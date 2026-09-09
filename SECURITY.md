# Security policy

## Supported versions

php-gtk4 is 0.x: only the newest release gets fixes, and the API may change between releases. There is no
long-term branch to backport to.

| Version | Supported |
| --- | --- |
| newest release | yes |
| anything older | no — upgrade |

## Reporting a vulnerability

**Do not open a public issue.** Use GitHub's private reporting: the *Security* tab of this repository →
*Report a vulnerability*. That opens a thread only the maintainer can see. If that page is unavailable to
you, mail <pohl@apsservices.de> with `php-gtk4` in the subject.

What makes a report actionable:

- the PHP version and build (`php -v`, NTS or ZTS) and `php -r 'echo Gtk4\BUILD_INFO;'`, which names the
  GTK version the extension was built against and the features it has,
- the GTK version actually loaded (`Gtk4\Gtk::get_major_version()` and friends — the library in use is not
  always the one built against),
- a PHP script that reproduces it, as small as you can make it, and whether it needs a display,
- what you expected instead: an exception, a refused argument, an error.

Expect a first answer within a week. This is a single-maintainer project with no bounty programme; a fix
ships in the next release, and you are credited in `CHANGELOG.md` unless you would rather not be.

## What counts as a vulnerability here

php-gtk4 runs desktop applications from the command line. **The PHP script is trusted** — it already has
the machine, so a script that deliberately crashes its own process is not a security bug. The threat model
is untrusted *data* reaching a trusted script: a `.ui` file, an image, clipboard contents, a `GVariant`, a
file path, or anything a `WebKitWebView` loads.

In scope:

- memory unsafety reachable from PHP values — a use-after-free, a buffer overflow, type confusion between
  a PHP handle and the GObject behind it,
- a value a PHP script can build that ends the process instead of raising a PHP error. The binding's own
  rule is that this must not happen (README.md "Design", `RobustnessTest`), so a case that does is a bug
  worth reporting even when nobody untrusted is involved,
- untrusted data that reaches code execution it should not: a `.ui` file resolving a signal handler the
  application never registered, a deserialised value escaping its type, an out-of-bounds read fed by an
  image or a clipboard payload.

Out of scope, and better filed as an ordinary issue or upstream:

- bugs inside GTK, GLib or WebKitGTK themselves — report those to their projects; php-gtk4 guards or pins
  what it can (`docs/TODO.md` records the ones it works around),
- a GLib `CRITICAL` that stays a PHP warning and does not end the process,
- the `Gtk::testing_*` hooks, which exist for the test suite and say so in their docblocks,
- anything requiring the attacker to already run arbitrary PHP in the process.
