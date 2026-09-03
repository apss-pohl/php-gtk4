# gen/gir.php report

Skipped members, by class. Fix with gen/overrides (a hand-written body), gen/skip.txt
(a reason) or by widening gen/allowlist.txt (types outside the closure).

## GAction

- `name_is_valid` — static function on an interface (PHP interfaces have no bodies)
- `parse_detailed_name` — static function on an interface (PHP interfaces have no bodies)
- `print_detailed_name` — static function on an interface (PHP interfaces have no bodies)
- `vfunc activate` — return or argument type not convertible in a thunk
- `vfunc change_state` — return or argument type not convertible in a thunk
- `vfunc get_name` — return or argument type not convertible in a thunk
- `vfunc get_parameter_type` — return or argument type not convertible in a thunk
- `vfunc get_state` — return or argument type not convertible in a thunk
- `vfunc get_state_hint` — return or argument type not convertible in a thunk
- `vfunc get_state_type` — return or argument type not convertible in a thunk

## GActionGroup

- `query_action` — out parameter `parameter_type` of type GLib.VariantType
- `vfunc action_state_changed` — return or argument type not convertible in a thunk
- `vfunc activate_action` — return or argument type not convertible in a thunk
- `vfunc change_action_state` — return or argument type not convertible in a thunk
- `vfunc get_action_parameter_type` — return or argument type not convertible in a thunk
- `vfunc get_action_state` — return or argument type not convertible in a thunk
- `vfunc get_action_state_hint` — return or argument type not convertible in a thunk
- `vfunc get_action_state_type` — return or argument type not convertible in a thunk
- `vfunc list_actions` — return or argument type not convertible in a thunk
- `vfunc query_action` — out parameter `parameter_type` of type GLib.VariantType

## GActionMap

- `add_action_entries` — parameter `entries` of type array (C array)
- `remove_action_entries` — parameter `entries` of type array (C array)

## GApplication

- `add_main_option_entries` — parameter `entries` of type array (C array)
- `add_option_group` — parameter `group` of type GLib.OptionGroup
- `get_dbus_connection` — return type Gio.DBusConnection (not in the closure)
- `open` — parameter `files` of type array (C array)
- `send_notification` — parameter `notification` of type Gio.Notification
- `set_action_group` — deprecated (2.32)
- `vfunc add_platform_data` — parameter `builder` of type GLib.VariantBuilder
- `vfunc after_emit` — return or argument type not convertible in a thunk
- `vfunc before_emit` — return or argument type not convertible in a thunk
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

## GListModel

- `get_item` — shadowed by get_object

## GListStore

- `find_with_equal_func` — callback parameter (needs an override)
- `find_with_equal_func_full` — callback parameter (needs an override)
- `insert_sorted` — callback parameter (needs an override)
- `sort` — callback parameter (needs an override)
- `splice` — parameter `additions` of type array (C array)

## GMenuItem

- `PHP subclasses` — constructor argument label is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain GMenuItem
- `get_attribute` — varargs
- `set_action_and_target` — varargs
- `set_attribute` — varargs
- `set_icon` — parameter `icon` of type Gio.Icon

## GMenuModel

- `get_item_attribute` — varargs
- `iterate_item_attributes` — return type Gio.MenuAttributeIter (not in the closure)
- `iterate_item_links` — return type Gio.MenuLinkIter (not in the closure)
- `__construct` — skip.txt: abstract with NULL class-struct slots (get_n_items ...) and vfuncs on GHashTables that cannot be thunked; a PHP subtype would segfault on first use - build menus with GMenu
- `vfunc get_item_attribute_value` — return or argument type not convertible in a thunk
- `vfunc get_item_attributes` — out parameter `attributes` of type GLib.HashTable
- `vfunc get_item_links` — out parameter `links` of type GLib.HashTable
- `vfunc_iterate_item_attributes` — return type Gio.MenuAttributeIter (not in the closure)
- `vfunc_iterate_item_links` — return type Gio.MenuLinkIter (not in the closure)
- `smoke test` — no constructor or factory whose parameters can be sampled

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
- `return_new_error_literal` — parameter `domain` of type GLib.Quark
- `return_pointer` — gpointer parameter
- `return_prefixed_error` — varargs
- `return_value` — parameter `result` of type GObject.Value
- `run_in_thread` — callback parameter (needs an override)
- `run_in_thread_sync` — callback parameter (needs an override)
- `set_source_tag` — gpointer parameter
- `set_task_data` — gpointer parameter

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
- `write_mime_type_async` — parameter `stream` of type Gio.OutputStream
- `vfunc get_value` — GError out parameter
- `vfunc ref_formats` — return or argument type not convertible in a thunk
- `vfunc ref_storable_formats` — return or argument type not convertible in a thunk
- `vfunc write_mime_type_async` — parameter `stream` of type Gio.OutputStream
- `vfunc write_mime_type_finish` — GError out parameter

