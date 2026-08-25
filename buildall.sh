#!/bin/bash
#
# One-liner build + install of the gtk4 extension for every enabled PHP version.
#
#   ./buildall.sh                       # builds & installs all enabled versions (re-execs with sudo)
#   PHPCPP_BASE=/custom/dist ./buildall.sh
#   ONLY=8.4 ./buildall.sh              # restrict to one version from the table
#
# Requires per-version static libphpcpp in ${PHPCPP_BASE}/php<version>/ as
# produced by PHP-CPP/build-dist.sh. The php-config listed in BUILDS must be
# the same one that libphpcpp was built against - nothing checks the pairing,
# and a mismatch produces a .so that loads and then corrupts the heap on
# shutdown ("free(): invalid pointer" after the script finished).
#
set -e
cd "$(dirname "$0")"

if [ "$EUID" -ne 0 ]; then
    exec sudo PHPCPP_BASE="$PHPCPP_BASE" ONLY="$ONLY" "$0" "$@"
fi

PHPCPP_BASE="${PHPCPP_BASE:-/mnt/share/dev/code/PHP-CPP/dist}"
JOBS=${JOBS:-$(nproc)}

# FORMAT: "version:php_config_path:ini_dir:with_webkit:enabled"
# The extension dir is taken from php-config --extension-dir.
# enabled: 1=build by default, 0=skip. PHP 8.4+ only - the Makefile rejects older php-configs.
BUILDS=(
    "8.4:/usr/bin/php-config8.4:/etc/php/8.4/mods-available:0:1"
)

for BUILD in "${BUILDS[@]}"; do
    IFS=: read -r VERSION PHP_CONFIG INI_DIR WITH_WEBKIT ENABLED <<< "$BUILD"

    if [ -n "${ONLY:-}" ] && [ "$ONLY" != "$VERSION" ]; then
        continue
    fi
    if [ "$ENABLED" -ne 1 ] && [ -z "${ONLY:-}" ]; then
        echo "=== Skipping PHP ${VERSION} (disabled) ==="
        continue
    fi
    if [ ! -x "$PHP_CONFIG" ]; then
        echo "ERROR: $PHP_CONFIG not found" >&2
        exit 1
    fi

    PHPCPP_DIR="${PHPCPP_BASE}/php${VERSION}"
    PHPCPP_STATIC=$(find "${PHPCPP_DIR}" -maxdepth 1 -name "libphpcpp.a.*" 2>/dev/null | head -1)
    if [ -z "$PHPCPP_STATIC" ]; then
        echo "ERROR: no static libphpcpp found in ${PHPCPP_DIR} (run PHP-CPP/build-dist.sh)" >&2
        exit 1
    fi

    SO_DEST=$("$PHP_CONFIG" --extension-dir)

    # Stop running interpreters of that version so the .so can be replaced
    pkill -f "/usr/bin/php${VERSION}" 2>/dev/null || true

    echo "=== Building for PHP ${VERSION} (libphpcpp: ${PHPCPP_STATIC}) ==="
    make clean
    make PHP_CONFIG="${PHP_CONFIG}" INI_DIR="${INI_DIR}/" WITH_WEBKIT="${WITH_WEBKIT}" \
         PHPCPP_STATIC="${PHPCPP_STATIC}" -j"${JOBS}"

    echo "=== Installing gtk4.so -> ${SO_DEST}, gtk4.ini -> ${INI_DIR} ==="
    install -m 644 gtk4.so "${SO_DEST}/"
    install -m 644 gtk4.ini "${INI_DIR}/"

    # Enable via the Debian/Ubuntu conf.d mechanism when available
    if command -v phpenmod >/dev/null 2>&1; then
        phpenmod -v "${VERSION}" gtk4 || true
    fi

    "/usr/bin/php${VERSION}" -m | grep -qx gtk4 && echo "PHP ${VERSION}: gtk4 loaded OK" \
        || echo "WARNING: PHP ${VERSION} does not list gtk4 (check ${INI_DIR}/gtk4.ini)"
done

echo "=== Done ==="
