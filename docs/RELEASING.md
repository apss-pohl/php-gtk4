
# Releasing

`VERSION` at the repo root is the single source of truth. One line, and the only thing a human edits to
change what the project calls itself:

```text
0.1.0-dev
```

The `-dev` suffix is the whole state machine. It says "this branch is developing *toward* 0.1.0" — which is
what makes a pre-release from an arbitrary merge meaningful; a pre-release has to be a candidate *for* a named
version, and without the suffix the file would name the version already shipped.

| `VERSION` | every merge into `main` publishes |
| --------- | --------------------------------- |
| `0.2.0-dev` | a dev build, **`v0.2.0-dev.<run>`**, as a pre-release |
| `0.2.0` | the real release **`v0.2.0`** — once; later merges are a no-op until someone bumps again |

`.github/workflows/release.yml` reads the file and decides. Nothing else *triggers* a release: no tag pushes,
no manual `gh release create`, no magic word in a commit. Bumping the file *is* the release action. (Commit
messages do shape what a release *says* — see "What a release body says" below.)

## Cutting a release

```sh
$EDITOR VERSION                      # 0.2.0-dev -> 0.2.0
./ci.sh --only=version,stubs --fix   # propagate into the header + stub, regenerate
$EDITOR CHANGELOG.md                 # move Unreleased under '## [0.2.0] - YYYY-MM-DD'
./update-deps.sh --only=gvsbuild     # Windows GTK pin: bump to gvsbuild's current release? (docs/BUILD.md)
./ci.sh --with=asan,coverage,valgrind   # fast feedback; release.yml runs these itself before publishing
```

Open that as a `chore(release): 0.2.0` PR — the title is the squashed commit, so it follows the commit
convention like every other one. Merging it tags, builds, and publishes. Then immediately open the
follow-up: `VERSION` → `0.3.0-dev`, `./ci.sh --only=version,stubs --fix`, a fresh `## [Unreleased]` — so the
tree never sits in the state where merges publish nothing.

## What a release body says

Two halves, in this order:

1. The `## [0.2.0]` section of `CHANGELOG.md`, **verbatim**. The workflow *fails* if it is missing —
   deliberately: the prose is the one part of a release that cannot be reconstructed from git. A dev
   build has no such section and gets the "development build, not a release" note instead.
