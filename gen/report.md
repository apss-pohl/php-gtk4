# gen/gir.php report

Skipped members, by class. Fix with gen/overrides (a hand-written body), gen/skip.txt
(a reason) or by widening gen/allowlist.txt (types outside the closure).

## GAction

- `name_is_valid` — static function on an interface (PHP interfaces have no bodies)
- `parse_detailed_name` — static function on an interface (PHP interfaces have no bodies)
- `print_detailed_name` — static function on an interface (PHP interfaces have no bodies)

## GActionGroup

- `query_action` — out parameter `parameter_type` of type GLib.VariantType
- `vfunc query_action` — out parameter `parameter_type` of type GLib.VariantType

## GActionMap

- `add_action_entries` — parameter `entries` of type array (C array)
- `remove_action_entries` — parameter `entries` of type array (C array)

## GApplication

- `add_main_option_entries` — parameter `entries` of type array (C array)
- `add_option_group` — parameter `group` of type GLib.OptionGroup
- `get_dbus_connection` — return type Gio.DBusConnection (not in the closure)
- `open` — parameter `files` of type array (C array)
- `set_action_group` — deprecated (2.32)
- `vfunc add_platform_data` — parameter `builder` of type GLib.VariantBuilder
- `vfunc command_line` — parameter `command_line` of type Gio.ApplicationCommandLine
- `vfunc dbus_register` — GError out parameter
- `vfunc dbus_unregister` — parameter `connection` of type Gio.DBusConnection
- `vfunc handle_local_options` — parameter `options` of type GLib.VariantDict
- `vfunc local_command_line` — inout parameter arguments
- `vfunc open` — parameter `files` of type array (C array)

## GApplicationFlags

- `flags_none` — skip.txt: deprecated enumerator (GLIB_DEPRECATED_ENUMERATOR is not in the GIR); DEFAULT_FLAGS is the name

## GAsyncResult

- `get_user_data` — return type gpointer
- `is_tagged` — gpointer parameter
- `vfunc get_user_data` — return or argument type not convertible in a thunk
- `vfunc is_tagged` — gpointer parameter

## GCancellable

- `connect` — callback parameter (needs an override)
- `make_pollfd` — parameter `pollfd` of type GLib.PollFD
- `release_fd` — skip.txt: aborts the process (GLib-GIO:ERROR "priv->fd_refcount > 0") unless get_fd() was called first, and the refcount is not observable from here
- `source_new` — return type GLib.Source

## GDateTime

- `PHP subclasses` — constructor argument tz is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain GDateTime
- `new_from_timeval_local` — deprecated (2.62)
- `new_from_timeval_utc` — deprecated (2.62)
- `ref` — memory management belongs to the handle (clone / destructor)
- `to_timeval` — deprecated (2.62)
- `unref` — memory management belongs to the handle (clone / destructor)

## GIcon

- `deserialize` — static function on an interface (PHP interfaces have no bodies)
- `new_for_string` — static function on an interface (PHP interfaces have no bodies)
- `vfunc to_tokens` — caller-allocates out parameter `tokens` of type GLib.PtrArray

## GInputStream

- `read` — skip.txt: a caller-allocated byte buffer the C caller sizes and owns; read_bytes() answers the same bytes as a PHP string, which is the shape this binding maps GBytes to everywhere
- `read_all` — skip.txt: as read(); read_bytes() covers it and a PHP string carries its own length
- `read_all_async` — skip.txt: as read()
- `read_async` — skip.txt: as read(); read_bytes_async() is the async pair, and it answers with a string
- `vfunc close_async` — return or argument type not convertible in a thunk
- `vfunc close_finish` — GError out parameter
- `vfunc close_fn` — GError out parameter
- `vfunc read_async` — skip.txt: as read(); read_bytes_async() is the async pair, and it answers with a string
- `vfunc read_finish` — GError out parameter
- `vfunc read_fn` — GError out parameter
- `vfunc skip` — GError out parameter
- `vfunc skip_async` — return or argument type not convertible in a thunk
- `vfunc skip_finish` — GError out parameter

## GKeyFile

- `free` — memory management belongs to the handle (clone / destructor)
- `load_from_dirs` — parameter `search_dirs` of type array (C array)
- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## GListModel

- `get_item` — shadowed by get_object

## GListStore

- `find_with_equal_func` — callback parameter (needs an override)
- `find_with_equal_func_full` — callback parameter (needs an override)
- `insert_sorted` — callback parameter (needs an override)
- `sort` — callback parameter (needs an override)
- `splice` — parameter `additions` of type array (C array)

## GMemoryInputStream

- `new_from_data` — callback parameter (needs an override)
- `add_data` — callback parameter (needs an override)

## GMemoryOutputStream

- `new` — gpointer parameter
- `get_data` — return type gpointer
- `steal_data` — return type gpointer
- `property data` — property type gpointer not mappable
- `property destroy-function` — property type gpointer not mappable
- `property realloc-function` — property type gpointer not mappable

## GMenuItem

- `PHP subclasses` — constructor argument label is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain GMenuItem
- `get_attribute` — varargs
- `set_action_and_target` — varargs
- `set_attribute` — varargs

## GMenuModel

- `get_item_attribute` — varargs
- `iterate_item_attributes` — return type Gio.MenuAttributeIter (not in the closure)
- `iterate_item_links` — return type Gio.MenuLinkIter (not in the closure)
- `__construct` — skip.txt: abstract with NULL class-struct slots (get_n_items ...) and vfuncs on GHashTables that cannot be thunked; a PHP subtype would segfault on first use - build menus with GMenu, whose PHP subclasses do reach the vfunc_* slots (GMenuVfuncTest)
- `vfunc get_item_attributes` — out parameter `attributes` of type GLib.HashTable
- `vfunc get_item_links` — out parameter `links` of type GLib.HashTable
- `vfunc_iterate_item_attributes` — return type Gio.MenuAttributeIter (not in the closure)
- `vfunc_iterate_item_links` — return type Gio.MenuLinkIter (not in the closure)
- `smoke test` — no constructor or factory whose parameters can be sampled

## GNotification

- `PHP subclasses` — constructor argument title is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain GNotification
- `add_button_with_target` — shadowed by add_button_with_target_value
- `set_default_action_and_target` — shadowed by set_default_action_and_target_value
- `set_urgent` — deprecated (2.42)

## GOutputStream

- `printf` — varargs
- `vprintf` — parameter `args` of type Gio.va_list
- `write` — parameter `buffer` of type array (C array)
- `write_all` — parameter `buffer` of type array (C array)
- `write_all_async` — parameter `buffer` of type array (C array)
- `write_async` — parameter `buffer` of type array (C array)
- `writev` — parameter `vectors` of type array (C array)
- `writev_all` — parameter `vectors` of type array (C array)
- `writev_all_async` — parameter `vectors` of type array (C array)
- `writev_async` — parameter `vectors` of type array (C array)
- `vfunc close_async` — return or argument type not convertible in a thunk
- `vfunc close_finish` — GError out parameter
- `vfunc close_fn` — GError out parameter
- `vfunc flush` — GError out parameter
- `vfunc flush_async` — return or argument type not convertible in a thunk
- `vfunc flush_finish` — GError out parameter
- `vfunc splice` — GError out parameter
- `vfunc splice_async` — return or argument type not convertible in a thunk
- `vfunc splice_finish` — GError out parameter
- `vfunc write_async` — parameter `buffer` of type array (C array)
- `vfunc write_finish` — GError out parameter
- `vfunc write_fn` — GError out parameter
- `vfunc writev_async` — parameter `vectors` of type array (C array)
- `vfunc writev_finish` — GError out parameter
- `vfunc writev_fn` — GError out parameter

## GTask

- `PHP subclasses` — constructor argument source_object is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain GTask
- `report_error` — gpointer parameter
- `report_new_error` — varargs
- `attach_source` — callback parameter (needs an override)
- `get_context` — return type GLib.MainContext
- `get_source_tag` — return type gpointer
- `get_task_data` — return type gpointer
- `propagate_pointer` — return type gpointer
- `propagate_value` — caller-allocates out parameter `value` of type GObject.Value
- `return_new_error` — varargs
- `return_new_error_literal` — skip.txt: a GQuark domain is an interned string GLib hands out, and PHP has no way to make a meaningful one - core/gerror owns error domains (bound since aliases resolve to the guint32 behind GQuark)
- `return_pointer` — gpointer parameter
- `return_prefixed_error` — varargs
- `return_value` — parameter `result` of type GObject.Value
- `run_in_thread` — callback parameter (needs an override)
- `run_in_thread_sync` — callback parameter (needs an override)
- `set_source_tag` — gpointer parameter
- `set_task_data` — gpointer parameter

## GThemedIcon

- `PHP subclasses` — constructor argument iconname is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain GThemedIcon

## GTimeZone

- `new` — deprecated (2.68)
- `adjust_time` — inout parameter time
- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## GTlsCertificate

- `new_from_pkcs12` — parameter `data` of type array (C array)
- `get_ip_addresses` — list of Gio.InetAddress
- `verify` — parameter `identity` of type Gio.SocketConnectable
- `__construct` — skip.txt: abstract, and the properties belong to the TLS backend's subclass: g_object_new() on a PHP subtype answers every construct property with "invalid property id N ... in Php__..." (gio/gtlscertificate.c) and leaves an object with no certificate behind it - build one with new_from_pem() / new_from_file()
- `vfunc verify` — parameter `identity` of type Gio.SocketConnectable
- `property certificate` — property type GLib.ByteArray not mappable
- `property dns-names` — property type GLib.PtrArray not mappable
- `property ip-addresses` — property type GLib.PtrArray not mappable
- `property pkcs12-data` — property type GLib.ByteArray not mappable
- `property private-key` — property type GLib.ByteArray not mappable
- `smoke test` — smoke-skip.txt: every factory needs a real certificate: 'smoke' is neither a PEM nor a

## GdkClipboard

- `read_finish` — return Gio.InputStream plus out parameters
- `read_value_async` — parameter `type` of type Gdk.GType
- `read_value_finish` — return type GObject.Value
- `set` — shadowed by set_value
- `set_valist` — parameter `type` of type Gdk.GType
- `set_value` — parameter `value` of type GObject.Value
- `__construct` — skip.txt: GDK owns the clipboards (GdkDisplay::get_clipboard()/get_primary_clipboard()); `new` aborts the process in gdk_clipboard_set_property ("assertion failed: (priv->display != NULL)")
- `smoke test` — no constructor or factory whose parameters can be sampled

