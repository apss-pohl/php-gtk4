dnl php-gtk4 - PHP extension binding GTK 4 (phpize build)
dnl
dnl   phpize && ./configure [--with-php-config=...] && make && make install
dnl   --enable-gtk4-sanitize   AddressSanitizer + UBSan build (ci.sh --only=asan)
dnl   --enable-gtk4-coverage   gcov instrumentation (ci.sh --only=coverage)
dnl   --enable-gtk4-webkit     WebKitGTK 6 support (not implemented yet)

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

  dnl The runtime keeps request state in plain statics (exception handler,
  dnl registries, running-loop stack) - correct for NTS, wrong for ZTS. Refuse
  dnl a thread-safe PHP instead of producing a subtly broken module.
  if test "$PHP_THREAD_SAFETY" = "yes"; then
    AC_MSG_ERROR([php-gtk4 does not support thread-safe (ZTS) PHP builds; use an NTS PHP])
  fi

  dnl GTK floor is 4.14 (Ubuntu 24.04); CI also compiles against 4.22.
  PKG_CHECK_MODULES([GTK4], [gtk4 >= 4.14 gobject-2.0 >= 2.76 glib-2.0 >= 2.76])
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

  GTK4_SOURCES="src/gtk4.cpp src/core/object.cpp src/core/marshal.cpp src/core/gsignal.cpp src/core/error.cpp src/core/callback.cpp src/core/mainloop.cpp src/core/paramspec.cpp src/core/teardown.cpp src/core/boxed.cpp src/core/variant.cpp src/Gio/GSimpleAction.cpp src/Gdk/GdkRGBA.cpp src/Gdk/GdkRectangle.cpp src/Gtk/Gtk.cpp src/Gtk/GtkWidget.cpp src/Gtk/GtkButton.cpp src/Gtk/GtkLabel.cpp src/Gtk/GMainLoop.cpp src/Gtk/GtkApplication.cpp src/Gtk/GtkWindow.cpp"
  PHP_NEW_EXTENSION([gtk4], [$GTK4_SOURCES], [$ext_shared], [], [$GTK4_CXXFLAGS], [cxx])
  PHP_ADD_BUILD_DIR([$ext_builddir/src])
  PHP_ADD_BUILD_DIR([$ext_builddir/src/core])
  PHP_ADD_BUILD_DIR([$ext_builddir/src/Gtk])
  PHP_ADD_BUILD_DIR([$ext_builddir/src/Gdk])
  PHP_ADD_BUILD_DIR([$ext_builddir/src/Gio])
  PHP_ADD_INCLUDE([$ext_srcdir])
  PHP_ADD_INCLUDE([$ext_srcdir/src])
fi