## GdkDisplay

- `create_gl_context` — return type Gdk.GLContext (not in the closure)
- `device_is_grabbed` — parameter `device` of type Gdk.Device
- `get_app_launch_context` — return type Gdk.AppLaunchContext (not in the closure)
- `get_default_seat` — return type Gdk.Seat (not in the closure)
- `get_dmabuf_formats` — return type Gdk.DmabufFormats
- `get_setting` — parameter `value` of type GObject.Value
- `get_startup_notification_id` — deprecated (4.10)
- `list_seats` — list of Gdk.Seat
- `map_keycode` — out parameter `keys` of type array
- `map_keyval` — out parameter `keys` of type array
- `notify_startup_complete` — deprecated (4.10)
- `put_event` — deprecated (4.10)
- `translate_key` — out parameter `consumed` of type Gdk.ModifierType
- `__construct` — skip.txt: GDK owns displays (GdkDisplay::get_default() / open()); a PHP subtype would have no backend behind it
- `property dmabuf-formats` — property type Gdk.DmabufFormats not mappable
- `smoke test` — no constructor or factory whose parameters can be sampled

## GdkDrag

- `begin` — parameter `device` of type Gdk.Device
- `get_device` — return type Gdk.Device (not in the closure)
- `__construct` — skip.txt: GDK creates a drag when a GtkDragSource starts one (get_drag()); a PHP subtype has no device and aborts the process in gdk_drag_set_property ("assertion failed: (priv->device != NULL)")
- `property device` — property type Gdk.Device not mappable
- `smoke test` — no constructor or factory whose parameters can be sampled

## GdkDrop

- `get_device` — return type Gdk.Device (not in the closure)
- `read_finish` — return Gio.InputStream plus out parameters
- `read_value_async` — parameter `type` of type Gdk.GType
- `read_value_finish` — return type GObject.Value
- `__construct` — skip.txt: the other end of the same drag, created by GDK; as Gdk.Drag
- `property device` — property type Gdk.Device not mappable
- `smoke test` — no constructor or factory whose parameters can be sampled

## GdkMonitor

- `__construct` — skip.txt: GDK owns the monitors (GdkDisplay::get_monitors()); a standalone one answers NULL from get_display(), which its declared type does not allow
- `smoke test` — no constructor or factory whose parameters can be sampled

## GdkPaintable

- `new_empty` — static function on an interface (PHP interfaces have no bodies)

## GdkSurface

- `create_cairo_context` — return type Gdk.CairoContext (not in the closure)
- `create_gl_context` — return type Gdk.GLContext (not in the closure)
- `create_similar_surface` — deprecated (4.12)
- `create_vulkan_context` — deprecated (4.14)
- `get_device_cursor` — parameter `device` of type Gdk.Device
- `get_device_position` — parameter `device` of type Gdk.Device
- `get_frame_clock` — return type Gdk.FrameClock (not in the closure)
- `set_device_cursor` — parameter `device` of type Gdk.Device
- `set_input_region` — parameter `region` of type cairo.Region
- `set_opaque_region` — parameter `region` of type cairo.Region
- `translate_coordinates` — inout parameter x
- `__construct` — skip.txt: GDK creates surfaces for a widget (GtkWindow) or through new_toplevel()/new_popup(); a PHP subtype has no display and aborts in gdk_surface_set_property
- `property frame-clock` — property type Gdk.FrameClock not mappable
- `smoke test` — no constructor or factory whose parameters can be sampled

## GdkTexture

