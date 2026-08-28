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
- `source_new` — return type GLib.Source

## GListModel

- `get_item` — shadowed by get_object

## GListStore

- `find_with_equal_func` — callback parameter (needs an override)
- `find_with_equal_func_full` — callback parameter (needs an override)
- `insert_sorted` — callback parameter (needs an override)
- `sort` — callback parameter (needs an override)
- `splice` — parameter `additions` of type array (C array)

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

## GdkDisplay

- `create_gl_context` — return type Gdk.GLContext (not in the closure)
- `device_is_grabbed` — parameter `device` of type Gdk.Device
- `get_app_launch_context` — return type Gdk.AppLaunchContext (not in the closure)
- `get_clipboard` — return type Gdk.Clipboard (not in the closure)
- `get_default_seat` — return type Gdk.Seat (not in the closure)
- `get_dmabuf_formats` — return type Gdk.DmabufFormats
- `get_monitor_at_surface` — parameter `surface` of type Gdk.Surface
- `get_primary_clipboard` — return type Gdk.Clipboard (not in the closure)
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

## GdkTexture

- `new_for_pixbuf` — parameter `pixbuf` of type GdkPixbuf.Pixbuf
- `new_from_file` — parameter `file` of type Gio.File
- `new_from_resource` — skip.txt: g_error()s (aborts the process) on an invalid resource path - not a PHP-safe API
- `download` — parameter `data` of type array (C array)
- `__construct` — skip.txt: abstract for GTK's own subclasses only: a texture needs internal state (color state, 4.16+) that only the factories set - no PHP subtypes, no `new`
- `smoke test` — smoke-skip.txt: factories need real image data (TextureTest covers it)

## GtkAlertDialog

- `new` — varargs

## GtkAlign

- `baseline` — duplicate value 4 (alias of baseline_fill)

## GtkApplication

- `get_menu_by_id` — return type Gio.Menu (not in the closure)
- `get_menubar` — return type Gio.MenuModel (not in the closure)
- `inhibit` — skip.txt: crashes inside GTK when the window has no surface / the app is not registered
- `set_menubar` — parameter `menubar` of type Gio.MenuModel
- `uninhibit` — skip.txt: pairs with inhibit
- `property menubar` — property type Gio.MenuModel not mappable

## GtkBox

- `smoke test` — no constructor or factory whose parameters can be sampled

## GtkCssProvider

- `load_from_data` — deprecated (4.12)
- `load_from_file` — parameter `file` of type Gio.File

## GtkFilterListModel

- `property item-type` — property type Gtk.GType not mappable

## GtkLabel

- `get_attributes` — return type Pango.AttrList
- `get_extra_menu` — return type Gio.MenuModel (not in the closure)
- `get_layout` — return type Pango.Layout (not in the closure)
- `get_tabs` — return type Pango.TabArray
- `set_attributes` — parameter `attrs` of type Pango.AttrList
- `set_extra_menu` — parameter `model` of type Gio.MenuModel
- `set_tabs` — parameter `tabs` of type Pango.TabArray
- `property attributes` — property type Pango.AttrList not mappable
- `property extra-menu` — property type Gio.MenuModel not mappable
- `property tabs` — property type Pango.TabArray not mappable

## GtkRequisition

- `copy` — memory management belongs to the handle (clone / destructor)
- `free` — memory management belongs to the handle (clone / destructor)

## GtkSortListModel

- `property item-type` — property type Gtk.GType not mappable

## GtkWidget

- `activate_action` — shadowed by activate_action_variant
- `add_controller` — parameter `controller` of type Gtk.EventController
- `add_tick_callback` — callback parameter (needs an override)
- `allocate` — parameter `transform` of type Gsk.Transform
- `compute_bounds` — caller-allocates out parameter `out_bounds` of type Graphene.Rect
- `compute_point` — parameter `point` of type Graphene.Point
- `compute_transform` — caller-allocates out parameter `out_transform` of type Graphene.Matrix
- `create_pango_context` — return type Pango.Context (not in the closure)
- `create_pango_layout` — return type Pango.Layout (not in the closure)
- `dispose_template` — parameter `widget_type` of type Gtk.GType
- `get_allocated_baseline` — deprecated (4.12)
- `get_allocated_height` — deprecated (4.12)
- `get_allocated_width` — deprecated (4.12)
- `get_allocation` — deprecated (4.12)
- `get_ancestor` — parameter `widget_type` of type Gtk.GType
- `get_clipboard` — return type Gdk.Clipboard (not in the closure)
- `get_cursor` — return type Gdk.Cursor (not in the closure)
- `get_font_map` — return type Pango.FontMap (not in the closure)
- `get_font_options` — return type cairo.FontOptions
- `get_frame_clock` — return type Gdk.FrameClock (not in the closure)
- `get_layout_manager` — return type Gtk.LayoutManager (not in the closure)
- `get_native` — return type Gtk.Native (not in the closure)
- `get_pango_context` — return type Pango.Context (not in the closure)
- `get_primary_clipboard` — return type Gdk.Clipboard (not in the closure)
- `get_settings` — return type Gtk.Settings (not in the closure)
- `get_style_context` — deprecated (4.10)
- `get_template_child` — parameter `widget_type` of type Gtk.GType
- `hide` — deprecated (4.10)
- `remove_controller` — parameter `controller` of type Gtk.EventController
- `set_cursor` — parameter `cursor` of type Gdk.Cursor
- `set_font_map` — parameter `font_map` of type Pango.FontMap
- `set_font_options` — parameter `options` of type cairo.FontOptions
- `set_layout_manager` — parameter `layout_manager` of type Gtk.LayoutManager
- `show` — deprecated (4.10)
- `size_allocate` — parameter `allocation` of type Gtk.Allocation
- `snapshot_child` — parameter `snapshot` of type Gtk.Snapshot
- `translate_coordinates` — deprecated (4.12)
- `vfunc compute_expand` — parameter vexpand_p is a pointer to a scalar without direction
- `vfunc css_changed` — parameter `change` of type Gtk.CssStyleChange
- `vfunc hide` — deprecated (4.10)
- `vfunc query_tooltip` — parameter `tooltip` of type Gtk.Tooltip
- `vfunc show` — deprecated (4.10)
- `vfunc snapshot` — parameter `snapshot` of type Gtk.Snapshot
- `property cursor` — property type Gdk.Cursor not mappable
- `property layout-manager` — property type Gtk.LayoutManager not mappable

