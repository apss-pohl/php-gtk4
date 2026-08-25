#
# php-gtk4 — PHP-CPP based extension binding GTK 4
#
# Params:
#   PHP_CONFIG=/usr/bin/php-config8.4   php-config of the PHP to build for (must match PHP-CPP's)
#   INI_DIR=/etc/php/8.4/mods-available
#   PHPCPP_STATIC=/path/libphpcpp.a.x.y.z  link a specific PHP-CPP by full path
#   WITH_WEBKIT=1                       enable webkitgtk-6.0 (not yet implemented)
#
NAME            = gtk4
PHP_CONFIG     ?= /usr/bin/php-config8.4
ifeq (,$(wildcard $(PHP_CONFIG)))
    PHP_CONFIG  = php-config
endif
INI_DIR        ?= /etc/php/8.4/mods-available/
EXTENSION_DIR   = $(shell $(PHP_CONFIG) --extension-dir)
PHP_VERSION     = $(shell $(PHP_CONFIG) --version | cut -d. -f1,2)
PHP_VERSION_ID  = $(shell $(PHP_CONFIG) --vernum)

# PHP 8.4+ only (see CLAUDE.md). Fail early instead of producing a .so that PHP refuses.
ifeq ($(shell test $(PHP_VERSION_ID) -ge 80400 && echo ok),)
    $(error php-gtk4 requires PHP >= 8.4, $(PHP_CONFIG) reports $(PHP_VERSION))
endif
BUILD_DIR       = build/php$(PHP_VERSION)

GTK_PKGS        = gtk4
GTKFLAGS        = $(shell pkg-config --cflags $(GTK_PKGS))
GTKLIBS         = $(shell pkg-config --libs $(GTK_PKGS))

GIT_HASH        = $(shell git rev-parse --short HEAD 2>/dev/null || echo unknown)
BUILD_DATE      = $(shell date -u +%Y-%m-%dT%H:%M:%SZ)

FEATURE_DEFS    =
ifeq ($(WITH_WEBKIT),1)
    GTK_PKGS   += webkitgtk-6.0
    FEATURE_DEFS += -DWITH_WEBKIT
endif

CXX             = g++
PHPFLAGS        = $(shell $(PHP_CONFIG) --includes)
CXXFLAGS        = -Wall -Wextra -std=c++20 -fpic -O2 -MMD -MP $(PHPFLAGS) $(GTKFLAGS) $(FEATURE_DEFS) -I.
LDFLAGS         = -shared
ifdef PHPCPP_STATIC
    PHPCPP_LIB  = $(PHPCPP_STATIC)
else
    PHPCPP_LIB  = -lphpcpp
endif
LIBS            = $(PHPCPP_LIB) $(GTKLIBS)

SOURCES         = main.cpp version.cpp $(wildcard src/core/*.cpp) $(wildcard src/Gtk/*.cpp) $(wildcard src/gen/*/*.cpp)
OBJECTS         = $(patsubst %.cpp,$(BUILD_DIR)/%.o,$(SOURCES))
EXTENSION       = $(NAME).so
INI             = $(NAME).ini

all: $(EXTENSION)

$(EXTENSION): $(OBJECTS)
	$(CXX) $(LDFLAGS) -o $@ $(OBJECTS) $(LIBS)

# Real source prerequisites (+ header deps via -MMD), unlike php-gtk3.
$(BUILD_DIR)/%.o: %.cpp
	@mkdir -p $(dir $@)
	$(CXX) $(CXXFLAGS) -c -o $@ $<

# version.o is always rebuilt so git hash / date stay current.
$(BUILD_DIR)/version.o: version.cpp FORCE
	@mkdir -p $(dir $@)
	$(CXX) $(CXXFLAGS) -DPHPGTK_GIT_HASH='"$(GIT_HASH)"' -DPHPGTK_BUILD_DATE='"$(BUILD_DATE)"' -c -o $@ $<

FORCE:

objects: $(OBJECTS)

install: $(EXTENSION)
	cp -f $(EXTENSION) $(EXTENSION_DIR)
	cp -f $(INI) $(INI_DIR)

test: $(EXTENSION)
	./tests/run.sh

compile_commands:
	bear -- $(MAKE) objects

clean:
	rm -rf build $(EXTENSION)

-include $(OBJECTS:.o=.d)

.PHONY: all objects install test compile_commands clean FORCE
