// run() remembers its result so get_error() can tell whether there is one to hand out.
namespace {

// The GtkPrintOperationResult of the last run(), kept as qdata on the operation (a heap gint,
// freed with the operation).
GQuark last_result_quark() {
  static const GQuark quark = g_quark_from_static_string("php-gtk4-print-last-result");
  return quark;
}

}  // namespace