## GtkWindow

- `fullscreen_on_monitor` — parameter `monitor` of type Gdk.Monitor
- `get_group` — return type Gtk.WindowGroup (not in the closure)
- `present_with_time` — deprecated (4.14)

## Overrides in effect

- `Gio.Action`: activate
- `Gio.ActionGroup`: activate_action
- `Gio.Application`: run
- `Gio.ListStore`: __construct
- `Gio.SimpleAction`: set_state
- `Gtk.Box`: get_children
- `Gtk.CustomFilter`: __construct, set_filter_func
- `Gtk.CustomSorter`: __construct, set_sort_func
- `Gtk.DrawingArea`: set_draw_func

## Emitted files

- `Gtk/GtkAlertDialog.cpp`
- `Gtk/GtkApplication.cpp`
- `Gtk/GtkBox.cpp`
- `Gtk/GtkButton.cpp`
- `Gtk/GtkCssProvider.cpp`
- `Gtk/GtkCustomFilter.cpp`
- `Gtk/GtkCustomSorter.cpp`
- `Gtk/GtkDrawingArea.cpp`
- `Gtk/GtkFilter.cpp`
- `Gtk/GtkFilterListModel.cpp`
- `Gtk/GtkLabel.cpp`
- `Gtk/GtkOrientable.cpp`
- `Gtk/GtkRequisition.cpp`
- `Gtk/GtkRoot.cpp`
- `Gtk/GtkSortListModel.cpp`
- `Gtk/GtkSorter.cpp`
- `Gtk/GtkStyleProvider.cpp`
- `Gtk/GtkWidget.cpp`
- `Gtk/GtkWindow.cpp`
- `Gtk/Gtk.stub.php`
- `Pango/Pango.stub.php`
- `Gio/GAction.cpp`
- `Gio/GActionGroup.cpp`
- `Gio/GActionMap.cpp`
- `Gio/GApplication.cpp`
- `Gio/GAsyncResult.cpp`
- `Gio/GCancellable.cpp`
- `Gio/GListModel.cpp`
- `Gio/GListStore.cpp`
- `Gio/GSimpleAction.cpp`
- `Gio/GTask.cpp`
- `Gio/Gio.stub.php`
- `Gdk/GdkDisplay.cpp`
- `Gdk/GdkTexture.cpp`
- `Gdk/Gdk.stub.php`
- `gen_minit.inc`
- `gen_prototypes.h`
- `gen_arginfo.h`
- `examples/generated-sections.inc`
- `tests/Generated/GtkAlertDialogSmokeTest.php`
- `tests/Generated/GtkApplicationSmokeTest.php`
- `tests/Generated/GtkButtonSmokeTest.php`
- `tests/Generated/GtkCssProviderSmokeTest.php`
- `tests/Generated/GtkCustomFilterSmokeTest.php`
- `tests/Generated/GtkCustomSorterSmokeTest.php`
- `tests/Generated/GtkDrawingAreaSmokeTest.php`
- `tests/Generated/GtkFilterSmokeTest.php`
- `tests/Generated/GtkFilterListModelSmokeTest.php`
- `tests/Generated/GtkLabelSmokeTest.php`
- `tests/Generated/GtkSortListModelSmokeTest.php`
- `tests/Generated/GtkSorterSmokeTest.php`
- `tests/Generated/GtkWidgetSmokeTest.php`
- `tests/Generated/GtkWindowSmokeTest.php`
- `tests/Generated/GApplicationSmokeTest.php`
- `tests/Generated/GCancellableSmokeTest.php`
- `tests/Generated/GListStoreSmokeTest.php`
- `tests/Generated/GSimpleActionSmokeTest.php`
- `tests/Generated/GTaskSmokeTest.php`
