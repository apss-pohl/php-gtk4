#!/usr/bin/env bash
# Runs the PHPUnit suite against ./gtk4.so under Xvfb, with gtk3 filtered out
# of the loaded extensions (bin/php-gtk4). Extra args go to phpunit:
#   ./tests/run.sh                          # whole suite
#   ./tests/run.sh --filter SignalTest      # one class
#   ./tests/run.sh --filter 'testReturnValue'
#   PHP=php8.4 GTK4_SO=/path/gtk4.so ./tests/run.sh
set -euo pipefail
cd "$(dirname "$0")/.."
export PHP=${PHP:-php8.4}
export GTK4_SO=${GTK4_SO:-./gtk4.so}
# Xvfb has no GL/DRI3: use the cairo renderer so GTK never initialises EGL
# (avoids "libEGL warning: DRI3 error" noise and any GL-driver flakiness).
export GSK_RENDERER=${GSK_RENDERER:-cairo}
export LIBGL_ALWAYS_SOFTWARE=1
# ...and keep GDK from initialising GL at all (Mesa's llvmpipe leaks thread pools under
# valgrind/LSan on CI runners, and Xvfb prints libEGL DRI3 warnings on stderr).
# GTK 4.14 (the CI floor) honours GDK_DEBUG=gl-disable; GDK_DISABLE=gl is the 4.16+ spelling and
# is ignored on 4.14 - and setting both makes 4.14 ignore GDK_DEBUG. Revisit when the floor moves.
export GDK_DEBUG=${GDK_DEBUG:-gl-disable}
# GDK prefers Wayland over the DISPLAY xvfb-run provides when WAYLAND_DISPLAY is set in the
# developer's session - the suite would then run on the real compositor (and hit a GTK
# Wayland-backend heap corruption on 4.14). Pin the X11 backend: Xvfb is the target.
# Forced, not defaulted: ${GDK_BACKEND:-x11} keeps an inherited GDK_BACKEND=wayland, which
# is exactly the case this guards against - a desktop session exports it. Unset the socket
# too, or GDK still finds the compositor.
export GDK_BACKEND=x11
unset WAYLAND_DISPLAY
# Never run the suite under xdebug: its develop-mode observer segfaults at
# request shutdown after ReflectionMethod::invoke() on PHP-CPP methods
# (EveryClassTest), and it slows everything down. The stress/ASan runs use
# php -n and never load it.
export XDEBUG_MODE=off
# CRITICAL lines from ErrorTest are expected: they are the g_critical() fallback
# path for exceptions thrown in handlers when no Gtk::set_exception_handler is set.
[[ -x vendor/bin/phpunit ]] || { echo "vendor/bin/phpunit missing - run: $PHP /usr/local/bin/composer install" >&2; exit 1; }
# PHP_GTK4_ENV (optional): extra VAR=value pairs bin/php-gtk4 applies to the php
# process only (ci.sh --only=asan uses it for LD_PRELOAD=libasan etc.).
export PHP_GTK4_ENV=${PHP_GTK4_ENV:-}
exec xvfb-run -a bin/php-gtk4 vendor/bin/phpunit "$@"
