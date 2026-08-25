#!/usr/bin/env bash
# clang-tidy + clang-format over src/**, main.cpp, version.cpp.
#   ./lint.sh            check
#   ./lint.sh --fix      apply clang-tidy --fix and clang-format -i
#   ./lint.sh --no-tidy  format only (fast)   ./lint.sh --no-format  tidy only
#   CLANG_TIDY=clang-tidy-17 CLANG_FORMAT=clang-format-17 JOBS=8 ./lint.sh
set -euo pipefail

CLANG_TIDY=${CLANG_TIDY:-clang-tidy-17}
CLANG_FORMAT=${CLANG_FORMAT:-clang-format-17}
JOBS=${JOBS:-$(nproc)}
EXTRA_ARGS="-std=c++20"

FIX=0; NO_FORMAT=0; NO_TIDY=0; FAIL_FAST=0
for arg in "$@"; do
    case "$arg" in
        --fix)        FIX=1 ;;
        --no-format)  NO_FORMAT=1 ;;
        --no-tidy)    NO_TIDY=1 ;;
        --fail-fast)  FAIL_FAST=1 ;;
    esac
done

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PHP_CONFIG=${PHP_CONFIG:-php-config}

# Include flags: PHP headers + GTK4 (+ optional webkit). Package list must
# mirror the Makefile's GTK_PKGS.
INCLUDE_FLAGS=(--extra-arg=-I"$SCRIPT_DIR")
while IFS= read -r flag; do
    INCLUDE_FLAGS+=("--extra-arg=$flag")
done < <(
    {
        "$PHP_CONFIG" --includes 2>/dev/null || true
        pkg-config --cflags glib-2.0 gobject-2.0 gtk4 2>/dev/null
        pkg-config --cflags webkitgtk-6.0 2>/dev/null || true
    } | tr ' ' '\n' | grep -v '^$'
)

mapfile -t CPP_FILES < <(
    { find "$SCRIPT_DIR/src" -type f -name "*.cpp"
      find "$SCRIPT_DIR" -maxdepth 1 -type f \( -name "main.cpp" -o -name "version.cpp" \); } | sort
)
mapfile -t FILES < <(
    { find "$SCRIPT_DIR/src" -type f \( -name "*.cpp" -o -name "*.h" \)
      find "$SCRIPT_DIR" -maxdepth 1 -type f \( -name "main.cpp" -o -name "main.h" -o -name "version.cpp" \); } | sort
)

ERR_DIR=$(mktemp -d)
trap 'rm -rf "$ERR_DIR"' EXIT
[[ $FIX -eq 1 ]] && ACTION="fixing" || ACTION="checking"

if [[ $NO_TIDY -eq 1 ]]; then
    echo "=== clang-tidy skipped (--no-tidy) ==="
else
    echo "=== clang-tidy (${JOBS} jobs) ==="
    active=0; TIDY_PIDS=()
    for f in "${CPP_FILES[@]}"; do
        [[ $FAIL_FAST -eq 1 && -f "$ERR_DIR/stop" ]] && break
        (
            [[ $FAIL_FAST -eq 1 && -f "$ERR_DIR/stop" ]] && exit 0
            echo "  $ACTION: ${f#"$SCRIPT_DIR"/}"
            TIDY_ARGS=(--extra-arg="$EXTRA_ARGS" "${INCLUDE_FLAGS[@]}")
            [[ $FIX -eq 1 ]] && TIDY_ARGS+=(--fix --fix-errors)
            if ! "$CLANG_TIDY" "$f" "${TIDY_ARGS[@]}" -- 2>&1; then
                touch "$ERR_DIR/$(basename "$f").tidy"
                [[ $FAIL_FAST -eq 1 ]] && touch "$ERR_DIR/stop"
            fi
        ) &
        TIDY_PIDS+=($!); active=$((active + 1))
        if [[ $active -ge $JOBS ]]; then
            wait -n 2>/dev/null || wait
            active=$((active - 1))
            if [[ $FAIL_FAST -eq 1 && -f "$ERR_DIR/stop" ]]; then
                for pid in "${TIDY_PIDS[@]}"; do kill "$pid" 2>/dev/null || true; done
                break
            fi
        fi
    done
    wait
fi

if [[ $NO_FORMAT -eq 1 ]]; then
    echo "=== clang-format skipped (--no-format) ==="
else
    echo "=== clang-format (${JOBS} jobs) ==="
    active=0
    for f in "${FILES[@]}"; do
        (
            echo "  $ACTION: ${f#"$SCRIPT_DIR"/}"
            if [[ $FIX -eq 1 ]]; then
                "$CLANG_FORMAT" -i "$f"
            elif ! "$CLANG_FORMAT" --dry-run --Werror "$f" 2>&1; then
                touch "$ERR_DIR/$(basename "$f").fmt"
            fi
        ) &
        active=$((active + 1))
        if [[ $active -ge $JOBS ]]; then
            wait -n 2>/dev/null || wait
            active=$((active - 1))
        fi
    done
    wait
fi

TIDY_ERRORS=$(find "$ERR_DIR" -name "*.tidy" | wc -l)
FORMAT_ERRORS=$(find "$ERR_DIR" -name "*.fmt" | wc -l)
echo ""; echo "=== Summary ==="
if [[ $FIX -eq 1 ]]; then echo "  Auto-fix applied. Run without --fix to verify."; exit 0; fi
echo "  clang-tidy   errors: $TIDY_ERRORS"
echo "  clang-format errors: $FORMAT_ERRORS"
[[ $TIDY_ERRORS -gt 0 || $FORMAT_ERRORS -gt 0 ]] && exit 1
echo "All checks passed."
