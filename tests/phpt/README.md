# `tests/phpt` — process-level tests (`make test`)

php-src's `run-tests.php` harness, run by `./ci.sh --only=phpt` (or
`GSK_RENDERER=cairo xvfb-run -a make test TESTS=tests/phpt`).

This is the **second** harness. The primary suite is PHPUnit in `tests/*.php`
(`./ci.sh --only=test`) and every new class/method is tested there — see the
definition of done in CLAUDE.md. Only put a test here when it asserts something
PHPUnit structurally cannot reach, because each test is its own process and the
harness compares the process's whole stdout **and stderr**:

| category | why not PHPUnit |
| --- | --- |
| a GLib `CRITICAL` under `gtk4.diagnostics=fatal` | it is an `E_ERROR`, so it ends the process - PHPUnit cannot survive one (`src/core/diagnostics.cpp`) |
| uncaught fatals, exit codes | the throwable that escapes would kill the PHPUnit runner itself |
| RSHUTDOWN teardown output | request shutdown happens after the last PHPUnit assertion can run |
| `--ENV--` / `--INI--` dependent startup | `tests/bootstrap.php` fixes the environment before any test runs |
| segfault isolation | a crash aborts the whole PHPUnit run; run-tests names the test and continues |

`make test` builds its `tmp-php.ini` by stripping every `extension=` line from the
scanned ini dir and re-adding only `modules/gtk4.so`, so php-gtk3 is filtered out
here without `bin/php-gtk4` (`extension-isolation.phpt` guards that).

Tests that need a display carry a `--SKIPIF--` guard on `DISPLAY`.

What is here, by the rule above:

| test | the thing PHPUnit cannot reach |
| ---- | ------------------------------ |
| `error-log-signal`, `error-log-callback`, `error-log-async-callback`, `error-handler-throws` | the uncaught-handler warning text, including which method an exception is blamed on |
| `error-rethrow-fatal`, `error-rethrow-mainloop` | a Throwable that escapes, and the exit code it leaves |
| `shutdown-teardown`, `shutdown-subtype`, `shutdown-io-watch` | what request shutdown does with closures, a live PHP subclass and an armed I/O watch |
| `variant-recursion-guard` | two inputs that used to be a SIGSEGV |
| `init-no-display`, `ini-directives`, `extension-isolation` | startup that depends on `--INI--`/`--ENV--` or on no display at all |
| `debug-info-boxed` | `var_dump()` output of a handle |
| `diagnostics-fatal` | `gtk4.diagnostics=fatal` raises an `E_ERROR`, which ends the process mid-script |
| `diagnostics-modes`, `diagnostics-stderr` | the mode an `--INI--` picks before the first line runs, and `gtk4.diagnostics=stderr`, whose whole output *is* the process' stderr |
| `diagnostics-off-ini` | `gtk4.diagnostics=off` in an `--INI--`, which PHP's ini scanner turns into the empty string long before the extension sees it |
| `vfunc-unbound-warning` | a warning emitted once per GType, at the registration only a fresh process still has ahead of it |

Every `.phpt` in this directory has a row above - `DocsTest::testPhptReadmeListsEveryTest()`
fails on one that does not.