- `new_for_pixbuf` — parameter `pixbuf` of type GdkPixbuf.Pixbuf
- `new_from_resource` — skip.txt: g_error()s (aborts the process) on an invalid resource path - not a PHP-safe API
- `__construct` — skip.txt: abstract for GTK's own subclasses only: a texture needs internal state (color state, 4.16+) that only the factories set - no PHP subtypes, no `new`
- `smoke test` — smoke-skip.txt: factories need real image data (TextureTest covers it)

## GraphenePoint

- `distance` — return gfloat plus out parameters
- `free` — memory management belongs to the handle (clone / destructor)
- `init_from_vec2` — parameter `src` of type Graphene.Vec2
- `to_vec2` — caller-allocates out parameter `v` of type Graphene.Vec2

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

## GtkCalendar

- `get_date` — return type GLib.DateTime
- `select_day` — parameter `date` of type GLib.DateTime

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
- `property value` — property type GObject.Value not mappable
- `smoke test` — smoke-skip.txt: the constructor takes a *type name* ("string", a registered class), not an arbitrary string; DragDropTest builds it properly

## GtkEditable

- `delegate_get_property` — static function on an interface (PHP interfaces have no bodies)
- `delegate_set_property` — static function on an interface (PHP interfaces have no bodies)
- `install_properties` — static function on an interface (PHP interfaces have no bodies)
- `insert_text` — inout parameter position
- `vfunc do_insert_text` — inout parameter position
- `vfunc get_text` — return or argument type not convertible in a thunk
- `vfunc insert_text` — inout parameter position

## GtkEntry

- `get_attributes` — return type Pango.AttrList
- `get_completion` — deprecated (4.10)
- `get_icon_gicon` — return type Gio.Icon (not in the closure)
- `get_tabs` — return type Pango.TabArray
- `set_attributes` — parameter `attrs` of type Pango.AttrList
- `set_completion` — deprecated (4.10)
- `set_icon_from_gicon` — parameter `icon` of type Gio.Icon
- `set_tabs` — parameter `tabs` of type Pango.TabArray
- `property attributes` — property type Pango.AttrList not mappable
- `property completion` — property type Gtk.EntryCompletion not mappable
- `property primary-icon-gicon` — property type Gio.Icon not mappable
- `property secondary-icon-gicon` — property type Gio.Icon not mappable
- `property tabs` — property type Pango.TabArray not mappable

## GtkEntryBuffer

- `PHP subclasses` — constructor argument initial_chars is not a construct property (map it in gen/ctor-props.txt); `new` on a PHP subclass builds a plain GtkEntryBuffer
- `vfunc get_text` — parameter n_bytes is a pointer to a scalar without direction

## GtkEventController

- `get_current_event_device` — return type Gdk.Device (not in the closure)

## GtkEventControllerKey

- `get_im_context` — return type Gtk.IMContext (not in the closure)
- `set_im_context` — parameter `im_context` of type Gtk.IMContext

## GtkFileDialog

- `property initial-file` — property type Gio.File not mappable
- `property initial-folder` — property type Gio.File not mappable

## GtkFilterListModel

- `property item-type` — property type Gtk.GType not mappable

## GtkFixed

- `get_child_transform` — return type Gsk.Transform
- `set_child_transform` — parameter `transform` of type Gsk.Transform

## GtkFixedLayoutChild

- `get_transform` — return type Gsk.Transform
- `set_transform` — parameter `transform` of type Gsk.Transform
- `__construct` — skip.txt: GtkFixedLayout creates its layout children, as Gtk.LayoutChild
- `property transform` — property type Gsk.Transform not mappable
- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkFontDialog

- `choose_face` — parameter `initial_value` of type Pango.FontFace
- `choose_face_finish` — return type Pango.FontFace (not in the closure)
- `choose_family` — parameter `initial_value` of type Pango.FontFamily
- `choose_family_finish` — return type Pango.FontFamily (not in the closure)
- `choose_font_and_features_finish` — out parameter `font_desc` of type Pango.FontDescription
- `get_font_map` — return type Pango.FontMap (not in the closure)
- `get_language` — return type Pango.Language
- `set_font_map` — parameter `fontmap` of type Pango.FontMap
- `set_language` — parameter `language` of type Pango.Language
- `property font-map` — property type Pango.FontMap not mappable
- `property language` — property type Pango.Language not mappable

## GtkFrame

- `vfunc compute_child_allocation` — parameter `allocation` of type Gtk.Allocation