2. `### Changes since v0.1.0` — every commit since the previous release, grouped by
   [Conventional Commit](https://www.conventionalcommits.org) type (`bin/release-notes`). That is why
   the format is mandatory and enforced on every commit and every PR title
   (`docs/CONTRIBUTING.md` "Commit messages"): a wrong type puts a change in the wrong section, and
   a subject written for nobody is what the release then says.

```sh
bin/release-notes                 # since the newest tag - what the next dev build will list
bin/release-notes --stable        # since the newest real release, skipping the v*-dev.* tags
bin/release-notes v0.1.0          # since that tag
```

A real release measures itself from the previous *real* release (`--stable`), a dev build from the
tag before it, so consecutive dev builds each list only what they added. Breaking changes (`!` or a
`BREAKING CHANGE:` footer) come first; anything whose subject is not conventional is kept under
"Other" rather than dropped — the notes never hide a commit.

## Version consistency

The version reaches four places, and every one of them is checked rather than trusted:

| Place | Written by | Checked by |
| ----- | ---------- | ---------- |
| `VERSION` | a human | — |
| `PHP_GTK4_VERSION` (`src/php_gtk4.h`) | `ci.sh --only=version --fix` | `version` stage |
| `const VERSION` (`src/gtk4.stub.php` → `Gtk4\VERSION`, arginfo, IDE stub) | same | `version` stage, `stubs` stage |
| the built `gtk4.so` (`phpversion('gtk4')`) | the compiler | `config.m4`, `load` stage, `ExtensionTest` |

- `./ci.sh --only=version` — mirrors agree with `VERSION`; part of the default stages and of `pre-commit`.
- `./ci.sh --only=version --fix` — rewrite the mirrors from `VERSION`. Follow with `--only=stubs --fix`.
- `config.m4` repeats the header check at **configure time** and aborts the build on a mismatch, so a
  hand-edited header cannot be compiled even by someone who never runs `ci.sh`.
- `./ci.sh --only=load` loads the freshly built `.so` and asserts `phpversion('gtk4') == Gtk4\VERSION ==
  ./VERSION`, which also catches a stale binary.
- `ExtensionTest::testConstantsAreNamespaced()` asserts the same three from inside the test suite.
- The release workflow re-checks each `.so` it is about to upload against `VERSION` before publishing it.

Nothing accepts a version from a command line or an environment variable. There is exactly one input.

## Dev builds

Every merge into `main` gets its own immutable pre-release, tagged `v<version>.<run>` — the version file
verbatim plus GitHub's monotonic run number:

```text
v0.2.0-dev.7   v0.2.0-dev.11   v0.2.0-dev.14   …
```

That is valid SemVer, and it sorts the way you want: `0.2.0-dev.7` < `0.2.0-dev.11` < `0.2.0` (numeric
prerelease identifiers compare numerically, and any prerelease sorts below the release). Run numbers skip
whenever a run does not publish; gaps are fine, collisions would not be.

**The newest five pre-releases stay downloadable.** After every publish the workflow deletes the rest, tags
included (`KEEP_PRERELEASES` in `release.yml`). The rule is uniform and does not care which version a
pre-release belongs to, so publishing a real release does not wipe the dev builds — it is simply one more
thing newer than them, and there are still five to fall back to. Deleting a release does not affect anyone
who already downloaded it.

Nothing is ever overwritten: a tag, once published, keeps pointing at the commit it was built from, and its
assets are the ones built from that commit. The cost is that there is no fixed "latest dev build" URL —
GitHub only serves `/releases/latest` for real releases. Find the newest with:

```sh
gh release list --limit 5 --json tagName,isPrerelease --jq '[.[]|select(.isPrerelease)][0].tagName'
```

A dev build's assets carry the `.<run>` suffix in their filenames while the module itself reports the plain
`0.2.0-dev` — two dev builds are different files and must not collide in a downloads folder, but the version
the binary reports still comes from `VERSION` and nowhere else. `PHPGTK_BUILD_INFO` (baked in by `config.m4`)
carries the git hash, so a build identifies its exact commit from the inside.

## Artifacts

Every release, dev builds included, carries:

```text
php-gtk4-0.2.0.tar.gz                                     # source dist - what PIE builds on Linux
gtk4-0.2.0-php8.4-nts-x86_64-linux-gnu-ubuntu24.04.so
gtk4-0.2.0-php8.5-nts-x86_64-linux-gnu-ubuntu24.04.so
php_gtk4-0.2.0-8.4-nts-vs17-x86_64.zip                    # Windows, one per PHP x thread model
php_gtk4-0.2.0-8.4-ts-vs17-x86_64.zip
php_gtk4-0.2.0-8.5-nts-vs17-x86_64.zip
php_gtk4-0.2.0-8.5-ts-vs17-x86_64.zip
SHA256SUMS
```

A dev build is the same set with `0.2.0-dev.7` in place of `0.2.0`.

A `.so` is not portable the way a phar or a manylinux wheel is: it is bound to the PHP `ZEND_MODULE_API`
number, the thread-safety mode, the C++ ABI, glibc, and the GTK 4 soname it linked against. So the filename
carries that whole identity, and the release body says out loud that these are a convenience — the source is
the supported path. Built on `ubuntu-24.04`, the GTK 4.14 floor CI already targets: the binaries run there and
newer, not older. NTS only — a ZTS Linux build comes from source.

The Windows assets are zips rather than bare dlls because that is what PIE downloads, and their names are
not decoration: PIE builds the name it looks for out of the extension name and the package version
(`php_<extension>-<version>-<php>-<ts|nts>-<compiler>-<arch>.zip`, lower case, holding a `.dll` of the same
name at the archive root). `PiePackageTest` pins the workflow to that shape. Both thread models ship,
because PIE never builds on Windows — an unpublished one is an install that finds nothing. They still need
a gvsbuild GTK 4 tree's `bin\` on `PATH` (docs/BUILD.md "Windows").

Tags are created by the workflow and therefore cannot carry a maintainer's GPG signature.
`actions/attest-build-provenance` on the `.so` files is the replacement — verifiable with
`gh attestation verify <file> --repo apss-pohl/php-gtk4`, and it attests the artifact people actually run
rather than the commit.

## Shipping

Two packages come out of this one repository, and neither is a second source of truth.

| Package | Registry | Installed with |
| ------- | -------- | -------------- |
| `php-gtk4/php-gtk4` | Packagist, from the root `composer.json` | `pie install php-gtk4/php-gtk4` |
| `php-gtk4/stubs` | Packagist, from the mirror below | `composer require --dev php-gtk4/stubs` |

The root `composer.json` is both the development manifest (`require-dev`, `scripts`, `autoload-dev` — all
of which a consumer ignores) and the published package: `"type": "php-ext"` plus a `php-ext` block is what
makes [PIE](https://github.com/php/pie) able to install it. `extension-name` is spelled out because PIE
would otherwise derive `php-gtk4` from the package name, and the module is `gtk4`. `configure-options`
is what lets `pie install php-gtk4/php-gtk4 --enable-gtk4-webkit` work.

On Linux and macOS PIE runs `phpize && ./configure && make && make install` against the Composer dist, so
whoever installs needs the build tools *and* the GTK 4 development headers — PIE does not install system
dependencies, and `config.m4` refuses anything below GTK 4.14 with a message that says so. On Windows PIE
does not build at all: it takes one of the release zips above.

## The stubs package

`stubs/` is also a Composer package, `php-gtk4/stubs`, so a project can `composer require --dev` the
IDE/PHPStan stub instead of pointing its editor at a checkout. Composer cannot install a subdirectory of a
repository, so the package has a repository of its own, `apss-pohl/php-gtk4-stubs`, that nobody edits: the
`publish-stubs` job in `release.yml` runs on every **real** release, copies `stubs/gtk4.php`,
`stubs/composer.json`, `stubs/extension.neon`, `stubs/README.md`, `LICENSE`, `THIRD-PARTY-NOTICES.md` and
`LICENSES/PHP-3.01.txt` into a checkout of it, commits, tags it with the same `vX.Y.Z` and pushes. The
notices go with it because `gtk4.php` is mostly GTK's own documentation and says so
(`DocsTest::testTheStubsPackageShipsTheThirdPartyNotices` pins the list). Packagist follows the tags, so
the stub version always equals the extension version. Dev builds are skipped: Composer does not understand
the `-dev.<run>` suffix, and an editor does not need a stub per merge.

The job needs one secret, `STUBS_DEPLOY_KEY`: the private half of a deploy key with write access on the stubs
repository (`ssh-keygen -t ed25519`, public key under that repository's *Deploy keys* with *Allow write
access*, private key as an Actions secret here). Without it the job prints a warning and publishes nothing,
so a fork without the key still releases. The package is deliberately without an `autoload` section - the
extension defines every class, loading the stub next to it would redeclare them all - and ships
`extension.neon` (`scanFiles`) under `extra.phpstan` so `phpstan/extension-installer` registers it.

## Gating

`release.yml` runs `./ci.sh` in full (PHP 8.4 and 8.5, ubuntu-24.04) before it builds anything for upload,
rather than keying off the result of `tests.yml`. That re-runs work the PR already did, and costs ~10 minutes
per merge. It buys a release job that cannot publish a broken artifact because of a `workflow_run` race, which
at this stage is the better half of the trade. `concurrency: release-main` with `cancel-in-progress: false`
serialises two merges landing back to back — which also keeps the prune step from racing itself.

Because that gate exists, `tests.yml`, `php-qa.yml` and `windows.yml` run on pull requests only: the commit
being merged is the one the pull request just validated, and this job validates it again on `main`. Leaving
`push: [main]` on them meant every merge paid for the same work three times over — a measured 132–146 Linux
and 52 Windows minutes, of which about two thirds was repetition. `cpp-lint.yml` is the exception and still
runs on `main`: a workflow run may read its own branch's caches and the *default* branch's, so a lint on main
is what keeps every pull request's clang-tidy cache warm. Its own `verify` here skips `cpp-lint` for the same
reason — clang-tidy does not depend on the PHP version, so this matrix used to run it twice per merge, each
time cold, which since the WebKit namespaces arrived no longer fits in the job's 30-minute budget.

A real release is gated on more than a dev build, because a dev pre-release is not what anybody
installs: `verify` adds the ZTS cells (the only run on `main` that exercises the thread model the extension
claims to support), a `release-gate` job runs the sanitizers and valgrind, and the Windows builds run the
PHPUnit suite against the very dll that ships. `publish` waits for all of them and for the coverage floor.
Those first two are what "Cutting a release" above asks the maintainer to run locally: the local run is for
fast feedback, and this is what makes forgetting it harmless rather than a shipped regression.

The Windows assets are built for real releases only. They are what `pie install` puts on a Windows machine,
and PIE resolves a release rather than a dev pre-release, so building four of them per merge produced
binaries nobody could install. `windows.yml` is what says whether Windows still compiles at all, on the pull
requests that touch anything it depends on and once a week regardless.

Branch protection on `main` should require the `C++ static analysis`, `PHP QA (...)` and `PHP 8.4` /
`PHP 8.5` checks (the job names in `.github/workflows/`), with squash merges — one merge
is one commit is one dev build. A required check must come from a workflow that always starts:
a `paths:` filter on one would mean it never runs for a change outside those paths, never reports,
and the pull request waits for a check that cannot arrive. That is why `cpp-lint.yml` is not filtered
and leans on its cache instead, and why `windows.yml` — which is filtered — is not a required check.
`WorkflowsTest` holds both ends of that to each other.

The same reasoning moved two checks off every push. `gcov` runs once per merge here rather than on every
push, because what matters is the floor (`COVERAGE_MIN_LINES`), not a number per commit. The ZTS cells in
`tests.yml` run when something under `src/core/` changed - ZTS is there to catch per-request state put in a
plain static instead of the module globals, so that is the change that can break it - and unconditionally
before a release. Both are safe to make conditional only because neither is a required status check; the
`changes` job always includes NTS, which is.

## Not covered yet

Publishing to PECL. `pie install php-gtk4/php-gtk4` works (see "Shipping"), and PECL is a separate
registration with its own review; whether it is worth having both is a 1.0 question.
