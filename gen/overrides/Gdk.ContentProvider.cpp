// The GValue boundary drag and drop is built on: a payload is a GValue of a declared GType, and
// PHP names that type the way it already does for a list store's items - a class name - plus the
// four scalars a drag realistically carries (core/marshal, gtype_from_php_name).
#include "core/marshal.h"