## GtkGesture

- `get_device` — return type Gdk.Device (not in the closure)
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
- `has_gicon` — parameter `gicon` of type Gio.Icon
- `lookup_by_gicon` — parameter `icon` of type Gio.Icon
- `set_search_path` — parameter `path` of type array (C array)

## GtkImage

- `new_from_gicon` — parameter `icon` of type Gio.Icon
- `new_from_pixbuf` — deprecated (4.12)
- `get_gicon` — return type Gio.Icon (not in the closure)
- `set_from_gicon` — parameter `icon` of type Gio.Icon
- `set_from_pixbuf` — deprecated (4.12)
- `property gicon` — property type Gio.Icon not mappable

## GtkLabel

- `get_attributes` — return type Pango.AttrList
- `get_layout` — return type Pango.Layout (not in the closure)
- `get_tabs` — return type Pango.TabArray
- `set_attributes` — parameter `attrs` of type Pango.AttrList
- `set_tabs` — parameter `tabs` of type Pango.TabArray
- `property attributes` — property type Pango.AttrList not mappable
- `property tabs` — property type Pango.TabArray not mappable

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
- `get_renderer` — return type Gsk.Renderer (not in the closure)

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

## GtkPicture

- `new_for_pixbuf` — deprecated (4.12)
- `get_keep_aspect_ratio` — deprecated (4.8)
- `set_keep_aspect_ratio` — deprecated (4.8)
- `set_pixbuf` — deprecated (4.12)
- `property file` — property type Gio.File not mappable

## GtkRange

- `vfunc get_range_border` — parameter `border_` of type Gtk.Border

## GtkRequisition

- `copy` — memory management belongs to the handle (clone / destructor)
- `free` — memory management belongs to the handle (clone / destructor)

## GtkScale

- `get_layout` — return type Pango.Layout (not in the closure)
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

- `append_border` — parameter `outline` of type Gsk.RoundedRect
- `append_conic_gradient` — parameter `stops` of type array (C array)
- `append_fill` — parameter `path` of type Gsk.Path
- `append_inset_shadow` — parameter `outline` of type Gsk.RoundedRect
- `append_layout` — parameter `layout` of type Pango.Layout
- `append_linear_gradient` — parameter `stops` of type array (C array)
- `append_node` — parameter `node` of type Gsk.RenderNode
- `append_outset_shadow` — parameter `outline` of type Gsk.RoundedRect
- `append_radial_gradient` — parameter `stops` of type array (C array)
- `append_repeating_linear_gradient` — parameter `stops` of type array (C array)
- `append_repeating_radial_gradient` — parameter `stops` of type array (C array)
- `append_stroke` — parameter `path` of type Gsk.Path
- `free_to_node` — return type Gsk.RenderNode (not in the closure)
- `free_to_paintable` — skip.txt: frees the GtkSnapshot itself, so the handle is left on freed memory and its next qdata/toggle-ref touch is a SEGV (ASan caught it); to_paintable() answers with the same paintable and leaves the object alive
- `push_color_matrix` — parameter `color_matrix` of type Graphene.Matrix
- `push_debug` — varargs
- `push_fill` — parameter `path` of type Gsk.Path
- `push_gl_shader` — parameter `shader` of type Gsk.GLShader
- `push_rounded_clip` — parameter `bounds` of type Gsk.RoundedRect
- `push_shadow` — parameter `shadow` of type array (C array)
- `push_stroke` — parameter `path` of type Gsk.Path
- `render_background` — deprecated (4.10)
- `render_focus` — deprecated (4.10)
- `render_frame` — deprecated (4.10)
- `render_insertion_cursor` — deprecated (4.10)
- `render_layout` — deprecated (4.10)
- `rotate_3d` — parameter `axis` of type Graphene.Vec3
- `to_node` — return type Gsk.RenderNode (not in the closure)
- `transform` — parameter `transform` of type Gsk.Transform
- `transform_matrix` — parameter `matrix` of type Graphene.Matrix
- `translate_3d` — parameter `point` of type Graphene.Point3D

## GtkSortListModel

- `property item-type` — property type Gtk.GType not mappable

## GtkStackPage

- `__construct` — skip.txt: GtkStack creates its pages (add_child()/add_titled(), get_page()); `new` g_error()s "is missing a child widget"
- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkStringList

