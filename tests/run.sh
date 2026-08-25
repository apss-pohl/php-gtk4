#!/usr/bin/env bash
# Runs every tests/*.php against the freshly built ./gtk4.so under Xvfb.
# -n is mandatory: without it PHP also loads an installed gtk4 and double-loads.
set -u
cd "$(dirname "$0")/.."
PHP=${PHP:-php}
fail=0
for t in tests/*.php; do
  if xvfb-run -a "$PHP" -n -dextension=./gtk4.so "$t"; then
    echo "PASS $t"
  else
    echo "FAIL $t"; fail=1
  fi
done
exit $fail
