
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

`.github/workflows/release.yml` reads the file and decides. Nothing else triggers a release: no tag pushes, no
manual `gh release create`, no commit-message conventions. Bumping the file *is* the release action.

## Cutting a release

```sh
$EDITOR VERSION                      # 0.2.0-dev -> 0.2.0
./ci.sh --only=version,stubs --fix   # propagate into the header + stub, regenerate
$EDITOR CHANGELOG.md                 # move Unreleased under '## [0.2.0] - YYYY-MM-DD'
./ci.sh --with=asan,coverage,valgrind
```

Open that as a `release: 0.2.0` PR. Merging it tags, builds, and publishes. Then immediately open the
follow-up: `VERSION` → `0.3.0-dev`, `./ci.sh --only=version,stubs --fix`, a fresh `## [Unreleased]` — so the
tree never sits in the state where merges publish nothing.

The `## [0.2.0]` section of `CHANGELOG.md` becomes the release body verbatim, and the workflow **fails** if it
is missing. That is deliberate: it is the one part of a release that cannot be reconstructed from git.

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
php-gtk4-0.2.0.tar.gz                                     # source dist - the supported install path
gtk4-0.2.0-php8.4-nts-x86_64-linux-gnu-ubuntu24.04.so
gtk4-0.2.0-php8.5-nts-x86_64-linux-gnu-ubuntu24.04.so
SHA256SUMS
```

A dev build is the same set with `0.2.0-dev.7` in place of `0.2.0`.

A `.so` is not portable the way a phar or a manylinux wheel is: it is bound to the PHP `ZEND_MODULE_API`
number, the thread-safety mode, the C++ ABI, glibc, and the GTK 4 soname it linked against. So the filename
carries that whole identity, and the release body says out loud that these are a convenience — the tarball is
the supported path. Built on `ubuntu-24.04`, the GTK 4.14 floor CI already targets: the binaries run there and
newer, not older. NTS only — ZTS builds compile and are tested in CI, but are not shipped as binaries.

Tags are created by the workflow and therefore cannot carry a maintainer's GPG signature.
`actions/attest-build-provenance` on the `.so` files is the replacement — verifiable with
`gh attestation verify <file> --repo apss-pohl/php-gtk4`, and it attests the artifact people actually run
rather than the commit.

## Gating

`release.yml` runs `./ci.sh` in full (PHP 8.4 and 8.5, ubuntu-24.04) before it builds anything for upload,
rather than keying off the result of `tests.yml`. That re-runs work the PR already did, and costs ~10 minutes
per merge. It buys a release job that cannot publish a broken artifact because of a `workflow_run` race, which
at this stage is the better half of the trade. `concurrency: release-main` with `cancel-in-progress: false`
serialises two merges landing back to back — which also keeps the prune step from racing itself.

Branch protection on `main` should require the `C++ static analysis`, `PHP QA (...)` and `PHP 8.4` /
`PHP 8.5` checks (the job names in `.github/workflows/`), with squash merges — one merge
is one commit is one dev build.

## Not covered yet

Publishing to PECL, or registering as a `php-ext` composer package so `pie install` works, is separate work
worth doing before 1.0. The tarball is already the phpize layout `pie` expects, so it is packaging and
registration, not a build change.
