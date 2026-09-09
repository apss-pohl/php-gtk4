#!/usr/bin/env bash
#
# Update everything this repository pins to a third party, in one pass:
#
#   composer   composer.lock (and, with --major, the constraints in composer.json) for require-dev
#   npm        the exact devDependencies pins in package.json (markdownlint-cli2 for ./ci.sh --only=md-lint)
#   actions    uses: owner/repo@<ref> in .github/workflows/*.yml, to the newest release of each action
#   gvsbuild   GVSBUILD_VERSION/GVSBUILD_SHA256 in windows-build.yml - reported; applied only with --gvsbuild
#   genstub    gen/gen_stub.php against the php-src copy of the installed PHP - reported, re-vendored by hand
#   runners    runs-on images and the clang-N pins - reported, never rewritten
#
# The first three are what Dependabot already opens PRs for (.github/dependabot.yml); this script is
# the local equivalent, for when you want them all current in one commit instead of five PRs. The
# last three Dependabot cannot see: an env: value, a vendored file and a runner label.
#
# Usage:
#   ./update-deps.sh                 update composer, npm and the action pins; report the rest
#   ./update-deps.sh --check         change nothing, only report what is behind (exit 1 if anything is)
#   ./update-deps.sh --only=actions  one section (composer,npm,actions,gvsbuild,genstub,runners)
#   ./update-deps.sh --skip=composer everything but that section
#   ./update-deps.sh --major         composer: also raise a ^N constraint in composer.json to the new major
#   ./update-deps.sh --gvsbuild      also write the new Windows GTK pin (a deliberate act - docs/BUILD.md)
#   ./update-deps.sh --help          this text
#
# Nothing here commits, pushes or bumps ./VERSION. Review the diff, run ./ci.sh, then commit with the
# prefix the section prints (docs/CONTRIBUTING.md: build(deps-dev) for composer/npm, ci(deps) for CI).
#
# Env: PHP=php8.4 PHP_CONFIG=/usr/bin/php-config8.4 COMPOSER=/usr/local/bin/composer
#      GITHUB_TOKEN=... (only used when gh is not installed; the API is rate limited without it)
set -euo pipefail
cd "$(dirname "$0")"

PHP=${PHP:-php8.4}
PHP_CONFIG=${PHP_CONFIG:-/usr/bin/php-config8.4}
COMPOSER=${COMPOSER:-/usr/local/bin/composer}
WORKFLOWS=${WORKFLOWS:-.github/workflows}   # absolute path = dry run against a copy

ALL_SECTIONS="composer npm actions gvsbuild genstub runners"
ONLY=""; SKIP=""; CHECK=0; MAJOR=0; GVSBUILD=0
usage() { sed -n '2,/^set -/p' "$0" | sed '$d' | sed 's/^# \{0,1\}//'; }
for arg in "$@"; do
    case "$arg" in
        -h|--help)  usage; exit 0 ;;
        --only=*)   ONLY="${arg#--only=}" ;;
        --skip=*)   SKIP="${arg#--skip=}" ;;
        --check)    CHECK=1 ;;
        --major)    MAJOR=1 ;;
        --gvsbuild) GVSBUILD=1 ;;
        *)          echo "update-deps.sh: unknown option '$arg' (see --help)" >&2; exit 2 ;;
    esac
