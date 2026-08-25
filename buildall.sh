#!/bin/bash
#
# Build + install the gtk4 extension for every enabled PHP version.
#
#   ./buildall.sh                 # builds as the current user, installs with sudo
#   ONLY=8.5 ./buildall.sh        # one version from the table (also forces a disabled one)
#   WITH_WEBKIT=1 ./buildall.sh
#
# Standard phpize build: needs php<ver>-dev (phpize<ver>, php-config<ver>)
# and libgtk-4-dev. No PHP-CPP any more.
#
set -e
cd "$(dirname "$0")"

if [ "$EUID" -eq 0 ]; then
    echo "ERROR: run buildall.sh as your normal user; it uses sudo only for the install step" >&2
    exit 1
fi
JOBS=${JOBS:-$(nproc)}
FOREIGN=$(find . -path ./vendor -prune -o -path ./.git -prune -o ! -user "$(id -un)" -print 2>/dev/null | head -1)
if [ -n "$FOREIGN" ]; then
    echo "ERROR: build artifacts owned by $(stat -c %U "$FOREIGN") (e.g. $FOREIGN) - run: sudo chown -R \"\$USER\" ." >&2
    exit 1
fi
WITH_WEBKIT=${WITH_WEBKIT:-0}

# FORMAT: "version:enabled"   (enabled: 1=build by default, 0=skip; ONLY=x forces)
# Local default is 8.4 only; 8.5 is covered by the CI matrix.
BUILDS=(
    "8.4:1"
    "8.5:0"
)

for BUILD in "${BUILDS[@]}"; do
    IFS=: read -r VERSION ENABLED <<< "$BUILD"
    if [ -n "${ONLY:-}" ] && [ "$ONLY" != "$VERSION" ]; then continue; fi
    if [ "$ENABLED" -ne 1 ] && [ -z "${ONLY:-}" ]; then
        echo "=== Skipping PHP ${VERSION} (disabled) ==="; continue
    fi
    PHPIZE="/usr/bin/phpize${VERSION}"
    PHP_CONFIG="/usr/bin/php-config${VERSION}"
    if [ ! -x "$PHPIZE" ] || [ ! -x "$PHP_CONFIG" ]; then
        echo "=== Skipping PHP ${VERSION}: ${PHPIZE} / ${PHP_CONFIG} not found (apt install php${VERSION}-dev) ==="
        continue
    fi

    CONFIGURE_ARGS=(--with-php-config="$PHP_CONFIG")
    [ "$WITH_WEBKIT" = "1" ] && CONFIGURE_ARGS+=(--enable-gtk4-webkit)

    echo "=== Building for PHP ${VERSION} ==="
    # NEVER `phpize --clean`: it deletes tests/*.php (php-src assumes .phpt there).
    [ -f Makefile ] && make clean >/dev/null 2>&1 || true
    "$PHPIZE"
    ./configure "${CONFIGURE_ARGS[@]}"
    make -j"$JOBS"

    EXT_DIR=$("$PHP_CONFIG" --extension-dir)
    INI_DIR="/etc/php/${VERSION}/mods-available"
    echo "=== Installing gtk4.so -> ${EXT_DIR}, gtk4.ini -> ${INI_DIR} (sudo) ==="
    sudo install -m 644 modules/gtk4.so "${EXT_DIR}/"
    sudo install -m 644 gtk4.ini "${INI_DIR}/"
    # Deliberately NOT enabled globally: php-gtk3 (libgtk-3) and gtk4 cannot
    # share a process. Use bin/php-gtk4, or: phpenmod -v ${VERSION} gtk4 (+ phpdismod gtk3).
    if "/usr/bin/php${VERSION}" -n -dextension=gtk4 -r 'exit(extension_loaded("gtk4") ? 0 : 1);'; then
        echo "PHP ${VERSION}: gtk4 loads OK (php${VERSION} -n -dextension=gtk4)"
    else
        echo "WARNING: php${VERSION} -n -dextension=gtk4 failed"
    fi
done
echo "=== Done ==="
