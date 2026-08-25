#!/usr/bin/env bash
#
# The one QA entry point - same stages as the GitHub workflows (.github/workflows/*.yml):
#
#   cpp-lint   clang-format --dry-run + clang-tidy over src/**, main.*, version.cpp
#   php-qa     phplint -> phpcs -> php-cs-fixer check -> phpstan (max)   [tests/ examples/ stubs/ gen/]
#   build      make gtk4.so
#   load       php -n -dextension=./gtk4.so smoke check
#   test       PHPUnit under xvfb-run via bin/php-gtk4 (gtk3 filtered out)
#
# Usage:
#   ./ci.sh                          all stages, in that order
#   ./ci.sh --fix                    apply clang-tidy/clang-format/phpcbf/php-cs-fixer fixes first
#   ./ci.sh --only=test              one stage   (--only=cpp-lint,php-qa for several)
#   ./ci.sh --skip=cpp-lint,php-qa   fastest edit-build-test loop
#   ./ci.sh --filter SignalTest      unknown args are passed to phpunit
#   ./ci.sh --no-stan                php-qa without phpstan
#   ./ci.sh --fail-fast              clang-tidy stops at the first failing file
#   ./ci.sh --skip=tidy | --skip=format   sub-steps of cpp-lint
#
# Env: PHP=php8.4 PHP_CONFIG=/usr/bin/php-config8.4 PHPCPP_STATIC=... PHPCPP_BASE=... BUILD_DIR=build
#      JOBS=$(nproc) CLANG_TIDY=clang-tidy-17 CLANG_FORMAT=clang-format-17 COMPOSER=/usr/local/bin/composer
set -euo pipefail
cd "$(dirname "$0")"

PHP=${PHP:-php8.4}
PHP_CONFIG=${PHP_CONFIG:-/usr/bin/php-config8.4}
PHPCPP_BASE=${PHPCPP_BASE:-/mnt/share/dev/code/PHP-CPP/dist}
BUILD_DIR=${BUILD_DIR:-build}
JOBS=${JOBS:-$(nproc)}
COMPOSER=${COMPOSER:-/usr/local/bin/composer}
CLANG_TIDY=${CLANG_TIDY:-clang-tidy-17}
CLANG_FORMAT=${CLANG_FORMAT:-clang-format-17}

ALL_STAGES="cpp-lint php-qa build load test"
ONLY=""; SKIP=""; FIX=0; STAN=1; FAIL_FAST=0; PHPUNIT_ARGS=()
for arg in "$@"; do
    case "$arg" in
        --only=*)   ONLY="${arg#--only=}" ;;
        --skip=*)   SKIP="${arg#--skip=}" ;;
        --fix)      FIX=1 ;;
        --no-stan)  STAN=0 ;;
        --fail-fast) FAIL_FAST=1 ;;
        *)          PHPUNIT_ARGS+=("$arg") ;;
    esac
done

enabled() {  # enabled <stage>
    if [[ -n "$ONLY" ]]; then [[ ",$ONLY," == *",$1,"* ]] || return 1; fi
    [[ ",$SKIP," == *",$1,"* ]] && return 1
    return 0
}

BLUE='\033[1;34m'; RED='\033[1;31m'; GREEN='\033[1;32m'; NC='\033[0m'
step() { printf "\n${BLUE}=== %s ===${NC}\n" "$*"; }
fail() { printf "${RED}FAILED: %s${NC}\n" "$*" >&2; exit 1; }