done
for section in ${ONLY//,/ } ${SKIP//,/ }; do
    [[ " $ALL_SECTIONS " == *" $section "* ]] ||
        { echo "update-deps.sh: unknown section '$section' (sections: $ALL_SECTIONS)" >&2; exit 2; }
done
enabled() {  # enabled <section>
    if [[ -n "$ONLY" ]]; then [[ ",$ONLY," == *",$1,"* ]] || return 1; fi
    [[ ",$SKIP," == *",$1,"* ]] && return 1
    return 0
}

BLUE='\033[1;34m'; RED='\033[1;31m'; GREEN='\033[1;32m'; YELLOW='\033[1;33m'; NC='\033[0m'
step() { printf "\n${BLUE}=== %s ===${NC}\n" "$*"; }
fail() { printf "${RED}FAILED: %s${NC}\n" "$*" >&2; exit 1; }
ok()   { printf "  ${GREEN}%s${NC}\n" "$*"; }
note() { printf "  ${YELLOW}%s${NC}\n" "$*"; }

BEHIND=0        # something is out of date (the exit code of --check)
CHANGED=()      # files this run rewrote
TODO=()         # what the summary tells you to do by hand

behind() { BEHIND=1; }
changed() { CHANGED+=("$1"); }
todo() { TODO+=("$1"); }

command -v jq >/dev/null || fail "jq is required (apt install jq)"
command -v curl >/dev/null || fail "curl is required"

# One GitHub API call, through gh when it is installed (its token lifts the rate limit) and through
# curl otherwise. Prints nothing and returns non-zero when the endpoint 404s (no release yet).
GH=$(command -v gh || true)
gh_api() {  # gh_api <path> [jq-filter]
    local path=$1 filter=${2:-.} auth=()
    if [[ -n "$GH" ]] && "$GH" auth status >/dev/null 2>&1; then
        "$GH" api "$path" --jq "$filter" 2>/dev/null
    else
        [[ -n "${GITHUB_TOKEN:-}" ]] && auth=(-H "Authorization: Bearer $GITHUB_TOKEN")
        curl -fsSL "${auth[@]}" -H 'Accept: application/vnd.github+json' \
            "https://api.github.com/$path" 2>/dev/null | jq -r "$filter" 2>/dev/null
    fi
}

# The newest release tag of a GitHub repository: its "latest release" if it publishes releases,
# else the highest semver-looking tag (some actions only tag).
latest_tag() {  # latest_tag <owner/repo>
    local tag
    tag=$(gh_api "repos/$1/releases/latest" .tag_name || true)
    if [[ -z "$tag" || "$tag" == "null" ]]; then
        tag=$(gh_api "repos/$1/tags?per_page=100" '.[].name' |
            grep -E '^v?[0-9]+(\.[0-9]+)*$' | sort -V | tail -1 || true)
    fi
    [[ -n "$tag" && "$tag" != "null" ]] && echo "$tag"
}

# sed-safe form of a string used as a BRE pattern (package and action names carry . / -).
quotemeta() { printf '%s' "$1" | sed 's/[][\.*^$/]/\\&/g'; }

# Replace one "key": "value" pair in a JSON file without reformatting the rest of it.
json_pin() {  # json_pin <file> <key> <new-value>
    local file=$1 key esc; key=$(quotemeta "$2"); esc=$(quotemeta "$3")
    sed -i "s/\(\"$key\"[[:space:]]*:[[:space:]]*\"\)[^\"]*\"/\1$3\"/" "$file"
    grep -q "\"$key\"[[:space:]]*:[[:space:]]*\"$esc\"" "$file" || fail "$file: could not pin $2 to $3"
}

# ---------------------------------------------------------------- composer (require-dev)
# `composer update` moves the lock inside the declared constraints; --major additionally raises a
# ^N to the new major, which is the only way a dev tool's next major ever lands here.
if enabled composer; then
    step "composer"
    [[ -x "$COMPOSER" || -f "$COMPOSER" ]] || fail "composer not found at $COMPOSER (COMPOSER=... to override)"
    outdated=$("$PHP" "$COMPOSER" outdated --direct --format=json --no-interaction 2>/dev/null || echo '{}')
    majors=$(jq -r '.installed // [] | .[] | select(.latest != null)
        | select((.version | ltrimstr("v") | split(".")[0]) != (.latest | ltrimstr("v") | split(".")[0]))
        | "\(.name) \(.version) \(.latest)"' <<<"$outdated")
    minors=$(jq -r '.installed // [] | .[] | select(.latest != null) | select(.version != .latest)
        | "\(.name) \(.version) -> \(.latest)"' <<<"$outdated")
    if [[ -n "$minors" ]]; then printf '  %s\n' "outdated (direct):"; sed 's/^/    /' <<<"$minors"; behind
    else ok "every direct requirement is at its newest version"; fi

    if [[ $CHECK -eq 0 ]]; then
        if [[ $MAJOR -eq 1 && -n "$majors" ]]; then
            while read -r name _ latest; do
                [[ -z "$name" ]] && continue
                # php itself is a project floor (PHP 8.4+, CLAUDE.md), never a dependency to bump.
                [[ "$name" == "php" ]] && continue
                major=${latest#v}; major=${major%%.*}
                printf '  raising %s to ^%s\n' "$name" "$major"
                "$PHP" "$COMPOSER" require --dev --no-interaction --no-progress --update-with-all-dependencies \
                    "$name:^$major" || fail "composer require $name:^$major"
                changed composer.json
            done <<<"$majors"
        elif [[ -n "$majors" ]]; then
            note "a new major is available for: $(cut -d' ' -f1 <<<"$majors" | tr '\n' ' ') (--major raises the constraint)"
            todo "./update-deps.sh --only=composer --major   # take the new majors"
        fi
        before=$(sha1sum composer.lock | cut -d' ' -f1)
        "$PHP" "$COMPOSER" update --no-interaction --no-progress --prefer-dist || fail "composer update"
        [[ "$(sha1sum composer.lock | cut -d' ' -f1)" != "$before" ]] && changed composer.lock
        "$PHP" "$COMPOSER" validate --no-check-publish --quiet || fail "composer validate"
    fi
    [[ -n "$minors" || -n "$majors" ]] && todo "commit: build(deps-dev): update the PHP dev tooling"
fi

# ---------------------------------------------------------------- npm (package.json pins)
# No lockfile is tracked (/package-lock.json is gitignored) - package.json *is* the pin, and ci.sh
# reads the version straight out of it, so rewriting the line is the whole update.
if enabled npm; then
    step "npm"
    while read -r name spec; do
        [[ -z "$name" ]] && continue
        latest=$(curl -fsSL "https://registry.npmjs.org/$name/latest" | jq -r .version 2>/dev/null || true)
        [[ -z "$latest" || "$latest" == "null" ]] && { note "$name: could not reach the npm registry"; continue; }
        prefix=""; [[ "$spec" =~ ^([~^]) ]] && prefix="${BASH_REMATCH[1]}"
        if [[ "$spec" == "$prefix$latest" ]]; then ok "$name $spec"; continue; fi
        behind
        printf '  %s %s -> %s%s\n' "$name" "$spec" "$prefix" "$latest"
        if [[ $CHECK -eq 0 ]]; then
            json_pin package.json "$name" "$prefix$latest"
            changed package.json
            todo "commit: build(deps-dev): update $name to $latest"
        fi
    done < <(jq -r '.devDependencies // {} | to_entries[] | "\(.key) \(.value)"' package.json)
fi

# ---------------------------------------------------------------- github actions
# Every uses: owner/repo@ref in the workflows, kept in the style it is written in: a floating major
# (@v7) follows the new major, a full version follows the new tag, a SHA pin follows the new tag's
# commit (and its trailing "# vX" comment with it). Local calls (uses: ./...) have no @ and are skipped.
if enabled actions; then
    step "github actions"
    while read -r use; do
        repo=${use%@*}; ref=${use#*@}
        tag=$(latest_tag "$repo" || true)
        [[ -z "$tag" ]] && { note "$repo: no release or tag found (API rate limit? gh auth login)"; continue; }
        major=${tag#v}; major="v${major%%.*}"
        case "$ref" in
            v[0-9])                 want=$major ;;
            v[0-9][0-9])            want=$major ;;
            v[0-9]*.[0-9]*)         want=$tag ;;
            [0-9a-f][0-9a-f][0-9a-f][0-9a-f]*) want=$(gh_api "repos/$repo/commits/$tag" .sha || true) ;;
            *)                      note "$repo@$ref: unrecognised pin style, left alone"; continue ;;
        esac
        [[ -z "$want" ]] && { note "$repo@$ref: could not resolve $tag"; continue; }
        if [[ "$want" == "$ref" ]]; then ok "$repo@$ref"; continue; fi
        behind
        printf '  %s %s -> %s (%s)\n' "$repo" "$ref" "$want" "$tag"
        if [[ $CHECK -eq 0 ]]; then
            pat="$(quotemeta "$repo")@$(quotemeta "$ref")"
            for yml in "$WORKFLOWS"/*.yml; do
                grep -q "$pat" "$yml" || continue
                # A SHA pin carries the human-readable tag as a trailing comment; keep it truthful.
                if [[ ${#want} -eq 40 ]]; then
                    sed -i "s|$pat[[:space:]]*\(#.*\)\?$|$repo@$want # $tag|" "$yml"
                else
                    sed -i "s|$pat|$repo@$want|g" "$yml"
                fi
                changed "$yml"
            done
            todo "commit: ci(deps): update the GitHub Actions pins"
        fi
    done < <(grep -hoE 'uses:[[:space:]]*[A-Za-z0-9._-]+/[A-Za-z0-9._-]+@[A-Za-z0-9._-]+' "$WORKFLOWS"/*.yml |
        sed -E 's/uses:[[:space:]]*//' | sort -u)
fi

# ---------------------------------------------------------------- gvsbuild (Windows GTK)
# docs/BUILD.md "The pinned GTK version": every published DLL is linked against this archive, the
# hash is what makes the download trustworthy, and moving it is a decision (a release checklist item,
# not a schedule). So this reports by default and only writes with --gvsbuild.
if enabled gvsbuild; then
    step "gvsbuild (Windows GTK pin)"
    recipe="$WORKFLOWS/windows-build.yml"
    cur_ver=$(sed -n "s/^[[:space:]]*GVSBUILD_VERSION:[[:space:]]*'\([^']*\)'.*/\1/p" "$recipe")
    cur_sha=$(sed -n "s/^[[:space:]]*GVSBUILD_SHA256:[[:space:]]*'\([^']*\)'.*/\1/p" "$recipe")
    [[ -n "$cur_ver" ]] || fail "$recipe: no GVSBUILD_VERSION pin"
    new_ver=$(latest_tag wingtk/gvsbuild || true)
    if [[ -z "$new_ver" ]]; then
        note "could not reach the gvsbuild releases API; pin stays at $cur_ver"
    elif [[ "$new_ver" == "$cur_ver" ]]; then
        ok "GVSBUILD_VERSION $cur_ver (newest)"
    else
        behind
        printf '  gvsbuild %s -> %s\n' "$cur_ver" "$new_ver"
        new_sha=$(gh_api "repos/wingtk/gvsbuild/releases/tags/$new_ver" \
            '.assets[] | select(.name | test("GTK4")) | .digest' | sed 's/^sha256://' || true)
        if [[ ! "$new_sha" =~ ^[0-9a-f]{64}$ ]]; then
            note "the $new_ver release has no GTK4 asset digest - read the hash by hand (docs/BUILD.md)"
        elif [[ $GVSBUILD -eq 1 ]]; then
            sed -i -e "s/^\([[:space:]]*GVSBUILD_VERSION:[[:space:]]*'\)$cur_ver'/\1$new_ver'/" \
                   -e "s/^\([[:space:]]*GVSBUILD_SHA256:[[:space:]]*'\)$cur_sha'/\1$new_sha'/" "$recipe"
            changed "$recipe"
            ok "pinned $new_ver ($new_sha)"
            todo "commit: ci(deps): build Windows against gvsbuild $new_ver   # then let windows.yml prove it"
        else
            note "GTK4 asset sha256: $new_sha"
            todo "./update-deps.sh --only=gvsbuild --gvsbuild   # take gvsbuild $new_ver, then push and watch windows.yml"
        fi
    fi
fi

# ---------------------------------------------------------------- vendored gen/gen_stub.php
# gen/gen_stub.php is php-src's build/gen_stub.php with two local hunks (the licence header and the
# initPhpParser() patch that uses composer's nikic/php-parser - gen/README.md). Two hunks means only
# our patch differs, so upstream has not moved; more means it has and the file wants re-vendoring.
if enabled genstub; then
    step "gen/gen_stub.php (vendored from php-src)"
    api=$("$PHP_CONFIG" --phpapi 2>/dev/null || true)
    sys="/usr/lib/php/${api}/build/gen_stub.php"
    if [[ -z "$api" || ! -f "$sys" ]]; then
        note "no php-src copy at ${sys:-/usr/lib/php/<api>/build/gen_stub.php} (install php8.4-dev to compare)"
    else
        hunks=$(diff -u "$sys" gen/gen_stub.php | grep -c '^@@' || true)
        if [[ "$hunks" -le 2 ]]; then
            ok "in sync with $sys (only the local patch differs)"
        else
            behind
            note "$sys has moved on ($hunks differing hunks, 2 of them ours)"
            todo "re-vendor gen/gen_stub.php: copy $sys, re-apply the header + initPhpParser() patch (gen/README.md), then ./ci.sh --only=stubs"
        fi
    fi
fi

# ---------------------------------------------------------------- runner images and toolchain pins
# Nothing rewrites these: the Ubuntu image is the GTK 4.14 floor the project tests against
# (CLAUDE.md), and the clang version is what .vscode and CI agree on. Reported so a bump is a
# conscious one.
if enabled runners; then
    step "runner images and toolchain pins (report only)"
    grep -hoE 'runs-on:[[:space:]]*[A-Za-z0-9._-]+' "$WORKFLOWS"/*.yml | sed -E 's/runs-on:[[:space:]]*//' |
        sort | uniq -c | sed 's/^/  /'
    grep -hoE 'clang-(tidy|format)-[0-9]+' "$WORKFLOWS"/*.yml .vscode/*.json 2>/dev/null |
        sort -u | sed 's/^/  /'
    grep -hoE "php: \[[^]]*\]" "$WORKFLOWS"/*.yml | sort -u | sed 's/^/  matrix /'
    note "bumped by hand: the Ubuntu image is the GTK 4.14 floor, the PHP versions are the support matrix"
fi

# ---------------------------------------------------------------- summary
step "summary"
if [[ ${#CHANGED[@]} -gt 0 ]]; then
    printf '  changed:\n'
    printf '%s\n' "${CHANGED[@]}" | sort -u | sed 's/^/    /'
else
    ok "nothing was rewritten"
fi
if [[ ${#TODO[@]} -gt 0 ]]; then
    printf '  next:\n'
    printf '%s\n' "${TODO[@]}" | awk '!seen[$0]++' | sed 's/^/    /'
fi
if [[ ${#CHANGED[@]} -gt 0 ]]; then
    printf '  verify:\n'
    printf '    %s\n' './ci.sh --only=php-qa,md-lint' './ci.sh --only=test --filter WorkflowsTest' './ci.sh'
fi
if [[ $CHECK -eq 1 && $BEHIND -eq 1 ]]; then
    printf "  ${YELLOW}%s${NC}\n" "something is behind (--check found it, nothing was written)"
    exit 1
fi
exit 0
