@echo off
rem Windows counterpart of tests/run.sh: the PHPUnit suite against the built
rem php_gtk4.dll on the real desktop (there is no Xvfb; GDK's win32 backend
rem needs an interactive session). Extra args go to phpunit:
rem   tests\run                       whole suite
rem   tests\run --filter SignalTest   one class
rem Same knobs as bin\php-gtk4.cmd: PHP, GTK4_ROOT, GTK4_DLL.
setlocal
cd /d "%~dp0.."
rem Resolve the module here, not only inside php-gtk4.cmd: ShutdownTest spawns a
rem second php with -dextension=%GTK4_DLL% and reads it from the environment.
if "%GTK4_DLL%"=="" (
    rem newest php_gtk4.dll under x64\ - the same rule as bin\php-gtk4.cmd
    for /f "delims=" %%f in ('powershell -NoProfile -Command "Get-ChildItem -Recurse -Filter php_gtk4.dll '%CD%\x64' -ErrorAction SilentlyContinue | Sort-Object LastWriteTime -Descending | Select-Object -First 1 -ExpandProperty FullName"') do set "GTK4_DLL=%%f"
)
if not exist "vendor\bin\phpunit" (
    echo tests\run: vendor\bin\phpunit missing - run: composer install 1>&2
    exit /b 1
)
rem Match tests/run.sh: software rendering, no accessibility bus, no xdebug.
if "%GSK_RENDERER%"=="" set "GSK_RENDERER=cairo"
if "%GTK_A11Y%"=="" set "GTK_A11Y=none"
set "XDEBUG_MODE=off"
call "%~dp0..\bin\php-gtk4.cmd" vendor\bin\phpunit %*
exit /b %ERRORLEVEL%