- `property item-type` — property type Gtk.GType not mappable

## GtkText

- `get_attributes` — return type Pango.AttrList
- `get_tabs` — return type Pango.TabArray
- `set_attributes` — parameter `attrs` of type Pango.AttrList
- `set_tabs` — parameter `tabs` of type Pango.TabArray
- `finish_delegate` — skip.txt: pairs with init_delegate
- `init_delegate` — skip.txt: GtkEditable plumbing for a widget that *delegates* to a GtkText from its own init; calling it on a GtkText is "invalid (NULL) pointer instance" from GLib
- `property attributes` — property type Pango.AttrList not mappable
- `property tabs` — property type Pango.TabArray not mappable

## GtkTextBuffer

- `create_child_anchor` — return type Gtk.TextChildAnchor (not in the closure)
- `create_tag` — varargs
- `get_iter_at_child_anchor` — parameter `anchor` of type Gtk.TextChildAnchor
- `insert_child_anchor` — parameter `anchor` of type Gtk.TextChildAnchor
- `insert_with_tags` — varargs
- `insert_with_tags_by_name` — varargs
- `vfunc insert_child_anchor` — parameter `anchor` of type Gtk.TextChildAnchor

## GtkTextIter

- `backward_find_char` — callback parameter (needs an override)
- `copy` — memory management belongs to the handle (clone / destructor)
- `forward_find_char` — callback parameter (needs an override)
- `free` — memory management belongs to the handle (clone / destructor)
- `get_child_anchor` — return type Gtk.TextChildAnchor (not in the closure)
- `get_language` — return type Pango.Language

## GtkTextTag

- `property tabs` — property type Pango.TabArray not mappable

## GtkTextView

- `add_child_at_anchor` — parameter `anchor` of type Gtk.TextChildAnchor
- `get_ltr_context` — return type Pango.Context (not in the closure)
- `get_rtl_context` — return type Pango.Context (not in the closure)
- `get_tabs` — return type Pango.TabArray
- `set_tabs` — parameter `tabs` of type Pango.TabArray
- `property tabs` — property type Pango.TabArray not mappable

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

- `activate_action` — shadowed by activate_action_variant
- `add_tick_callback` — callback parameter (needs an override)
- `compute_transform` — caller-allocates out parameter `out_transform` of type Graphene.Matrix
- `create_pango_context` — return type Pango.Context (not in the closure)
- `create_pango_layout` — return type Pango.Layout (not in the closure)
- `dispose_template` — parameter `widget_type` of type Gtk.GType
- `get_allocated_baseline` — deprecated (4.12)
- `get_allocated_height` — deprecated (4.12)
- `get_allocated_width` — deprecated (4.12)
- `get_allocation` — deprecated (4.12)
- `get_ancestor` — parameter `widget_type` of type Gtk.GType
- `get_font_map` — return type Pango.FontMap (not in the closure)
- `get_font_options` — return type cairo.FontOptions
- `get_frame_clock` — return type Gdk.FrameClock (not in the closure)
- `get_pango_context` — return type Pango.Context (not in the closure)
- `get_settings` — return type Gtk.Settings (not in the closure)
- `get_style_context` — deprecated (4.10)
- `get_template_child` — parameter `widget_type` of type Gtk.GType
- `hide` — deprecated (4.10)
- `set_font_map` — parameter `font_map` of type Pango.FontMap
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

## PangoFontDescription

- `copy` — memory management belongs to the handle (clone / destructor)
- `free` — memory management belongs to the handle (clone / destructor)

## Overrides in effect