## GdkContentFormats

- `new_for_gtype` — parameter `type` of type Gdk.GType
- `match_gtype` — return type Gdk.GType
- `print` — parameter `string` of type GLib.String
- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## GdkContentProvider

- `new_typed` — varargs
- `new_union` — parameter `providers` of type array (C array)
- `vfunc get_value` — GError out parameter
- `vfunc ref_formats` — return or argument type not convertible in a thunk
- `vfunc ref_storable_formats` — return or argument type not convertible in a thunk
- `vfunc write_mime_type_async` — return or argument type not convertible in a thunk
- `vfunc write_mime_type_finish` — GError out parameter

## GdkDevice

- `get_device_tool` — return type Gdk.DeviceTool (not in the closure)
- `get_surface_at_position` — return Gdk.Surface plus out parameters
- `__construct` — skip.txt: abstract, and every device belongs to a GdkSeat that GDK built: g_object_new() on a PHP subtype has no backend device behind it, so the getters read uninitialised private state (EveryClassTest segfaulted on it). GdkSeat::get_pointer()/get_keyboard() and an event's get_device() are where one comes from
- `property tool` — property type Gdk.DeviceTool not mappable
- `smoke test` — no constructor or factory whose parameters can be sampled

## GdkDisplay

- `create_gl_context` — return type Gdk.GLContext (not in the closure)
- `get_app_launch_context` — return type Gdk.AppLaunchContext (not in the closure)
- `get_dmabuf_formats` — return type Gdk.DmabufFormats
- `get_setting` — parameter `value` of type GObject.Value
- `get_startup_notification_id` — deprecated (4.10)
- `map_keycode` — out parameter `keys` of type array
- `map_keyval` — out parameter `keys` of type array
- `notify_startup_complete` — deprecated (4.10)
- `put_event` — deprecated (4.10)
- `__construct` — skip.txt: GDK owns displays (GdkDisplay::get_default() / open()); a PHP subtype would have no backend behind it
- `property dmabuf-formats` — property type Gdk.DmabufFormats not mappable
- `smoke test` — no constructor or factory whose parameters can be sampled

## GdkDrag

- `__construct` — skip.txt: GDK creates a drag when a GtkDragSource starts one (get_drag()); a PHP subtype has no device and aborts the process in gdk_drag_set_property ("assertion failed: (priv->device != NULL)")
- `smoke test` — no constructor or factory whose parameters can be sampled

## GdkDrop

- `read_finish` — return Gio.InputStream plus out parameters
- `read_value_async` — parameter `type` of type Gdk.GType
- `read_value_finish` — return type GObject.Value
- `__construct` — skip.txt: the other end of the same drag, created by GDK; as Gdk.Drag
- `smoke test` — no constructor or factory whose parameters can be sampled

## GdkMemoryTexture

- `smoke test` — no constructor or factory whose parameters can be sampled

## GdkMonitor

- `__construct` — skip.txt: GDK owns the monitors (GdkDisplay::get_monitors()); a standalone one answers NULL from get_display(), which its declared type does not allow
- `smoke test` — no constructor or factory whose parameters can be sampled

## GdkPaintable

- `new_empty` — static function on an interface (PHP interfaces have no bodies)

## GdkPixbuf

- `new_from_data` — skip.txt: takes a caller-owned pixel buffer with a destroy callback; new_from_bytes() copies the same bytes
- `new_from_inline` — deprecated (2.32)
- `get_file_info_finish` — return GdkPixbuf.PixbufFormat plus out parameters
- `composite_color` — skip.txt: 17 parameters (the checkerboard-background variant of composite()); composite() and composite_color_simple() cover the same drawing, and a ZEND_METHOD that size trips clang-tidy's function-size gate
- `get_pixels` — skip.txt: a bare pointer into the pixel buffer (the shadowing get_pixels_with_length is skipped for the same reason); read_pixel_bytes() answers the bytes
- `get_pixels_with_length` — skip.txt: a bare pointer into the pixel buffer; read_pixel_bytes() answers the same bytes as a PHP string
- `read_pixels` — skip.txt: a bare pointer into the pixel buffer (GIR types it as a guint8 pointer, which would cross as an integer); read_pixel_bytes() answers the bytes
- `ref` — deprecated (2.0)
- `save` — varargs
- `save_to_buffer` — varargs
- `save_to_callback` — varargs
- `save_to_callbackv` — callback parameter (needs an override)
- `save_to_stream` — varargs
- `save_to_stream_async` — varargs
- `unref` — deprecated (2.0)
- `property pixels` — property type gpointer not mappable
- `smoke test` — smoke-skip.txt: the factories need real image data (PixbufTest covers it)

## GdkPixbufAnimation

- `get_iter` — parameter `start_time` of type GLib.TimeVal
- `ref` — deprecated (2.0)
- `unref` — deprecated (2.0)
- `__construct` — skip.txt: abstract with a private class struct (GDK_PIXBUF_ENABLE_BACKEND): a PHP subtype has NULL slots and every getter dereferences them (SIGSEGV); animations come from new_from_file()
- `vfuncs` — skip.txt: the class struct is behind GDK_PIXBUF_ENABLE_BACKEND (the loader-module API), so no PHP subclass could override a slot
- `smoke test` — smoke-skip.txt: same: new_from_file() needs an image file

## GdkPixbufAnimationIter

- `advance` — parameter `current_time` of type GLib.TimeVal
- `__construct` — skip.txt: as GdkPixbufAnimation; iterators come from an animation
- `vfuncs` — skip.txt: as GdkPixbufAnimation
- `smoke test` — no constructor or factory whose parameters can be sampled

## GdkPixbufFormat

- `copy` — memory management belongs to the handle (clone / destructor)
- `free` — memory management belongs to the handle (clone / destructor)

## GdkPixbufLoader

- `write` — skip.txt: a C byte array with its length; write_bytes() takes the same bytes as a PHP string

## GdkSeat

- `get_tools` — list of Gdk.DeviceTool
- `__construct` — skip.txt: abstract, as GdkDevice: a seat belongs to a GdkDisplay, and GdkDisplay::get_default_seat() is where one comes from
- `smoke test` — no constructor or factory whose parameters can be sampled

## GdkSurface

- `create_cairo_context` — return type Gdk.CairoContext (not in the closure)
- `create_gl_context` — return type Gdk.GLContext (not in the closure)
- `create_similar_surface` — deprecated (4.12)
- `create_vulkan_context` — deprecated (4.14)
- `get_frame_clock` — return type Gdk.FrameClock (not in the closure)
- `set_input_region` — parameter `region` of type cairo.Region
- `set_opaque_region` — parameter `region` of type cairo.Region
- `translate_coordinates` — inout parameter x
- `__construct` — skip.txt: GDK creates surfaces for a widget (GtkWindow) or through new_toplevel()/new_popup(); a PHP subtype has no display and aborts in gdk_surface_set_property
- `property frame-clock` — property type Gdk.FrameClock not mappable
- `smoke test` — no constructor or factory whose parameters can be sampled

## GdkTexture

- `new_from_resource` — skip.txt: g_error()s (aborts the process) on an invalid resource path - not a PHP-safe API
- `__construct` — skip.txt: abstract for GTK's own subclasses only: a texture needs internal state (color state, 4.16+) that only the factories set - no PHP subtypes, no `new`
- `smoke test` — smoke-skip.txt: factories need real image data (TextureTest covers it)

## GdkTextureDownloader

- `PHP subclasses` — constructor argument texture is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain GdkTextureDownloader
- `copy` — memory management belongs to the handle (clone / destructor)
- `download_into` — parameter `data` of type array (C array)
- `free` — memory management belongs to the handle (clone / destructor)

## GrapheneMatrix

- `decompose` — caller-allocates out parameter `rotate` of type Graphene.Quaternion
- `free` — memory management belongs to the handle (clone / destructor)
- `init_from_float` — parameter `v` of type array (C array)
- `project_rect` — caller-allocates out parameter `res` of type Graphene.Quad
- `rotate_euler` — parameter `e` of type Graphene.Euler
- `rotate_quaternion` — parameter `q` of type Graphene.Quaternion
- `to_float` — caller-allocates out parameter `v` of type array
- `transform_box` — parameter `b` of type Graphene.Box
- `transform_ray` — parameter `r` of type Graphene.Ray
- `transform_rect` — caller-allocates out parameter `res` of type Graphene.Quad
- `transform_sphere` — parameter `s` of type Graphene.Sphere

## GraphenePoint

- `distance` — return gfloat plus out parameters
- `free` — memory management belongs to the handle (clone / destructor)

## GraphenePoint3D

- `distance` — return gfloat plus out parameters
- `free` — memory management belongs to the handle (clone / destructor)

## GrapheneRect

