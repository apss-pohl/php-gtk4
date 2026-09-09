<!-- markdownlint-disable-file MD041 -->
<!--
The title is a Conventional Commit: a squash merge makes it the commit on main, and it is what the
release notes are built from (`bin/commit-lint` checks it in CI).
Delete whatever does not apply - a one-line typo fix does not need the checklist.
-->

## What and why

<!-- What changes, and what made it necessary. Link the issue if there is one. -->

## How it was verified

<!-- The commands you ran, and anything you could not run here (a Windows build, a GTK version you
     do not have). `./ci.sh` locally covers what CI runs on Linux. -->

- [ ] `./ci.sh` passes
- [ ] tests cover the change — including the error path, not only the happy one

## For a new class, method or constant

All four land in the same PR or CI rejects it (CLAUDE.md "Definition of done"):

- [ ] implementation — `gen/allowlist.txt` + `gen/overrides/` for a generated class, `ZEND_METHOD` +
      `src/gtk4.cpp` MINIT otherwise
- [ ] tests — the generated smoke test, plus `tests/<Class>Test.php` for anything beyond round-tripping
- [ ] declaration in the stub, then `./ci.sh --only=gen,stubs --fix` (never edit a generated file)
- [ ] `examples/<Class>.php` returning `Demo::page(...)`, placed in `Demo::SECTIONS`

## Housekeeping

- [ ] a line under `## [Unreleased]` in `CHANGELOG.md` if this is user-visible
- [ ] `VERSION` untouched — bumping it is what publishes a release (`docs/RELEASING.md`)
- [ ] `config.m4` changes mirrored in `config.w32`
