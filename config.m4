dnl php-gtk4 - PHP extension binding GTK 4 (phpize build)
dnl
dnl   phpize && ./configure [--with-php-config=...] && make && make install
dnl   --enable-gtk4-sanitize   AddressSanitizer + UBSan build (ci.sh --only=asan)
dnl   --enable-gtk4-coverage   gcov instrumentation (ci.sh --only=coverage)
dnl   --enable-gtk4-webkit     WebKitGTK 6 support (the WebKit* and JSC* classes; Gtk4\FEATURES says webkit=yes)

PHP_ARG_ENABLE([gtk4],
  [whether to enable the gtk4 extension],
  [AS_HELP_STRING([--enable-gtk4], [Enable the GTK 4 binding])],
  [yes])
PHP_ARG_ENABLE([gtk4-sanitize],
  [whether to build gtk4 with ASan/UBSan],
  [AS_HELP_STRING([--enable-gtk4-sanitize], [Build gtk4 with AddressSanitizer and UBSan])],
  [no], [no])
PHP_ARG_ENABLE([gtk4-coverage],
  [whether to build gtk4 with gcov coverage],
  [AS_HELP_STRING([--enable-gtk4-coverage], [Build gtk4 with gcov instrumentation])],
  [no], [no])
PHP_ARG_ENABLE([gtk4-webkit],
  [whether to enable WebKitGTK in gtk4],
  [AS_HELP_STRING([--enable-gtk4-webkit], [Enable WebKitGTK 6 support])],
  [no], [no])

if test "$PHP_GTK4" != "no"; then
  PHP_REQUIRE_CXX()

  AC_MSG_CHECKING([for PHP >= 8.4])
  gtk4_php_version=`$PHP_CONFIG --version`
  AS_VERSION_COMPARE([$gtk4_php_version], [8.4.0], [
    AC_MSG_ERROR([php-gtk4 requires PHP >= 8.4, $PHP_CONFIG reports $gtk4_php_version])
  ])
  AC_MSG_RESULT([$gtk4_php_version])

  dnl ZTS and NTS both build: per-request state is in module globals
  dnl (src/core/globals.h). GTK itself is single-threaded either way.

  dnl GTK floor is 4.14 (Ubuntu 24.04); CI also compiles against 4.22.
  PKG_CHECK_MODULES([GTK4], [gtk4 >= 4.14 cairo-gobject gobject-2.0 >= 2.76 glib-2.0 >= 2.76])
  PHP_EVAL_INCLINE([$GTK4_CFLAGS])
  PHP_EVAL_LIBLINE([$GTK4_LIBS], [GTK4_SHARED_LIBADD])

  GTK4_FEATURES="webkit=no"
  if test "$PHP_GTK4_WEBKIT" != "no"; then
    PKG_CHECK_MODULES([WEBKITGTK], [webkitgtk-6.0])
    PHP_EVAL_INCLINE([$WEBKITGTK_CFLAGS])
    PHP_EVAL_LIBLINE([$WEBKITGTK_LIBS], [GTK4_SHARED_LIBADD])
    AC_DEFINE([PHPGTK_WITH_WEBKIT], [1], [WebKitGTK support])
    GTK4_FEATURES="webkit=yes"
  fi
  AC_DEFINE_UNQUOTED([PHPGTK_BUILD_FEATURES], ["$GTK4_FEATURES"], [Compiled-in optional features])

  dnl ./VERSION is the single source of truth (docs/RELEASING.md); src/php_gtk4.h
  dnl mirrors it via `./ci.sh --only=version --fix`. Refuse to build a module that
  dnl would report a version nobody released.
  AC_MSG_CHECKING([that src/php_gtk4.h matches ./VERSION])
  gtk4_version=`tr -d ' \t\r\n' < "$srcdir/VERSION" 2>/dev/null`
  gtk4_hdr_version=`sed -n -E 's/^#define PHP_GTK4_VERSION "(.*)"$/\1/p' "$srcdir/src/php_gtk4.h"`
  if test -z "$gtk4_version"; then
    AC_MSG_ERROR([./VERSION is missing or empty])
  fi
  if test "$gtk4_version" != "$gtk4_hdr_version"; then
    AC_MSG_ERROR([version mismatch: ./VERSION is $gtk4_version, src/php_gtk4.h is $gtk4_hdr_version; run ./ci.sh --only=version --fix])
  fi
  AC_MSG_RESULT([$gtk4_version])

  dnl Build metadata baked in at configure time.
  GTK4_GIT_HASH=`git -C "$srcdir" rev-parse --short HEAD 2>/dev/null || echo unknown`
  GTK4_BUILD_DATE=`date -u +%Y-%m-%dT%H:%M:%SZ`
  AC_DEFINE_UNQUOTED([PHPGTK_BUILD_INFO], ["built $GTK4_BUILD_DATE, git $GTK4_GIT_HASH"], [Build info])

  dnl -Wno-unused-parameter: every ZEND_METHOD/PHP_MINIT macro has unused params
  GTK4_CXXFLAGS="-std=c++20 -Wall -Wextra -Wno-unused-parameter -fvisibility=hidden"
  if test "$PHP_GTK4_SANITIZE" != "no"; then
    GTK4_CXXFLAGS="$GTK4_CXXFLAGS -O1 -g -fno-omit-frame-pointer -fsanitize=address,undefined"
    GTK4_SHARED_LIBADD="-fsanitize=address,undefined $GTK4_SHARED_LIBADD"
  fi
  if test "$PHP_GTK4_COVERAGE" != "no"; then
    GTK4_CXXFLAGS="$GTK4_CXXFLAGS -O0 -g --coverage"
    GTK4_SHARED_LIBADD="--coverage $GTK4_SHARED_LIBADD"
  fi

  PHP_SUBST([GTK4_SHARED_LIBADD])

  dnl Every .cpp under src/ is compiled; nothing to maintain when a class file is added
  dnl (sorted for a reproducible link order). Every directory under src/ becomes a build dir
  dnl (an out-of-tree build cannot place the objects otherwise) - derived, like the sources,
  dnl so a new GIR namespace directory (src/Pango, src/Gsk) needs no edit here or in config.w32.
  dnl The one exception: the namespaces that need WebKitGTK (src/WebKit, src/JavaScriptCore,
  dnl src/Soup - libsoup is WebKitGTK's HTTP library and reaches PHP only through it -
  dnl CONDITIONAL_NAMESPACES in gen/gir/config.php) are left out without --enable-gtk4-webkit;
  dnl their registration and arginfo are under #ifdef PHPGTK_WITH_WEBKIT in the generated files.
  if test "$PHP_GTK4_WEBKIT" = "no"; then
    GTK4_SOURCES=`cd "$srcdir" && find src -name '*.cpp' | grep -v '^src/\(WebKit\|JavaScriptCore\|Soup\)/' | LC_ALL=C sort | tr '\n' ' '`
  else
    GTK4_SOURCES=`cd "$srcdir" && find src -name '*.cpp' | LC_ALL=C sort | tr '\n' ' '`
  fi
  PHP_NEW_EXTENSION([gtk4], [$GTK4_SOURCES], [$ext_shared], [], [$GTK4_CXXFLAGS], [cxx])
  for gtk4_dir in `cd "$srcdir" && find src -type d -not -name '.libs' | LC_ALL=C sort`; do
    PHP_ADD_BUILD_DIR([$ext_builddir/$gtk4_dir])
  done
  PHP_ADD_INCLUDE([$ext_srcdir])
  PHP_ADD_INCLUDE([$ext_srcdir/src])
fi
