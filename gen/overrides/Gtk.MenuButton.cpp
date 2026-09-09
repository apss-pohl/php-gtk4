// GtkMenuButtonCreatePopupFunc (notified scope): the PHP callable lives in a Callback owned by GTK.
#include "core/callback.h"
#include "core/teardown.h"

namespace {

// GtkMenuButtonCreatePopupFunc trampoline: (GtkMenuButton $button) -> void, run right before
// the popup is shown so the callable can set_popover()/set_menu_model() lazily.
void create_popup_func(GtkMenuButton *button, gpointer data) {
  auto *cb = static_cast<Callback *>(data);
  zval arg;
  wrap(G_OBJECT(button), &arg);
  zval retval;
  callback_invoke(cb, 1, &arg, &retval);
  zval_ptr_dtor(&retval);
  zval_ptr_dtor(&arg);
}

// GDestroyNotify for the create-popup func.
void create_popup_func_free(gpointer data) {
  teardown_untrack_notified(data);
  callback_free(data);
}

// teardown clear hook: drop the PHP callable from the button.
void create_popup_func_clear(GObject *owner) {
  gtk_menu_button_set_create_popup_func(GTK_MENU_BUTTON(owner), nullptr, nullptr, nullptr);
}

// Install (or with null, remove) a PHP create-popup func on `button`.
void install_create_popup_func(GtkMenuButton *button, zend_fcall_info *fci, const char *origin) {
  if (!ZEND_FCI_INITIALIZED(*fci)) {
    gtk_menu_button_set_create_popup_func(button, nullptr, nullptr, nullptr);
  } else {
    Callback *cb = callback_new(&fci->function_name, origin);
    teardown_track_notified(cb, G_OBJECT(button), create_popup_func_clear);
    gtk_menu_button_set_create_popup_func(button, create_popup_func, cb, create_popup_func_free);
  }
  callback_drain();  // the previous func's notify ran inside the setter
}

}  // namespace
