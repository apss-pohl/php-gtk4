<?php
if (getenv('DISPLAY') === false && getenv('WAYLAND_DISPLAY') === false) {
    die("skip needs a display (run under xvfb-run)\n");
}
