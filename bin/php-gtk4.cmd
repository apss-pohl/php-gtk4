@echo off
rem Windows counterpart of bin/php-gtk4: run a script with the freshly built
rem php_gtk4.dll and GTK's DLLs on PATH.
rem
rem   bin\php-gtk4 script.php [args...]
rem   set GTK4_ROOT=C:\gtk-build\gtk\x64\release   (gvsbuild tree; default shown)
rem   set GTK4_DLL=x64\Release\php_gtk4.dll        (default: newest build under x64\)
rem   set PHP=C:\php\php.exe                        (default: php from PATH)
rem
rem There is no php-gtk3 to filter out on Windows, so this is only PATH + -dextension.
setlocal
set "ROOT=%~dp0.."
if "%PHP%"=="" set "PHP=php"
if "%GTK4_ROOT%"=="" set "GTK4_ROOT=C:\gtk-build\gtk\x64\release"
if "%GTK4_DLL%"=="" (
    if exist "%ROOT%\x64\Release\php_gtk4.dll" set "GTK4_DLL=%ROOT%\x64\Release\php_gtk4.dll"
    if exist "%ROOT%\x64\Release_TS\php_gtk4.dll" set "GTK4_DLL=%ROOT%\x64\Release_TS\php_gtk4.dll"
)
if "%GTK4_DLL%"=="" (
    echo php-gtk4: no php_gtk4.dll found under %ROOT%\x64 - build it first ^(docs\BUILD.md^) or set GTK4_DLL 1>&2
    exit /b 1
)
set "PATH=%GTK4_ROOT%\bin;%PATH%"
"%PHP%" -dextension="%GTK4_DLL%" %*
exit /b %ERRORLEVEL%