# ---------------------------------------------------------------- helpers
# ini scan dir with gtk3/gtk4 removed, for tools that must not load GTK
gtk_free_ini_dir() {
    local scan tmp
    scan=$("$PHP" -n -r 'echo PHP_CONFIG_FILE_SCAN_DIR;')
    tmp=$(mktemp -d)
    if [[ -d "$scan" ]]; then
        for ini in "$scan"/*.ini; do
            [[ -e "$ini" ]] || continue
            grep -qE '^\s*extension\s*=\s*"?(php_)?gtk[34]?(\.so)?"?\s*$' "$ini" && continue
            ln -s "$ini" "$tmp/$(basename "$ini")"
        done
    fi
    echo "$tmp"
}

ensure_vendor() {
    if [[ ! -x vendor/bin/phpunit || ! -x vendor/bin/phpstan ]]; then
        step "composer install"
        "$PHP" "$COMPOSER" install --no-interaction --no-progress --prefer-dist || fail "composer install"
    fi
}

# ---------------------------------------------------------------- cpp-lint
stage_cpp_lint() {
    local action="checking"; [[ $FIX -eq 1 ]] && action="fixing"
    local -a include_flags=(--extra-arg=-std=c++20 --extra-arg=-I"$PWD")
    while IFS= read -r flag; do
        [[ -n "$flag" ]] && include_flags+=("--extra-arg=$flag")
    done < <(
        { "$PHP_CONFIG" --includes 2>/dev/null || true
          pkg-config --cflags glib-2.0 gobject-2.0 gtk4 2>/dev/null
          pkg-config --cflags webkitgtk-6.0 2>/dev/null || true; } | tr ' ' '\n'
    )
    mapfile -t cpp_files < <(
        { find src -type f -name '*.cpp'; ls main.cpp version.cpp; } | sort
    )
    mapfile -t all_files < <(
        { find src -type f \( -name '*.cpp' -o -name '*.h' \); ls main.cpp main.h version.cpp; } | sort
    )

    local err_dir; err_dir=$(mktemp -d)
    # shellcheck disable=SC2064
    trap "rm -rf '$err_dir'" RETURN

    if enabled tidy; then
        step "clang-tidy ($CLANG_TIDY, $JOBS jobs, $action)"
        local active=0
        for f in "${cpp_files[@]}"; do
            [[ $FAIL_FAST -eq 1 && -f "$err_dir/stop" ]] && break
            (
                echo "  $action: $f"
                local -a args=(--quiet "${include_flags[@]}")
                [[ $FIX -eq 1 ]] && args+=(--fix --fix-errors)
                if ! "$CLANG_TIDY" "$f" "${args[@]}" -- 2>&1; then
                    touch "$err_dir/$(basename "$f").tidy"
                    [[ $FAIL_FAST -eq 1 ]] && touch "$err_dir/stop"
                fi
            ) &
            active=$((active + 1))
            if [[ $active -ge $JOBS ]]; then wait -n 2>/dev/null || wait; active=$((active - 1)); fi
        done
        wait
    fi

    if enabled format; then
        step "clang-format ($CLANG_FORMAT, $JOBS jobs, $action)"
        local active=0
        for f in "${all_files[@]}"; do
            (
                if [[ $FIX -eq 1 ]]; then
                    "$CLANG_FORMAT" -i "$f"
                elif ! "$CLANG_FORMAT" --dry-run --Werror "$f" 2>&1; then
                    touch "$err_dir/$(basename "$f").fmt"
                fi
            ) &
            active=$((active + 1))
            if [[ $active -ge $JOBS ]]; then wait -n 2>/dev/null || wait; active=$((active - 1)); fi
        done
        wait
    fi

    local tidy_errors fmt_errors
    tidy_errors=$(find "$err_dir" -name '*.tidy' | wc -l)
    fmt_errors=$(find "$err_dir" -name '*.fmt' | wc -l)
    echo "  clang-tidy errors: $tidy_errors, clang-format errors: $fmt_errors"
    [[ $tidy_errors -eq 0 && $fmt_errors -eq 0 ]] || fail "cpp-lint"
}

# ---------------------------------------------------------------- php-qa
stage_php_qa() {
    ensure_vendor
    local ini_dir; ini_dir=$(gtk_free_ini_dir)
    # shellcheck disable=SC2064
    trap "rm -rf '$ini_dir'" RETURN
    local -a run=(env PHP_INI_SCAN_DIR="$ini_dir" "$PHP" -d memory_limit=-1)
    local status=0

    step "phplint (-j $JOBS)"
    "${run[@]}" vendor/bin/phplint -j "$JOBS" --no-progress || status=1

    if [[ $FIX -eq 1 ]]; then
        step "phpcbf (--parallel=$JOBS)"
        "${run[@]}" vendor/bin/phpcbf --parallel="$JOBS" || true   # exit 1 = fixed something
        step "php-cs-fixer fix (all cores)"
        "${run[@]}" vendor/bin/php-cs-fixer fix --show-progress=none || status=1
    fi

    step "phpcs (--parallel=$JOBS)"
    "${run[@]}" vendor/bin/phpcs --parallel="$JOBS" || status=1

    step "php-cs-fixer check (all cores)"
    "${run[@]}" vendor/bin/php-cs-fixer check --diff --show-progress=none || status=1

    if [[ $STAN -eq 1 ]]; then
        step "phpstan (level max, all cores)"
        "${run[@]}" vendor/bin/phpstan analyse --no-progress --memory-limit=-1 || status=1
    fi
    [[ $status -eq 0 ]] || fail "php-qa"
}

# ---------------------------------------------------------------- build
stage_build() {
    step "build ($PHP_CONFIG -> $BUILD_DIR, -j$JOBS)"
    if [[ -e "$BUILD_DIR" && ! -w "$BUILD_DIR" ]] || [[ -e gtk4.so && ! -w gtk4.so ]]; then
        fail "$BUILD_DIR/ or gtk4.so is not writable (owned by $(stat -c %U "$BUILD_DIR")) - run: sudo chown -R \"\$USER\" $BUILD_DIR gtk4.so"
    fi
    local static="${PHPCPP_STATIC:-}"
    if [[ -z "$static" ]]; then
        local ver; ver=$("$PHP_CONFIG" --version | cut -d. -f1,2)
        static=$(find "$PHPCPP_BASE/php$ver" -maxdepth 1 -name 'libphpcpp.a.*' 2>/dev/null | head -1 || true)
    fi
    if [[ -n "$static" ]]; then
        echo "  libphpcpp: $static"
        make -j"$JOBS" PHP_CONFIG="$PHP_CONFIG" BUILD_DIR="$BUILD_DIR" PHPCPP_STATIC="$static" || fail "build"
    else
        echo "  no static libphpcpp under $PHPCPP_BASE - linking system -lphpcpp"
        make -j"$JOBS" PHP_CONFIG="$PHP_CONFIG" BUILD_DIR="$BUILD_DIR" || fail "build"
    fi
}

# ---------------------------------------------------------------- load
stage_load() {
    step "extension loads"
    [[ -f gtk4.so ]] || fail "gtk4.so missing (run the build stage)"
    "$PHP" -n -dextension=./gtk4.so -r 'exit(extension_loaded("gtk4") ? 0 : 1);' || fail "gtk4.so does not load"
    "$PHP" -n -dextension=./gtk4.so -r 'echo "  ", Gtk4\PHPGTK_BUILD_INFO, " | ", Gtk4\PHPGTK_FEATURES, PHP_EOL;'
}

# ---------------------------------------------------------------- test
stage_test() {
    ensure_vendor
    step "phpunit (xvfb-run + bin/php-gtk4)"
    [[ -f gtk4.so ]] || fail "gtk4.so missing (run the build stage)"
    PHP="$PHP" GTK4_SO=./gtk4.so ./tests/run.sh "${PHPUNIT_ARGS[@]}" || fail "test"
}

# ---------------------------------------------------------------- main
ran=""
for stage in $ALL_STAGES; do
    enabled "$stage" || continue
    case "$stage" in
        cpp-lint) stage_cpp_lint ;;
        php-qa)   stage_php_qa ;;
        build)    stage_build ;;
        load)     stage_load ;;
        test)     stage_test ;;
    esac
    ran="$ran $stage"
done
[[ -n "$ran" ]] || fail "no stage selected (stages: $ALL_STAGES)"
printf "\n${GREEN}All checks passed:${NC}%s\n" "$ran"
