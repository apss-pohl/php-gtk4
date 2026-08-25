#!/usr/bin/env bash
# Runs the PHPUnit suite against ./gtk4.so under Xvfb, with gtk3 filtered out
# of the loaded extensions (bin/php-gtk4). Extra args go to phpunit:
#   ./tests/run.sh                          # whole suite
#   ./tests/run.sh --filter SignalTest      # one class
#   ./tests/run.sh --filter 'testReturnValue'
#   PHP=php8.4 GTK4_SO=/path/gtk4.so ./tests/run.sh
set -u
cd "$(dirname "$0")/.."
export PHP=${PHP:-php8.4}
export GTK4_SO=${GTK4_SO:-./gtk4.so}
# Xvfb has no GL/DRI3: use the cairo renderer so GTK never initialises EGL
# (avoids "libEGL warning: DRI3 error" noise and any GL-driver flakiness).
export GSK_RENDERER=${GSK_RENDERER:-cairo}
export LIBGL_ALWAYS_SOFTWARE=1
# CRITICAL lines from ErrorTest are expected: they are the g_critical() fallback
# path for exceptions thrown in handlers when no Gtk::set_exception_handler is set.
[[ -x vendor/bin/phpunit ]] || { echo "vendor/bin/phpunit missing - run: $PHP /usr/local/bin/composer install" >&2; exit 1; }
# PHP_GTK4_ENV: extra VAR=value pairs for the php process (ci.sh --only=asan uses it for LD_PRELOAD etc.)
# shellcheck disable=SC2086
exec xvfb-run -a env ${PHP_GTK4_ENV:-} bin/php-gtk4 vendor/bin/phpunit "$@"