- `Gdk.Clipboard`: set_value
- `Gdk.ContentFormats`: __construct, contain_gtype, get_gtypes, get_mime_types
- `Gdk.ContentProvider`: get_value, new_for_value
- `Gdk.Texture`: download
- `Gio.Action`: activate
- `Gio.ActionGroup`: activate_action, list_actions
- `Gio.Application`: get_dbus_object_path, get_is_remote, run
- `Gio.ListStore`: __construct
- `Gio.SimpleAction`: set_state
- `Gio.Task`: propagate_boolean, propagate_int, return_boolean, return_error, return_int
- `Graphene.Rect`: inset, normalize, offset
- `Gtk.Box`: get_children
- `Gtk.Builder`: add_from_string, add_objects_from_string, set_current_object, set_handlers
- `Gtk.CustomFilter`: __construct, set_filter_func
- `Gtk.CustomSorter`: __construct, set_sort_func
- `Gtk.DrawingArea`: set_draw_func
- `Gtk.DropTarget`: __construct, get_gtypes, get_value, set_gtypes
- `Gtk.DropTargetAsync`: get_formats
- `Gtk.Entry`: grab_focus_without_selecting
- `Gtk.EventControllerKey`: get_group
- `Gtk.FileFilter`: new_from_gvariant
- `Gtk.Gesture`: get_bounding_box_center
- `Gtk.IconTheme`: set_theme_name
- `Gtk.LayoutManager`: get_layout_child, get_request_mode
- `Gtk.MenuButton`: set_create_popup_func
- `Gtk.Popover`: get_pointing_to, popup
- `Gtk.Scale`: set_format_value_func
- `Gtk.Text`: grab_focus_without_selecting
- `Gtk.TextBuffer`: insert, insert_at_cursor, insert_interactive, insert_interactive_at_cursor, insert_markup, set_text
- `Gtk.TextIter`: set_line_index, set_line_offset
- `Gtk.TextView`: get_extra_menu
- `Gtk.TreeListModel`: __construct
- `Gtk.Widget`: allocate

## Emitted files