- `field origin` — field type Graphene.Point is not a scalar
- `field size` — field type Graphene.Size is not a scalar
- `free` — memory management belongs to the handle (clone / destructor)
- `get_vertices` — caller-allocates out parameter `vertices` of type array
- `inset_r` — skip.txt: the plain inset() is bound as this const form (graphene's own inset() rewrites the rectangle it is given, which a value handle must not do)
- `normalize_r` — skip.txt: as inset_r: normalize() is the const form
- `offset_r` — skip.txt: as inset_r: offset() is the const form
- `round` — deprecated (1.10)
- `round_to_pixel` — deprecated (1.4)

## GrapheneSize

- `free` — memory management belongs to the handle (clone / destructor)

## GrapheneVec2

- `free` — memory management belongs to the handle (clone / destructor)
- `init_from_float` — parameter `src` of type array (C array)
- `to_float` — caller-allocates out parameter `dest` of type array

## GrapheneVec3

- `free` — memory management belongs to the handle (clone / destructor)
- `init_from_float` — parameter `src` of type array (C array)
- `to_float` — caller-allocates out parameter `dest` of type array

## GrapheneVec4

- `free` — memory management belongs to the handle (clone / destructor)
- `init_from_float` — parameter `src` of type array (C array)
- `to_float` — caller-allocates out parameter `dest` of type array

## GskBlendNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskBlurNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskBorderNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskCairoNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskClipNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskColorMatrixNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskColorNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskConicGradientNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskContainerNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskCrossFadeNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskDebugNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskFillNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskInsetShadowNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskLinearGradientNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskMaskNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskOpacityNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskOutsetShadowNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskPath

- `foreach` — callback parameter (needs an override)
- `print` — parameter `string` of type GLib.String
- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## GskPathBuilder

- `add_cairo_path` — parameter `path` of type cairo.Path
- `free_to_path` — skip.txt: unrefs the builder itself (GIR does not say so on the instance parameter), leaving the handle on freed memory - a SEGV on the next call; to_path() gives the same path and keeps the builder
- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## GskPathMeasure

- `PHP subclasses` — constructor argument path is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain GskPathMeasure
- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## GskPathPoint

- `copy` — memory management belongs to the handle (clone / destructor)
- `free` — memory management belongs to the handle (clone / destructor)
- `get_curvature` — return gfloat plus out parameters

## GskRadialGradientNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskRenderNode

- `ref` — memory management belongs to the handle (destructor)
- `unref` — memory management belongs to the handle (destructor)
- `smoke test` — no constructor or factory whose parameters can be sampled

## GskRenderer

- `render` — parameter `region` of type cairo.Region

## GskRepeatNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskRepeatingLinearGradientNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskRepeatingRadialGradientNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskRoundedClipNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskShadowNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskStroke

- `PHP subclasses` — constructor argument line_width is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain GskStroke
- `copy` — memory management belongs to the handle (clone / destructor)
- `free` — memory management belongs to the handle (clone / destructor)
- `equal` — gpointer parameter

## GskStrokeNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskSubsurfaceNode

- `new` — gpointer parameter
- `get_subsurface` — return type gpointer
- `smoke test` — no constructor or factory whose parameters can be sampled

## GskTextNode

- `new` — parameter `font` of type Pango.Font
- `get_font` — return type Pango.Font (not in the closure)
- `get_glyphs` — return array plus out parameters
- `smoke test` — no constructor or factory whose parameters can be sampled

## GskTextureNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskTextureScaleNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GskTransform

- `print` — parameter `string` of type GLib.String
- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## GskTransformNode

- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkAdjustment

- `smoke test` — smoke-skip.txt: the sample (every argument 1.0: upper == lower with a page size) is refused by newer GTK (gvsbuild on Windows); LayoutTest builds it with a real range

## GtkAlertDialog

- `new` — varargs

## GtkAlign

- `baseline` — duplicate value 4 (alias of baseline_fill)

## GtkApplication

- `inhibit` — skip.txt: crashes inside GTK when the window has no surface / the app is not registered
- `uninhibit` — skip.txt: pairs with inhibit

## GtkApplicationWindow

- `implements Gio.ActionGroup` — skip.txt: GtkWidget::activate_action(string, mixed): bool is incompatible with the interface's activate_action(): void; the window stays a GActionMap (add/lookup/remove_action) and the widget method reaches its actions ("win.x")
- `get_help_overlay` — return type Gtk.ShortcutsWindow (not in the closure)
- `set_help_overlay` — parameter `help_overlay` of type Gtk.ShortcutsWindow
- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkBitset

- `copy` — memory management belongs to the handle (clone / destructor)
- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## GtkBox

- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkBoxLayout

- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkBuilder

- `new_from_file` — skip.txt: as new_from_string; add_from_file() throws instead
- `new_from_resource` — skip.txt: as new_from_string; add_from_resource() throws instead
- `new_from_string` — skip.txt: g_error()s (Gtk-ERROR, aborts the process) on any document GTK cannot parse or instantiate, and a .ui document is data - `new GtkBuilder()` + add_from_string() reports the same failure as a Gtk4\GError
- `create_closure` — return type GObject.Closure
- `extend_with_template` — parameter `template_type` of type Gtk.GType
- `get_type_from_name` — return type Gtk.GType
- `value_from_string` — caller-allocates out parameter `value` of type GObject.Value
- `value_from_string_type` — parameter `type` of type Gtk.GType

## GtkBuilderScope

- `vfunc create_closure` — GError out parameter
- `vfunc get_type_from_function` — return or argument type not convertible in a thunk
- `vfunc get_type_from_name` — return or argument type not convertible in a thunk

## GtkCssProvider

- `load_from_data` — deprecated (4.12)

## GtkDragIcon

- `create_widget_for_value` — parameter `value` of type GObject.Value
- `__construct` — skip.txt: GTK creates the icon for a drag (get_for_drag()); a standalone one realizes without a GdkSurface and dies in gtk_drag_icon_realize
- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkDropDown

- `new` — parameter `expression` of type Gtk.Expression
- `get_expression` — return type Gtk.Expression (not in the closure)
- `set_expression` — parameter `expression` of type Gtk.Expression
- `property expression` — property type Gtk.Expression not mappable

## GtkDropTarget

- `get_drop` — deprecated (4.4)
- `property drop` — deprecated (4.4)
- `property value` — property type GObject.Value not mappable
- `smoke test` — smoke-skip.txt: the constructor takes a *type name* ("string", a registered class), not an arbitrary string; DragDropTest builds it properly

## GtkEditable

- `delegate_get_property` — static function on an interface (PHP interfaces have no bodies)
- `delegate_set_property` — static function on an interface (PHP interfaces have no bodies)
- `install_properties` — static function on an interface (PHP interfaces have no bodies)
- `insert_text` — inout parameter position
- `vfunc do_insert_text` — inout parameter position
- `vfunc insert_text` — inout parameter position

## GtkEntry

- `get_completion` — deprecated (4.10)
- `set_completion` — deprecated (4.10)
- `property completion` — deprecated (4.10)

## GtkEntryBuffer

- `vfunc get_text` — parameter n_bytes is a pointer to a scalar without direction

## GtkEventControllerKey

- `get_im_context` — return type Gtk.IMContext (not in the closure)
- `set_im_context` — parameter `im_context` of type Gtk.IMContext

## GtkFileDialog

- `property initial-file` — property type Gio.File not mappable
- `property initial-folder` — property type Gio.File not mappable

## GtkFilterListModel

- `property item-type` — property type Gtk.GType not mappable

## GtkFixedLayoutChild

- `__construct` — skip.txt: GtkFixedLayout creates its layout children, as Gtk.LayoutChild
- `property transform` — skip.txt: GTK 4.14's getter hands the GValue the address of its pointer (g_value_set_boxed(value, &self->transform)), so a read through the property system is a GLib CRITICAL; get_transform()/set_transform() work and are what the stub promises
- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkFontDialog

- `choose_face` — parameter `initial_value` of type Pango.FontFace
- `choose_face_finish` — return type Pango.FontFace (not in the closure)
- `choose_family` — parameter `initial_value` of type Pango.FontFamily
- `choose_family_finish` — return type Pango.FontFamily (not in the closure)
- `choose_font_and_features_finish` — out parameter `font_desc` of type Pango.FontDescription
- `get_language` — return type Pango.Language
- `set_language` — parameter `language` of type Pango.Language
- `property language` — property type Pango.Language not mappable

## GtkFontDialogButton

- `get_language` — return type Pango.Language
- `set_language` — parameter `language` of type Pango.Language
- `property language` — property type Pango.Language not mappable

## GtkFrame

- `vfunc compute_child_allocation` — parameter `allocation` of type Gtk.Allocation

## GtkGesture

- `set_sequence_state` — deprecated (4.10.)

## GtkGesturePan

- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkGridLayoutChild

- `__construct` — skip.txt: GtkGridLayout creates its layout children, as Gtk.LayoutChild
- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkIconPaintable

- `__construct` — skip.txt: GtkIconTheme creates them (lookup_icon()); a standalone one paints nothing and has neither file nor icon name
- `property file` — property type Gio.File not mappable

## GtkIconTheme

- `get_icon_sizes` — return type array
- `get_search_path` — return type array
- `set_search_path` — parameter `path` of type array (C array)

## GtkImage

- `new_from_pixbuf` — deprecated (4.12)
- `set_from_pixbuf` — deprecated (4.12)

## GtkLayoutChild

- `__construct` — skip.txt: a layout manager creates its layout children (GtkLayoutManager::get_layout_child()); GTK stores the manager and the child widget *unowned* in construct-only properties, so one built from PHP CRITICALs when they are missing and dangles - get_layout_manager() then wraps freed memory - as soon as PHP drops what it was handed
- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkListItem

- `__construct` — skip.txt: a list item belongs to the factory that created it (the setup/bind handlers get it); a standalone one has no item and no position
- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkMenuButton

- `get_direction` — skip.txt: gtk_menu_button_get_direction (GtkArrowType) clashes with GtkWidget::get_direction (GtkTextDirection) in PHP; the `direction` property still reads the arrow
- `set_direction` — skip.txt: same; `$button->direction = GtkArrowType::Left` sets the arrow

## GtkMultiSelection

- `property item-type` — property type Gtk.GType not mappable

## GtkNative

- `get_for_surface` — static function on an interface (PHP interfaces have no bodies)

## GtkNoSelection

- `property item-type` — property type Gtk.GType not mappable

## GtkNotebookPage

- `__construct` — skip.txt: GtkNotebook creates its pages (get_page()); a page without a notebook has no child
- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkOverlayLayoutChild

- `__construct` — skip.txt: GtkOverlayLayout creates its layout children, as Gtk.LayoutChild
- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkPaned

- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkPaperSize

- `PHP subclasses` — constructor argument name is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain GtkPaperSize
- `copy` — memory management belongs to the handle (clone / destructor)
- `free` — memory management belongs to the handle (clone / destructor)

## GtkPicture

- `new_for_pixbuf` — deprecated (4.12)
- `get_keep_aspect_ratio` — deprecated (4.8)
- `set_keep_aspect_ratio` — deprecated (4.8)
- `set_pixbuf` — deprecated (4.12)
- `property file` — property type Gio.File not mappable
- `property keep-aspect-ratio` — deprecated (4.8)

## GtkPrintContext

- `__construct` — skip.txt: GTK creates the context inside a print operation (draw-page hands it over); one from g_object_new() has no page setup or cairo context and its getters dereference NULL (SIGSEGV)
- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkPrintOperation

- `vfunc preview` — parameter `preview` of type Gtk.PrintOperationPreview

## GtkPrintSetup

- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## GtkRange

- `vfunc get_range_border` — parameter `border` of type Gtk.Border

## GtkRequisition

- `copy` — memory management belongs to the handle (clone / destructor)
- `free` — memory management belongs to the handle (clone / destructor)

## GtkScale

- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkScrollInfo

- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## GtkScrollable

- `get_border` — caller-allocates out parameter `border` of type Gtk.Border
- `vfunc get_border` — caller-allocates out parameter `border` of type Gtk.Border

## GtkSelectionModel

- `vfunc get_selection_in_range` — return or argument type not convertible in a thunk

## GtkSeparator

- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkSingleSelection

- `property item-type` — property type Gtk.GType not mappable

## GtkSizeGroup

- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkSnapshot

- `append_conic_gradient` — parameter `stops` of type array (C array)
- `append_linear_gradient` — parameter `stops` of type array (C array)
- `append_radial_gradient` — parameter `stops` of type array (C array)
- `append_repeating_linear_gradient` — parameter `stops` of type array (C array)
- `append_repeating_radial_gradient` — parameter `stops` of type array (C array)
- `free_to_node` — skip.txt: frees the GtkSnapshot itself like free_to_paintable; to_node() answers with the same node and leaves the object alive
- `free_to_paintable` — skip.txt: frees the GtkSnapshot itself, so the handle is left on freed memory and its next qdata/toggle-ref touch is a SEGV (ASan caught it); to_paintable() answers with the same paintable and leaves the object alive
- `push_debug` — varargs
- `push_gl_shader` — parameter `shader` of type Gsk.GLShader
- `push_shadow` — parameter `shadow` of type array (C array)
- `render_background` — deprecated (4.10)
- `render_focus` — deprecated (4.10)
- `render_frame` — deprecated (4.10)
- `render_insertion_cursor` — deprecated (4.10)
- `render_layout` — deprecated (4.10)

## GtkSortListModel

- `property item-type` — property type Gtk.GType not mappable

## GtkStackPage

- `__construct` — skip.txt: GtkStack creates its pages (add_child()/add_titled(), get_page()); `new` g_error()s "is missing a child widget"
- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkStringList

- `property item-type` — property type Gtk.GType not mappable

## GtkText

- `finish_delegate` — skip.txt: pairs with init_delegate
- `init_delegate` — skip.txt: GtkEditable plumbing for a widget that *delegates* to a GtkText from its own init; calling it on a GtkText is "invalid (NULL) pointer instance" from GLib

## GtkTextBuffer

- `create_tag` — varargs
- `insert_with_tags` — varargs
- `insert_with_tags_by_name` — varargs

## GtkTextChildAnchor

- `get_deleted` — skip.txt: GTK warns ("hasn't been in a buffer yet") and answers TRUE for an anchor that was never inserted; the state it checks (the anchor's line segment) is private, so the binding cannot refuse the call before GTK complains
- `get_widgets` — skip.txt: same: it needs the anchor to be in a buffer, and nothing here can ask whether it is

## GtkTextIter

- `backward_find_char` — callback parameter (needs an override)
- `copy` — memory management belongs to the handle (clone / destructor)
- `forward_find_char` — callback parameter (needs an override)
- `free` — memory management belongs to the handle (clone / destructor)
- `get_language` — return type Pango.Language

## GtkToggleButton

- `toggled` — deprecated (4.10)
- `vfunc toggled` — deprecated (4.10)

## GtkTreeListModel

- `property item-type` — property type Gtk.GType not mappable
- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkTreeListRow

- `__construct` — skip.txt: GtkTreeListModel creates its rows (get_row()/get_child_row()); a row without a model has nothing to expand
- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkWidget

- `add_tick_callback` — callback parameter (needs an override)
- `dispose_template` — parameter `widget_type` of type Gtk.GType
- `get_allocated_baseline` — deprecated (4.12)
- `get_allocated_height` — deprecated (4.12)
- `get_allocated_width` — deprecated (4.12)
- `get_allocation` — deprecated (4.12)
- `get_ancestor` — parameter `widget_type` of type Gtk.GType
- `get_font_options` — return type cairo.FontOptions
- `get_frame_clock` — return type Gdk.FrameClock (not in the closure)
- `get_settings` — return type Gtk.Settings (not in the closure)
- `get_style_context` — deprecated (4.10)
- `get_template_child` — parameter `widget_type` of type Gtk.GType
- `hide` — deprecated (4.10)
- `set_font_options` — parameter `options` of type cairo.FontOptions
- `show` — deprecated (4.10)
- `size_allocate` — parameter `allocation` of type Gtk.Allocation
- `translate_coordinates` — deprecated (4.12)
- `vfunc compute_expand` — parameter vexpand_p is a pointer to a scalar without direction
- `vfunc css_changed` — parameter `change` of type Gtk.CssStyleChange
- `vfunc hide` — deprecated (4.10)
- `vfunc query_tooltip` — parameter `tooltip` of type Gtk.Tooltip
- `vfunc show` — deprecated (4.10)

## GtkWindow

- `get_group` — return type Gtk.WindowGroup (not in the closure)
- `present_with_time` — deprecated (4.14)

## JSCContext

- `check_syntax` — return JavaScriptCore.CheckSyntaxResult plus out parameters
- `evaluate_in_object` — gpointer parameter
- `push_exception_handler` — callback parameter (needs an override)
- `register_class` — callback parameter (needs an override)
- `throw_printf` — varargs
- `throw_with_name_printf` — varargs

## JSCException

- `PHP subclasses` — constructor argument context is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain JSCException
- `new_printf` — varargs
- `new_vprintf` — parameter `args` of type JavaScriptCore.va_list
- `new_with_name_printf` — varargs
- `new_with_name_vprintf` — parameter `args` of type JavaScriptCore.va_list
- `smoke test` — no constructor or factory whose parameters can be sampled

## JSCValue

- `new_array` — varargs
- `new_array_buffer` — gpointer parameter
- `new_array_from_garray` — parameter `array` of type GLib.PtrArray (C array)
- `new_function` — shadowed by new_functionv
- `new_function_variadic` — callback parameter (needs an override)
- `new_functionv` — callback parameter (needs an override)
- `new_object` — gpointer parameter
- `new_typed_array` — skip.txt: a length JavaScriptCore cannot allocate is a RELEASE_ASSERT (aborts the process), and the array's bytes are unreachable from PHP anyway (typed_array_get_data() is a gpointer)
- `new_typed_array_with_buffer` — skip.txt: as new_typed_array: an offset/length outside the buffer asserts inside JavaScriptCore
- `object_define_property_accessor` — callback parameter (needs an override)
- `typed_array_get_data` — return gpointer plus out parameters
- `__construct` — skip.txt: built by its factories (new_number(), new_string(), ...) or by a JSCContext; g_object_new() without a context asserts
- `smoke test` — no constructor or factory whose parameters can be sampled

## PangoAttrList

- `change` — parameter `attr` of type Pango.Attribute
- `copy` — memory management belongs to the handle (clone / destructor)
- `filter` — callback parameter (needs an override)
- `get_attributes` — list of Pango.Attribute
- `get_iterator` — return type Pango.AttrIterator
- `insert` — parameter `attr` of type Pango.Attribute
- `insert_before` — parameter `attr` of type Pango.Attribute
- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## PangoContext

- `get_language` — return type Pango.Language
- `get_matrix` — return type Pango.Matrix
- `get_metrics` — parameter `language` of type Pango.Language
- `list_families` — out parameter `families` of type array
- `load_font` — return type Pango.Font (not in the closure)
- `load_fontset` — parameter `language` of type Pango.Language
- `set_language` — parameter `language` of type Pango.Language
- `set_matrix` — parameter `matrix` of type Pango.Matrix

## PangoFontDescription

- `copy` — memory management belongs to the handle (clone / destructor)
- `free` — memory management belongs to the handle (clone / destructor)

## PangoFontMap

- `get_family` — return type Pango.FontFamily (not in the closure)
- `list_families` — out parameter `families` of type array
- `load_font` — return type Pango.Font (not in the closure)
- `load_fontset` — parameter `language` of type Pango.Language
- `reload_font` — parameter `font` of type Pango.Font
- `__construct` — skip.txt: abstract, and the concrete map belongs to the backend (PangoCairoFontMap here): a PHP subtype has no font map behind it and the getters read uninitialised state (EveryClassTest segfaulted on it). PangoContext::get_font_map() and GtkWidget::get_pango_context() are where one comes from
- `vfunc get_face` — parameter `font` of type Pango.Font
- `vfunc_get_family` — return type Pango.FontFamily (not in the closure)
- `vfunc list_families` — out parameter `families` of type array
- `vfunc_load_font` — return type Pango.Font (not in the closure)
- `vfunc load_fontset` — parameter `language` of type Pango.Language
- `property item-type` — property type Pango.GType not mappable
- `smoke test` — no constructor or factory whose parameters can be sampled

## PangoLayout

- `PHP subclasses` — constructor argument context is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain PangoLayout
- `get_caret_pos` — caller-allocates out parameter `strong_pos` of type Pango.Rectangle
- `get_cursor_pos` — caller-allocates out parameter `strong_pos` of type Pango.Rectangle
- `get_extents` — caller-allocates out parameter `ink_rect` of type Pango.Rectangle
- `get_iter` — return type Pango.LayoutIter
- `get_line` — return type Pango.LayoutLine
- `get_line_readonly` — return type Pango.LayoutLine
- `get_lines` — list of Pango.LayoutLine
- `get_lines_readonly` — list of Pango.LayoutLine
- `get_log_attrs` — out parameter `attrs` of type array
- `get_log_attrs_readonly` — return array plus out parameters
- `get_pixel_extents` — caller-allocates out parameter `ink_rect` of type Pango.Rectangle
- `index_to_pos` — caller-allocates out parameter `pos` of type Pango.Rectangle
- `smoke test` — no constructor or factory whose parameters can be sampled

## PangoTabArray

- `PHP subclasses` — constructor argument initial_size is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain PangoTabArray
- `new_with_positions` — varargs
- `copy` — memory management belongs to the handle (clone / destructor)
- `free` — memory management belongs to the handle (clone / destructor)
- `get_tabs` — out parameter `locations` of type array

## SoupCookie

- `PHP subclasses` — constructor argument name is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain SoupCookie
- `applies_to_uri` — parameter `uri` of type GLib.Uri
- `copy` — memory management belongs to the handle (clone / destructor)
- `free` — memory management belongs to the handle (clone / destructor)
- `parse` — parameter `origin` of type GLib.Uri

## SoupMessageHeaders

- `PHP subclasses` — constructor argument type is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain SoupMessageHeaders
- `free_ranges` — parameter `ranges` of type Soup.Range
- `get_content_disposition` — out parameter `params` of type GLib.HashTable
- `get_content_type` — out parameter `params` of type GLib.HashTable
- `get_ranges` — out parameter `ranges` of type array
- `ref` — memory management belongs to the handle (clone / destructor)
- `set_content_disposition` — parameter `params` of type GLib.HashTable
- `set_content_type` — parameter `params` of type GLib.HashTable
- `set_ranges` — parameter `ranges` of type Soup.Range
- `unref` — memory management belongs to the handle (clone / destructor)

## WebKitApplicationInfo

- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## WebKitAuthenticationRequest

- `__construct` — skip.txt: only WebKitWebView::authenticate hands one out
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitAutomationSession

- `__construct` — skip.txt: only WebKitWebContext::automation-started hands one out
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitBackForwardList

- `__construct` — skip.txt: WebKitWebView::get_back_forward_list()
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitBackForwardListItem

- `__construct` — skip.txt: WebKitBackForwardList::get_current_item() and friends
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitClipboardPermissionRequest

- `__construct` — skip.txt: only WebKitWebView::permission-request hands one out
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitColorChooserRequest

- `__construct` — skip.txt: only WebKitWebView::run-color-chooser hands one out
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitContextMenu

- `new_with_items` — parameter `items` of type GLib.List

## WebKitContextMenuItem

- `__construct` — skip.txt: built by its factories (new_from_gaction(), new_separator(), ...); g_object_new() gives an item without an action

## WebKitCookieManager

- `replace_cookies` — parameter `cookies` of type GLib.List
- `__construct` — skip.txt: WebKitNetworkSession::get_cookie_manager()
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitCredential

- `PHP subclasses` — constructor argument username is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain WebKitCredential
- `copy` — memory management belongs to the handle (clone / destructor)
- `free` — memory management belongs to the handle (clone / destructor)

## WebKitDeviceInfoPermissionRequest

- `__construct` — skip.txt: only WebKitWebView::permission-request hands one out
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitDownload

- `__construct` — skip.txt: WebKitWebView::download_uri() / the ::download-started signal
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitEditorState

- `__construct` — skip.txt: WebKitWebView::get_editor_state()
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitFaviconDatabase

- `__construct` — skip.txt: WebKitWebView::get_favicon_database() (a network session owns it)
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitFeature

- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## WebKitFeatureList

- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## WebKitFileChooserRequest

- `__construct` — skip.txt: only WebKitWebView::run-file-chooser hands one out
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitFindController

- `__construct` — skip.txt: WebKitWebView::get_find_controller()
- `property options` — skip.txt: WebKit's get_property writes a flags GValue with g_value_set_uint() (a CRITICAL and no value); get_options() reads it fine
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitFormSubmissionRequest

- `list_text_fields` — out parameter `field_names` of type GLib.PtrArray
- `__construct` — skip.txt: only WebKitWebView::submit-form hands one out
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitGeolocationManager

- `__construct` — skip.txt: WebKitWebContext::get_geolocation_manager()
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitGeolocationPermissionRequest

- `__construct` — skip.txt: only WebKitWebView::permission-request hands one out
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitGeolocationPosition

- `PHP subclasses` — constructor argument latitude is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain WebKitGeolocationPosition
- `copy` — memory management belongs to the handle (clone / destructor)
- `free` — memory management belongs to the handle (clone / destructor)

## WebKitHitTestResult

- `__construct` — skip.txt: only WebKitWebView::mouse-target-changed and ::context-menu hand one out
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitITPFirstParty

- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## WebKitITPThirdParty

- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## WebKitInputMethodContext

- `get_preedit` — out parameter `underlines` of type GLib.List
- `vfunc get_preedit` — out parameter `underlines` of type GLib.List

## WebKitInputMethodUnderline

- `PHP subclasses` — constructor argument start_offset is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain WebKitInputMethodUnderline
- `copy` — memory management belongs to the handle (clone / destructor)
- `free` — memory management belongs to the handle (clone / destructor)

## WebKitMediaKeySystemPermissionRequest

- `__construct` — skip.txt: only WebKitWebView::permission-request hands one out
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitMemoryPressureSettings

- `copy` — memory management belongs to the handle (clone / destructor)
- `free` — memory management belongs to the handle (clone / destructor)

## WebKitNavigationAction

- `copy` — memory management belongs to the handle (clone / destructor)
- `free` — memory management belongs to the handle (clone / destructor)

## WebKitNavigationPolicyDecision

- `__construct` — skip.txt: only WebKitWebView::decide-policy hands one out
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitNetworkProxySettings

- `copy` — memory management belongs to the handle (clone / destructor)
- `free` — memory management belongs to the handle (clone / destructor)

## WebKitNotification

- `__construct` — skip.txt: only WebKitWebView::show-notification hands one out
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitNotificationPermissionRequest

- `__construct` — skip.txt: only WebKitWebView::permission-request hands one out
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitOptionMenu

- `__construct` — skip.txt: only WebKitWebView::show-option-menu hands one out
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitOptionMenuItem

- `copy` — memory management belongs to the handle (clone / destructor)
- `free` — memory management belongs to the handle (clone / destructor)

## WebKitPermissionStateQuery

- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## WebKitPointerLockPermissionRequest

- `__construct` — skip.txt: only WebKitWebView::permission-request hands one out
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitPolicyDecision

- `__construct` — skip.txt: abstract with no slots to override; only WebKitWebView::decide-policy hands out its subclasses
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitPrintOperation

- `property web-view` — skip.txt: WebKit's get_property hands the GValue the view without a reference of its own (g_value_take_object on a pointer it does not own), so every read of the property dropped one reference of the web view and the second one disposed it; get_web_view() borrows correctly
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitResponsePolicyDecision

- `__construct` — skip.txt: only WebKitWebView::decide-policy hands one out
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitScriptDialog

- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## WebKitScriptMessageReply

- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## WebKitSecurityManager

- `__construct` — skip.txt: WebKitWebContext::get_security_manager()
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitSecurityOrigin

- `PHP subclasses` — constructor argument protocol is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain WebKitSecurityOrigin
- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## WebKitSettings

- `new_with_settings` — varargs
- `get_enable_dns_prefetching` — deprecated (2.48.)
- `get_enable_hyperlink_auditing` — deprecated (2.50.)
- `get_enable_offline_web_application_cache` — deprecated (2.44)
- `get_load_icons_ignoring_image_load_setting` — deprecated (2.42)
- `set_enable_dns_prefetching` — deprecated (2.48.)
- `set_enable_hyperlink_auditing` — deprecated (2.50.)
- `set_enable_offline_web_application_cache` — deprecated (2.44)
- `set_load_icons_ignoring_image_load_setting` — deprecated (2.42)
- `property enable-dns-prefetching` — deprecated (2.48)
- `property enable-hyperlink-auditing` — deprecated (2.50)
- `property enable-offline-web-application-cache` — deprecated (2.44)
- `property load-icons-ignoring-image-load-setting` — deprecated (2.42)

## WebKitURIResponse

- `__construct` — skip.txt: WebKitWebResource::get_response() and the policy decisions carry one
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitURISchemeRequest

- `__construct` — skip.txt: only the handler of WebKitWebContext::register_uri_scheme() is handed one; a g_object_new() request has no WebKit request behind it and its getters dereference null (EveryClassTest segfaulted on it)
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitURISchemeResponse

- `PHP subclasses` — constructor argument input_stream is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain WebKitURISchemeResponse
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitUserContentFilter

- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## WebKitUserContentFilterStore

- `PHP subclasses` — constructor argument storage_path is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain WebKitUserContentFilterStore

## WebKitUserMediaPermissionRequest

- `__construct` — skip.txt: only WebKitWebView::permission-request hands one out
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitUserMessage

- `new_with_fd_list` — parameter `fd_list` of type Gio.UnixFDList
- `get_fd_list` — return type Gio.UnixFDList (not in the closure)
- `property fd-list` — property type Gio.UnixFDList not mappable

## WebKitUserScript

- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## WebKitUserStyleSheet

- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## WebKitWebContext

- `initialize_notification_permissions` — parameter `allowed_origins` of type GLib.List

## WebKitWebInspector

- `__construct` — skip.txt: WebKitWebView::get_inspector()
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitWebResource

- `__construct` — skip.txt: WebKitWebView::get_main_resource() / ::resource-load-started
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitWebViewBase

- `__construct` — skip.txt: the widget base WebKit builds only as part of a WebKitWebView; a bare one has no page behind it
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitWebViewSessionState

- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## WebKitWebsiteData

- `ref` — memory management belongs to the handle (clone / destructor)
- `unref` — memory management belongs to the handle (clone / destructor)

## WebKitWebsiteDataAccessPermissionRequest

- `__construct` — skip.txt: only WebKitWebView::permission-request hands one out
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitWebsiteDataManager

- `remove` — parameter `website_data` of type GLib.List
- `__construct` — skip.txt: WebKitNetworkSession::get_website_data_manager() (the session sets its directories)
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitWebsitePolicies

- `new_with_policies` — varargs

## WebKitWindowProperties

- `__construct` — skip.txt: WebKitWebView::get_window_properties()
- `smoke test` — no constructor or factory whose parameters can be sampled

## WebKitXRPermissionRequest

- `__construct` — skip.txt: only WebKitWebView::permission-request hands one out
- `smoke test` — no constructor or factory whose parameters can be sampled

## Overrides in effect

- `Gdk.Clipboard`: read_async, set_value
- `Gdk.ContentFormats`: __construct, contain_gtype, get_gtypes, get_mime_types
- `Gdk.ContentProvider`: get_value, new_for_value
- `Gdk.Paintable`: compute_concrete_size
- `Gdk.Texture`: download
- `Gdk.TextureDownloader`: download_bytes
- `GdkPixbuf.Pixbuf`: get_file_info, get_options, save_to_bufferv, savev
- `Gio.Action`: activate
- `Gio.ActionGroup`: activate_action, change_action_state, get_action_enabled, get_action_parameter_type, get_action_state, get_action_state_hint, get_action_state_type, has_action, list_actions
- `Gio.Application`: get_dbus_object_path, get_is_remote, run
- `Gio.ListStore`: __construct
- `Gio.MenuModel`: get_item_attribute_value, get_item_link
- `Gio.SimpleAction`: set_state
- `Gio.Task`: propagate_boolean, propagate_int, return_boolean, return_error, return_int
- `Graphene.Rect`: inset, normalize, offset
- `Gsk.BorderNode`: __construct, get_widths
- `Gsk.ConicGradientNode`: __construct, get_color_stops
- `Gsk.ContainerNode`: __construct
- `Gsk.LinearGradientNode`: __construct, get_color_stops
- `Gsk.RadialGradientNode`: __construct, get_color_stops
- `Gsk.RenderNode`: deserialize
- `Gsk.RepeatingLinearGradientNode`: __construct
- `Gsk.RepeatingRadialGradientNode`: __construct
- `Gsk.ShadowNode`: __construct, get_shadow
- `Gsk.Stroke`: get_dash, set_dash
- `Gsk.Transform`: parse
- `Gtk.Box`: get_children
- `Gtk.Builder`: __construct, add_from_string, add_objects_from_string, set_current_object, set_handlers
- `Gtk.CustomFilter`: __construct, set_filter_func
- `Gtk.CustomSorter`: __construct, set_sort_func
- `Gtk.DrawingArea`: set_draw_func
- `Gtk.DropTarget`: __construct, get_gtypes, get_value, set_gtypes
- `Gtk.DropTargetAsync`: get_formats
- `Gtk.Entry`: grab_focus_without_selecting
- `Gtk.EntryBuffer`: __construct
- `Gtk.EventControllerKey`: get_group
- `Gtk.FileFilter`: new_from_gvariant
- `Gtk.FlowBox`: bind_model, set_filter_func, set_sort_func
- `Gtk.Gesture`: get_bounding_box_center
- `Gtk.IconTheme`: set_theme_name
- `Gtk.LayoutManager`: get_layout_child, get_request_mode
- `Gtk.ListBox`: bind_model, set_filter_func, set_header_func, set_sort_func
- `Gtk.MenuButton`: set_create_popup_func
- `Gtk.Popover`: get_pointing_to, popup
- `Gtk.PrintOperation`: get_error, run
- `Gtk.PrintSettings`: get_page_ranges, set_page_ranges
- `Gtk.Scale`: set_format_value_func
- `Gtk.SelectionModel`: selection_changed
- `Gtk.Snapshot`: append_border
- `Gtk.Text`: grab_focus_without_selecting
- `Gtk.TextBuffer`: insert, insert_at_cursor, insert_interactive, insert_interactive_at_cursor, insert_markup, set_text
- `Gtk.TextIter`: set_line_index, set_line_offset
- `Gtk.TextView`: get_extra_menu
- `Gtk.TreeListModel`: __construct
- `Gtk.Widget`: activate_action, allocate, insert_action_group
- `JavaScriptCore.Value`: constructor_call, function_call, object_invoke_method
- `WebKit.WebContext`: register_uri_scheme
- `WebKit.WebResource`: get_data_finish

## Emitted files

- `Gtk/GtkAboutDialog.cpp`
- `Gtk/GtkActionBar.cpp`
- `Gtk/GtkAdjustment.cpp`
- `Gtk/GtkAlertDialog.cpp`
- `Gtk/GtkApplication.cpp`
- `Gtk/GtkApplicationWindow.cpp`
- `Gtk/GtkAspectFrame.cpp`
- `Gtk/GtkBinLayout.cpp`
- `Gtk/GtkBitset.cpp`
- `Gtk/GtkBox.cpp`
- `Gtk/GtkBoxLayout.cpp`
- `Gtk/GtkBuilder.cpp`
- `Gtk/GtkBuilderScope.cpp`
- `Gtk/GtkButton.cpp`
- `Gtk/GtkCalendar.cpp`
- `Gtk/GtkCenterBox.cpp`
- `Gtk/GtkCenterLayout.cpp`
- `Gtk/GtkCheckButton.cpp`
- `Gtk/GtkColorDialog.cpp`
- `Gtk/GtkColorDialogButton.cpp`
- `Gtk/GtkColumnView.cpp`
- `Gtk/GtkColumnViewColumn.cpp`
- `Gtk/GtkCssProvider.cpp`
- `Gtk/GtkCustomFilter.cpp`
- `Gtk/GtkCustomSorter.cpp`
- `Gtk/GtkDragIcon.cpp`
- `Gtk/GtkDragSource.cpp`
- `Gtk/GtkDrawingArea.cpp`
- `Gtk/GtkDropDown.cpp`
- `Gtk/GtkDropTarget.cpp`
- `Gtk/GtkDropTargetAsync.cpp`
- `Gtk/GtkEditable.cpp`
- `Gtk/GtkEntry.cpp`
- `Gtk/GtkEntryBuffer.cpp`
- `Gtk/GtkEventController.cpp`
- `Gtk/GtkEventControllerFocus.cpp`
- `Gtk/GtkEventControllerKey.cpp`
- `Gtk/GtkEventControllerLegacy.cpp`
- `Gtk/GtkEventControllerMotion.cpp`
- `Gtk/GtkEventControllerScroll.cpp`
- `Gtk/GtkExpander.cpp`
- `Gtk/GtkFileDialog.cpp`
- `Gtk/GtkFileFilter.cpp`
- `Gtk/GtkFilter.cpp`
- `Gtk/GtkFilterListModel.cpp`
- `Gtk/GtkFixed.cpp`
- `Gtk/GtkFixedLayout.cpp`
- `Gtk/GtkFixedLayoutChild.cpp`
- `Gtk/GtkFlowBox.cpp`
- `Gtk/GtkFlowBoxChild.cpp`
- `Gtk/GtkFontDialog.cpp`
- `Gtk/GtkFontDialogButton.cpp`
- `Gtk/GtkFrame.cpp`
- `Gtk/GtkGesture.cpp`
- `Gtk/GtkGestureClick.cpp`
- `Gtk/GtkGestureDrag.cpp`
- `Gtk/GtkGestureLongPress.cpp`
- `Gtk/GtkGesturePan.cpp`
- `Gtk/GtkGestureRotate.cpp`
- `Gtk/GtkGestureSingle.cpp`
- `Gtk/GtkGestureSwipe.cpp`
- `Gtk/GtkGestureZoom.cpp`
- `Gtk/GtkGrid.cpp`
- `Gtk/GtkGridLayout.cpp`
- `Gtk/GtkGridLayoutChild.cpp`
- `Gtk/GtkGridView.cpp`
- `Gtk/GtkHeaderBar.cpp`
- `Gtk/GtkIconPaintable.cpp`
- `Gtk/GtkIconTheme.cpp`
- `Gtk/GtkImage.cpp`
- `Gtk/GtkLabel.cpp`
- `Gtk/GtkLayoutChild.cpp`
- `Gtk/GtkLayoutManager.cpp`
- `Gtk/GtkListBox.cpp`
- `Gtk/GtkListBoxRow.cpp`
- `Gtk/GtkListItem.cpp`
- `Gtk/GtkListItemFactory.cpp`
- `Gtk/GtkListView.cpp`
- `Gtk/GtkMenuButton.cpp`
- `Gtk/GtkMultiSelection.cpp`
- `Gtk/GtkNative.cpp`
- `Gtk/GtkNoSelection.cpp`
- `Gtk/GtkNotebook.cpp`
- `Gtk/GtkNotebookPage.cpp`
- `Gtk/GtkOrientable.cpp`
- `Gtk/GtkOverlay.cpp`
- `Gtk/GtkOverlayLayout.cpp`
- `Gtk/GtkOverlayLayoutChild.cpp`
- `Gtk/GtkPageSetup.cpp`
- `Gtk/GtkPaned.cpp`
- `Gtk/GtkPaperSize.cpp`
- `Gtk/GtkPasswordEntry.cpp`
- `Gtk/GtkPicture.cpp`
- `Gtk/GtkPopover.cpp`
- `Gtk/GtkPopoverMenu.cpp`
- `Gtk/GtkPopoverMenuBar.cpp`
- `Gtk/GtkPrintContext.cpp`
- `Gtk/GtkPrintDialog.cpp`
- `Gtk/GtkPrintOperation.cpp`
- `Gtk/GtkPrintSettings.cpp`
- `Gtk/GtkPrintSetup.cpp`
- `Gtk/GtkProgressBar.cpp`
- `Gtk/GtkRange.cpp`
- `Gtk/GtkRequisition.cpp`
- `Gtk/GtkRevealer.cpp`
- `Gtk/GtkRoot.cpp`
- `Gtk/GtkScale.cpp`
- `Gtk/GtkScrollInfo.cpp`
- `Gtk/GtkScrollable.cpp`
- `Gtk/GtkScrolledWindow.cpp`
- `Gtk/GtkSelectionModel.cpp`
- `Gtk/GtkSeparator.cpp`
- `Gtk/GtkSignalListItemFactory.cpp`
- `Gtk/GtkSingleSelection.cpp`
- `Gtk/GtkSizeGroup.cpp`
- `Gtk/GtkSnapshot.cpp`
- `Gtk/GtkSortListModel.cpp`
- `Gtk/GtkSorter.cpp`
- `Gtk/GtkSpinButton.cpp`
- `Gtk/GtkSpinner.cpp`
- `Gtk/GtkStack.cpp`
- `Gtk/GtkStackPage.cpp`
- `Gtk/GtkStackSidebar.cpp`
- `Gtk/GtkStackSwitcher.cpp`
- `Gtk/GtkStringList.cpp`
- `Gtk/GtkStringObject.cpp`
- `Gtk/GtkStyleProvider.cpp`
- `Gtk/GtkText.cpp`
- `Gtk/GtkTextBuffer.cpp`
- `Gtk/GtkTextChildAnchor.cpp`
- `Gtk/GtkTextIter.cpp`
- `Gtk/GtkTextMark.cpp`
- `Gtk/GtkTextTag.cpp`
- `Gtk/GtkTextTagTable.cpp`
- `Gtk/GtkTextView.cpp`
- `Gtk/GtkToggleButton.cpp`
- `Gtk/GtkTreeExpander.cpp`
- `Gtk/GtkTreeListModel.cpp`
- `Gtk/GtkTreeListRow.cpp`
- `Gtk/GtkUriLauncher.cpp`
- `Gtk/GtkViewport.cpp`
- `Gtk/GtkWidget.cpp`
- `Gtk/GtkWindow.cpp`
- `Gtk/Gtk.stub.php`
- `Pango/PangoAttrList.cpp`
- `Pango/PangoContext.cpp`
- `Pango/PangoFontDescription.cpp`
- `Pango/PangoFontMap.cpp`
- `Pango/PangoLayout.cpp`
- `Pango/PangoTabArray.cpp`
- `Pango/Pango.stub.php`
- `Gio/GAction.cpp`
- `Gio/GActionGroup.cpp`
- `Gio/GActionMap.cpp`
- `Gio/GApplication.cpp`
- `Gio/GAsyncResult.cpp`
- `Gio/GCancellable.cpp`
- `Gio/GIcon.cpp`
- `Gio/GInputStream.cpp`
- `Gio/GListModel.cpp`
- `Gio/GListStore.cpp`
- `Gio/GMemoryInputStream.cpp`
- `Gio/GMemoryOutputStream.cpp`
- `Gio/GMenu.cpp`
- `Gio/GMenuItem.cpp`
- `Gio/GMenuModel.cpp`
- `Gio/GNotification.cpp`
- `Gio/GOutputStream.cpp`
- `Gio/GSimpleAction.cpp`
- `Gio/GTask.cpp`
- `Gio/GThemedIcon.cpp`
- `Gio/GTlsCertificate.cpp`
- `Gio/Gio.stub.php`
- `Gdk/GdkClipboard.cpp`
- `Gdk/GdkContentFormats.cpp`
- `Gdk/GdkContentProvider.cpp`
- `Gdk/GdkCursor.cpp`
- `Gdk/GdkDevice.cpp`
- `Gdk/GdkDisplay.cpp`
- `Gdk/GdkDrag.cpp`
- `Gdk/GdkDrop.cpp`
- `Gdk/GdkMemoryTexture.cpp`
- `Gdk/GdkMonitor.cpp`
- `Gdk/GdkPaintable.cpp`
- `Gdk/GdkSeat.cpp`
- `Gdk/GdkSnapshot.cpp`
- `Gdk/GdkSurface.cpp`
- `Gdk/GdkTexture.cpp`
- `Gdk/GdkTextureDownloader.cpp`
- `Gdk/Gdk.stub.php`
- `Gsk/GskBlendNode.cpp`
- `Gsk/GskBlurNode.cpp`
- `Gsk/GskBorderNode.cpp`
- `Gsk/GskCairoNode.cpp`
- `Gsk/GskCairoRenderer.cpp`
- `Gsk/GskClipNode.cpp`
- `Gsk/GskColorMatrixNode.cpp`
- `Gsk/GskColorNode.cpp`
- `Gsk/GskConicGradientNode.cpp`
- `Gsk/GskContainerNode.cpp`
- `Gsk/GskCrossFadeNode.cpp`
- `Gsk/GskDebugNode.cpp`
- `Gsk/GskFillNode.cpp`
- `Gsk/GskGLRenderer.cpp`
- `Gsk/GskInsetShadowNode.cpp`
- `Gsk/GskLinearGradientNode.cpp`
- `Gsk/GskMaskNode.cpp`
- `Gsk/GskOpacityNode.cpp`
- `Gsk/GskOutsetShadowNode.cpp`
- `Gsk/GskPath.cpp`
- `Gsk/GskPathBuilder.cpp`
- `Gsk/GskPathMeasure.cpp`
- `Gsk/GskPathPoint.cpp`
- `Gsk/GskRadialGradientNode.cpp`
- `Gsk/GskRenderNode.cpp`
- `Gsk/GskRenderer.cpp`
- `Gsk/GskRepeatNode.cpp`
- `Gsk/GskRepeatingLinearGradientNode.cpp`
- `Gsk/GskRepeatingRadialGradientNode.cpp`
- `Gsk/GskRoundedClipNode.cpp`
- `Gsk/GskShadowNode.cpp`
- `Gsk/GskStroke.cpp`
- `Gsk/GskStrokeNode.cpp`
- `Gsk/GskSubsurfaceNode.cpp`
- `Gsk/GskTextNode.cpp`
- `Gsk/GskTextureNode.cpp`
- `Gsk/GskTextureScaleNode.cpp`
- `Gsk/GskTransform.cpp`
- `Gsk/GskTransformNode.cpp`
- `Gsk/Gsk.stub.php`
- `Graphene/GrapheneMatrix.cpp`
- `Graphene/GraphenePoint.cpp`
- `Graphene/GraphenePoint3D.cpp`
- `Graphene/GrapheneRect.cpp`
- `Graphene/GrapheneSize.cpp`
- `Graphene/GrapheneVec2.cpp`
- `Graphene/GrapheneVec3.cpp`
- `Graphene/GrapheneVec4.cpp`
- `Graphene/Graphene.stub.php`
- `GdkPixbuf/GdkPixbuf.cpp`
- `GdkPixbuf/GdkPixbufAnimation.cpp`
- `GdkPixbuf/GdkPixbufAnimationIter.cpp`
- `GdkPixbuf/GdkPixbufFormat.cpp`
- `GdkPixbuf/GdkPixbufLoader.cpp`
- `GdkPixbuf/GdkPixbuf.stub.php`
- `WebKit/WebKitApplicationInfo.cpp`
- `WebKit/WebKitAuthenticationRequest.cpp`
- `WebKit/WebKitAutomationSession.cpp`
- `WebKit/WebKitBackForwardList.cpp`
- `WebKit/WebKitBackForwardListItem.cpp`
- `WebKit/WebKitClipboardPermissionRequest.cpp`
- `WebKit/WebKitColorChooserRequest.cpp`
- `WebKit/WebKitContextMenu.cpp`
- `WebKit/WebKitContextMenuItem.cpp`
- `WebKit/WebKitCookieManager.cpp`
- `WebKit/WebKitCredential.cpp`
- `WebKit/WebKitDeviceInfoPermissionRequest.cpp`
- `WebKit/WebKitDownload.cpp`
- `WebKit/WebKitEditorState.cpp`
- `WebKit/WebKitFaviconDatabase.cpp`
- `WebKit/WebKitFeature.cpp`
- `WebKit/WebKitFeatureList.cpp`
- `WebKit/WebKitFileChooserRequest.cpp`
- `WebKit/WebKitFindController.cpp`
- `WebKit/WebKitFormSubmissionRequest.cpp`
- `WebKit/WebKitGeolocationManager.cpp`
- `WebKit/WebKitGeolocationPermissionRequest.cpp`
- `WebKit/WebKitGeolocationPosition.cpp`
- `WebKit/WebKitHitTestResult.cpp`
- `WebKit/WebKitITPFirstParty.cpp`
- `WebKit/WebKitITPThirdParty.cpp`
- `WebKit/WebKitInputMethodContext.cpp`
- `WebKit/WebKitInputMethodUnderline.cpp`
- `WebKit/WebKitMediaKeySystemPermissionRequest.cpp`
- `WebKit/WebKitMemoryPressureSettings.cpp`
- `WebKit/WebKitNavigationAction.cpp`
- `WebKit/WebKitNavigationPolicyDecision.cpp`
- `WebKit/WebKitNetworkProxySettings.cpp`
- `WebKit/WebKitNetworkSession.cpp`
- `WebKit/WebKitNotification.cpp`
- `WebKit/WebKitNotificationPermissionRequest.cpp`
- `WebKit/WebKitOptionMenu.cpp`
- `WebKit/WebKitOptionMenuItem.cpp`
- `WebKit/WebKitPermissionRequest.cpp`
- `WebKit/WebKitPermissionStateQuery.cpp`
- `WebKit/WebKitPointerLockPermissionRequest.cpp`
- `WebKit/WebKitPolicyDecision.cpp`
- `WebKit/WebKitPrintOperation.cpp`
- `WebKit/WebKitResponsePolicyDecision.cpp`
- `WebKit/WebKitScriptDialog.cpp`
- `WebKit/WebKitScriptMessageReply.cpp`
- `WebKit/WebKitSecurityManager.cpp`
- `WebKit/WebKitSecurityOrigin.cpp`
- `WebKit/WebKitSettings.cpp`
- `WebKit/WebKitURIRequest.cpp`
- `WebKit/WebKitURIResponse.cpp`
- `WebKit/WebKitURISchemeRequest.cpp`
- `WebKit/WebKitURISchemeResponse.cpp`
- `WebKit/WebKitUserContentFilter.cpp`
- `WebKit/WebKitUserContentFilterStore.cpp`
- `WebKit/WebKitUserContentManager.cpp`
- `WebKit/WebKitUserMediaPermissionRequest.cpp`
- `WebKit/WebKitUserMessage.cpp`
- `WebKit/WebKitUserScript.cpp`
- `WebKit/WebKitUserStyleSheet.cpp`
- `WebKit/WebKitWebContext.cpp`
- `WebKit/WebKitWebInspector.cpp`
- `WebKit/WebKitWebResource.cpp`
- `WebKit/WebKitWebView.cpp`
- `WebKit/WebKitWebViewBase.cpp`
- `WebKit/WebKitWebViewSessionState.cpp`
- `WebKit/WebKitWebsiteData.cpp`
- `WebKit/WebKitWebsiteDataAccessPermissionRequest.cpp`
- `WebKit/WebKitWebsiteDataManager.cpp`
- `WebKit/WebKitWebsitePolicies.cpp`
- `WebKit/WebKitWindowProperties.cpp`
- `WebKit/WebKitXRPermissionRequest.cpp`
- `WebKit/WebKit.stub.php`
- `JavaScriptCore/JSCContext.cpp`
- `JavaScriptCore/JSCException.cpp`
- `JavaScriptCore/JSCValue.cpp`
- `JavaScriptCore/JSCVirtualMachine.cpp`
- `JavaScriptCore/JavaScriptCore.stub.php`
- `GLib/GDateTime.cpp`
- `GLib/GKeyFile.cpp`
- `GLib/GTimeZone.cpp`
- `GLib/GLib.stub.php`
- `Soup/SoupCookie.cpp`
- `Soup/SoupMessageHeaders.cpp`
- `Soup/Soup.stub.php`
- `gen_minit_defs.inc`
- `gen_minit.inc`
- `gen_prototypes.h`
- `gen_arginfo.h`
- `examples/generated-sections.inc`
- `tests/Generated/GtkAboutDialogSmokeTest.php`
- `tests/Generated/GtkActionBarSmokeTest.php`
- `tests/Generated/GtkAlertDialogSmokeTest.php`
- `tests/Generated/GtkApplicationSmokeTest.php`
- `tests/Generated/GtkAspectFrameSmokeTest.php`
- `tests/Generated/GtkBinLayoutSmokeTest.php`
- `tests/Generated/GtkBuilderSmokeTest.php`
- `tests/Generated/GtkButtonSmokeTest.php`
- `tests/Generated/GtkCalendarSmokeTest.php`
- `tests/Generated/GtkCenterBoxSmokeTest.php`
- `tests/Generated/GtkCenterLayoutSmokeTest.php`
- `tests/Generated/GtkCheckButtonSmokeTest.php`
- `tests/Generated/GtkColorDialogSmokeTest.php`
- `tests/Generated/GtkColorDialogButtonSmokeTest.php`
- `tests/Generated/GtkColumnViewSmokeTest.php`
- `tests/Generated/GtkColumnViewColumnSmokeTest.php`
- `tests/Generated/GtkCssProviderSmokeTest.php`
- `tests/Generated/GtkCustomFilterSmokeTest.php`
- `tests/Generated/GtkCustomSorterSmokeTest.php`
- `tests/Generated/GtkDragSourceSmokeTest.php`
- `tests/Generated/GtkDrawingAreaSmokeTest.php`
- `tests/Generated/GtkDropDownSmokeTest.php`
- `tests/Generated/GtkDropTargetAsyncSmokeTest.php`
- `tests/Generated/GtkEntrySmokeTest.php`
- `tests/Generated/GtkEntryBufferSmokeTest.php`
- `tests/Generated/GtkEventControllerSmokeTest.php`
- `tests/Generated/GtkEventControllerFocusSmokeTest.php`
- `tests/Generated/GtkEventControllerKeySmokeTest.php`
- `tests/Generated/GtkEventControllerLegacySmokeTest.php`
- `tests/Generated/GtkEventControllerMotionSmokeTest.php`
- `tests/Generated/GtkEventControllerScrollSmokeTest.php`
- `tests/Generated/GtkExpanderSmokeTest.php`
- `tests/Generated/GtkFileDialogSmokeTest.php`
- `tests/Generated/GtkFileFilterSmokeTest.php`
- `tests/Generated/GtkFilterSmokeTest.php`
- `tests/Generated/GtkFilterListModelSmokeTest.php`
- `tests/Generated/GtkFixedSmokeTest.php`
- `tests/Generated/GtkFixedLayoutSmokeTest.php`
- `tests/Generated/GtkFlowBoxSmokeTest.php`
- `tests/Generated/GtkFlowBoxChildSmokeTest.php`
- `tests/Generated/GtkFontDialogSmokeTest.php`
- `tests/Generated/GtkFontDialogButtonSmokeTest.php`
- `tests/Generated/GtkFrameSmokeTest.php`
- `tests/Generated/GtkGestureSmokeTest.php`
- `tests/Generated/GtkGestureClickSmokeTest.php`
- `tests/Generated/GtkGestureDragSmokeTest.php`
- `tests/Generated/GtkGestureLongPressSmokeTest.php`
- `tests/Generated/GtkGestureRotateSmokeTest.php`
- `tests/Generated/GtkGestureSingleSmokeTest.php`
- `tests/Generated/GtkGestureSwipeSmokeTest.php`
- `tests/Generated/GtkGestureZoomSmokeTest.php`
- `tests/Generated/GtkGridSmokeTest.php`
- `tests/Generated/GtkGridLayoutSmokeTest.php`
- `tests/Generated/GtkGridViewSmokeTest.php`
- `tests/Generated/GtkHeaderBarSmokeTest.php`
- `tests/Generated/GtkIconPaintableSmokeTest.php`
- `tests/Generated/GtkIconThemeSmokeTest.php`
- `tests/Generated/GtkImageSmokeTest.php`
- `tests/Generated/GtkLabelSmokeTest.php`
- `tests/Generated/GtkLayoutManagerSmokeTest.php`
- `tests/Generated/GtkListBoxSmokeTest.php`
- `tests/Generated/GtkListBoxRowSmokeTest.php`
- `tests/Generated/GtkListItemFactorySmokeTest.php`
- `tests/Generated/GtkListViewSmokeTest.php`
- `tests/Generated/GtkMenuButtonSmokeTest.php`
- `tests/Generated/GtkMultiSelectionSmokeTest.php`
- `tests/Generated/GtkNoSelectionSmokeTest.php`
- `tests/Generated/GtkNotebookSmokeTest.php`
- `tests/Generated/GtkOverlaySmokeTest.php`
- `tests/Generated/GtkOverlayLayoutSmokeTest.php`
- `tests/Generated/GtkPageSetupSmokeTest.php`
- `tests/Generated/GtkPasswordEntrySmokeTest.php`
- `tests/Generated/GtkPictureSmokeTest.php`
- `tests/Generated/GtkPopoverSmokeTest.php`
- `tests/Generated/GtkPopoverMenuSmokeTest.php`
- `tests/Generated/GtkPopoverMenuBarSmokeTest.php`
- `tests/Generated/GtkPrintDialogSmokeTest.php`
- `tests/Generated/GtkPrintOperationSmokeTest.php`
- `tests/Generated/GtkPrintSettingsSmokeTest.php`
- `tests/Generated/GtkProgressBarSmokeTest.php`
- `tests/Generated/GtkRangeSmokeTest.php`
- `tests/Generated/GtkRevealerSmokeTest.php`
- `tests/Generated/GtkScrolledWindowSmokeTest.php`
- `tests/Generated/GtkSignalListItemFactorySmokeTest.php`
- `tests/Generated/GtkSingleSelectionSmokeTest.php`
- `tests/Generated/GtkSnapshotSmokeTest.php`
- `tests/Generated/GtkSortListModelSmokeTest.php`
- `tests/Generated/GtkSorterSmokeTest.php`
- `tests/Generated/GtkSpinButtonSmokeTest.php`
- `tests/Generated/GtkSpinnerSmokeTest.php`
- `tests/Generated/GtkStackSmokeTest.php`
- `tests/Generated/GtkStackSidebarSmokeTest.php`
- `tests/Generated/GtkStackSwitcherSmokeTest.php`
- `tests/Generated/GtkStringListSmokeTest.php`
- `tests/Generated/GtkStringObjectSmokeTest.php`
- `tests/Generated/GtkTextSmokeTest.php`
- `tests/Generated/GtkTextBufferSmokeTest.php`
- `tests/Generated/GtkTextChildAnchorSmokeTest.php`
- `tests/Generated/GtkTextMarkSmokeTest.php`
- `tests/Generated/GtkTextTagSmokeTest.php`
- `tests/Generated/GtkTextTagTableSmokeTest.php`
- `tests/Generated/GtkTextViewSmokeTest.php`
- `tests/Generated/GtkToggleButtonSmokeTest.php`
- `tests/Generated/GtkTreeExpanderSmokeTest.php`
- `tests/Generated/GtkUriLauncherSmokeTest.php`
- `tests/Generated/GtkViewportSmokeTest.php`
- `tests/Generated/GtkWidgetSmokeTest.php`
- `tests/Generated/GtkWindowSmokeTest.php`
- `tests/Generated/PangoContextSmokeTest.php`
- `tests/Generated/GApplicationSmokeTest.php`
- `tests/Generated/GCancellableSmokeTest.php`
- `tests/Generated/GInputStreamSmokeTest.php`
- `tests/Generated/GListStoreSmokeTest.php`
- `tests/Generated/GMemoryInputStreamSmokeTest.php`
- `tests/Generated/GMemoryOutputStreamSmokeTest.php`
- `tests/Generated/GMenuSmokeTest.php`
- `tests/Generated/GMenuItemSmokeTest.php`
- `tests/Generated/GNotificationSmokeTest.php`
- `tests/Generated/GOutputStreamSmokeTest.php`
- `tests/Generated/GSimpleActionSmokeTest.php`
- `tests/Generated/GTaskSmokeTest.php`
- `tests/Generated/GThemedIconSmokeTest.php`
- `tests/Generated/GdkContentProviderSmokeTest.php`
- `tests/Generated/GdkCursorSmokeTest.php`
- `tests/Generated/GdkSnapshotSmokeTest.php`
- `tests/Generated/GskCairoRendererSmokeTest.php`
- `tests/Generated/GskGLRendererSmokeTest.php`
- `tests/Generated/GskRendererSmokeTest.php`
- `tests/Generated/GdkPixbufLoaderSmokeTest.php`
- `tests/Generated/WebKitContextMenuSmokeTest.php`
- `tests/Generated/WebKitContextMenuItemSmokeTest.php`
- `tests/Generated/WebKitInputMethodContextSmokeTest.php`
- `tests/Generated/WebKitNetworkSessionSmokeTest.php`
- `tests/Generated/WebKitSettingsSmokeTest.php`
- `tests/Generated/WebKitURIRequestSmokeTest.php`
- `tests/Generated/WebKitUserContentFilterStoreSmokeTest.php`
- `tests/Generated/WebKitUserContentManagerSmokeTest.php`
- `tests/Generated/WebKitUserMessageSmokeTest.php`
- `tests/Generated/WebKitWebContextSmokeTest.php`
- `tests/Generated/WebKitWebViewSmokeTest.php`
- `tests/Generated/WebKitWebsitePoliciesSmokeTest.php`
- `tests/Generated/JSCContextSmokeTest.php`
- `tests/Generated/JSCVirtualMachineSmokeTest.php`