- `Gtk/GtkAboutDialog.cpp`
- `Gtk/GtkAdjustment.cpp`
- `Gtk/GtkAlertDialog.cpp`
- `Gtk/GtkApplication.cpp`
- `Gtk/GtkApplicationWindow.cpp`
- `Gtk/GtkBinLayout.cpp`
- `Gtk/GtkBitset.cpp`
- `Gtk/GtkBox.cpp`
- `Gtk/GtkBoxLayout.cpp`
- `Gtk/GtkBuilder.cpp`
- `Gtk/GtkBuilderScope.cpp`
- `Gtk/GtkButton.cpp`
- `Gtk/GtkCalendar.cpp`
- `Gtk/GtkCenterLayout.cpp`
- `Gtk/GtkCheckButton.cpp`
- `Gtk/GtkColorDialog.cpp`
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
- `Gtk/GtkFileDialog.cpp`
- `Gtk/GtkFileFilter.cpp`
- `Gtk/GtkFilter.cpp`
- `Gtk/GtkFilterListModel.cpp`
- `Gtk/GtkFixed.cpp`
- `Gtk/GtkFixedLayout.cpp`
- `Gtk/GtkFixedLayoutChild.cpp`
- `Gtk/GtkFontDialog.cpp`
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
- `Gtk/GtkPaned.cpp`
- `Gtk/GtkPasswordEntry.cpp`
- `Gtk/GtkPicture.cpp`
- `Gtk/GtkPopover.cpp`
- `Gtk/GtkPopoverMenu.cpp`
- `Gtk/GtkPopoverMenuBar.cpp`
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
- `Gtk/GtkTextIter.cpp`
- `Gtk/GtkTextMark.cpp`
- `Gtk/GtkTextTag.cpp`
- `Gtk/GtkTextTagTable.cpp`
- `Gtk/GtkTextView.cpp`
- `Gtk/GtkToggleButton.cpp`
- `Gtk/GtkTreeExpander.cpp`
- `Gtk/GtkTreeListModel.cpp`
- `Gtk/GtkTreeListRow.cpp`
- `Gtk/GtkViewport.cpp`
- `Gtk/GtkWidget.cpp`
- `Gtk/GtkWindow.cpp`
- `Gtk/Gtk.stub.php`
- `Pango/PangoFontDescription.cpp`
- `Pango/Pango.stub.php`
- `Gio/GAction.cpp`
- `Gio/GActionGroup.cpp`
- `Gio/GActionMap.cpp`
- `Gio/GApplication.cpp`
- `Gio/GAsyncResult.cpp`
- `Gio/GCancellable.cpp`
- `Gio/GListModel.cpp`
- `Gio/GListStore.cpp`
- `Gio/GMenu.cpp`
- `Gio/GMenuItem.cpp`
- `Gio/GMenuModel.cpp`
- `Gio/GSimpleAction.cpp`
- `Gio/GTask.cpp`
- `Gio/Gio.stub.php`
- `Gdk/GdkClipboard.cpp`
- `Gdk/GdkContentFormats.cpp`
- `Gdk/GdkContentProvider.cpp`
- `Gdk/GdkCursor.cpp`
- `Gdk/GdkDisplay.cpp`
- `Gdk/GdkDrag.cpp`
- `Gdk/GdkDrop.cpp`
- `Gdk/GdkMonitor.cpp`
- `Gdk/GdkPaintable.cpp`
- `Gdk/GdkSnapshot.cpp`
- `Gdk/GdkSurface.cpp`
- `Gdk/GdkTexture.cpp`
- `Gdk/Gdk.stub.php`
- `Gsk/Gsk.stub.php`
- `Graphene/GraphenePoint.cpp`
- `Graphene/GrapheneRect.cpp`
- `Graphene/GrapheneSize.cpp`
- `Graphene/Graphene.stub.php`
- `gen_minit.inc`
- `gen_prototypes.h`
- `gen_arginfo.h`
- `examples/generated-sections.inc`
- `tests/Generated/GtkAboutDialogSmokeTest.php`
- `tests/Generated/GtkAlertDialogSmokeTest.php`
- `tests/Generated/GtkApplicationSmokeTest.php`
- `tests/Generated/GtkBinLayoutSmokeTest.php`
- `tests/Generated/GtkBuilderSmokeTest.php`
- `tests/Generated/GtkButtonSmokeTest.php`
- `tests/Generated/GtkCalendarSmokeTest.php`
- `tests/Generated/GtkCenterLayoutSmokeTest.php`
- `tests/Generated/GtkCheckButtonSmokeTest.php`
- `tests/Generated/GtkColorDialogSmokeTest.php`
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
- `tests/Generated/GtkFileDialogSmokeTest.php`
- `tests/Generated/GtkFileFilterSmokeTest.php`
- `tests/Generated/GtkFilterSmokeTest.php`
- `tests/Generated/GtkFilterListModelSmokeTest.php`
- `tests/Generated/GtkFixedSmokeTest.php`
- `tests/Generated/GtkFixedLayoutSmokeTest.php`
- `tests/Generated/GtkFontDialogSmokeTest.php`
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
- `tests/Generated/GtkListItemFactorySmokeTest.php`
- `tests/Generated/GtkListViewSmokeTest.php`
- `tests/Generated/GtkMenuButtonSmokeTest.php`
- `tests/Generated/GtkMultiSelectionSmokeTest.php`
- `tests/Generated/GtkNoSelectionSmokeTest.php`
- `tests/Generated/GtkNotebookSmokeTest.php`
- `tests/Generated/GtkOverlaySmokeTest.php`
- `tests/Generated/GtkOverlayLayoutSmokeTest.php`
- `tests/Generated/GtkPasswordEntrySmokeTest.php`
- `tests/Generated/GtkPictureSmokeTest.php`
- `tests/Generated/GtkPopoverSmokeTest.php`
- `tests/Generated/GtkPopoverMenuSmokeTest.php`
- `tests/Generated/GtkPopoverMenuBarSmokeTest.php`
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
- `tests/Generated/GtkTextMarkSmokeTest.php`
- `tests/Generated/GtkTextTagSmokeTest.php`
- `tests/Generated/GtkTextTagTableSmokeTest.php`
- `tests/Generated/GtkTextViewSmokeTest.php`
- `tests/Generated/GtkToggleButtonSmokeTest.php`
- `tests/Generated/GtkTreeExpanderSmokeTest.php`
- `tests/Generated/GtkViewportSmokeTest.php`
- `tests/Generated/GtkWidgetSmokeTest.php`
- `tests/Generated/GtkWindowSmokeTest.php`
- `tests/Generated/GApplicationSmokeTest.php`
- `tests/Generated/GCancellableSmokeTest.php`
- `tests/Generated/GListStoreSmokeTest.php`
- `tests/Generated/GMenuSmokeTest.php`
- `tests/Generated/GMenuItemSmokeTest.php`
- `tests/Generated/GSimpleActionSmokeTest.php`
- `tests/Generated/GTaskSmokeTest.php`
- `tests/Generated/GdkContentProviderSmokeTest.php`
- `tests/Generated/GdkCursorSmokeTest.php`
- `tests/Generated/GdkSnapshotSmokeTest.php`
