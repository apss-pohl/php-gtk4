/**
 * public function run(array $argv = []): int
 * Run the application (emits `startup`, `activate`, ...) until the last window closes or
 * {@see quit()} is called. $argv is what GApplication parses for command-line handling.
 */
ZEND_METHOD(Gtk4_GApplication, run) {
  HashTable *argv_ht = nullptr;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_ARRAY_HT(argv_ht)
  ZEND_PARSE_PARAMETERS_END();
  if (!assert_gui_thread("GApplication::run()")) RETURN_THROWS();
  GApplication *app = PHPGTK_SELF(GApplication, G_TYPE_APPLICATION);
  // argv must stay valid for the whole run: copy the strings.
  std::vector<std::string> strings;
  if (argv_ht != nullptr) {
    zval *item;
    // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
    ZEND_HASH_FOREACH_VAL(argv_ht, item) {
      zend_string *s = zval_get_string(item);
      strings.emplace_back(ZSTR_VAL(s), ZSTR_LEN(s));
      zend_string_release(s);
    }
    ZEND_HASH_FOREACH_END();
  }
  std::vector<char *> argv;
  argv.reserve(strings.size() + 1);
  for (auto &s : strings) argv.push_back(s.data());
  argv.push_back(nullptr);
  int status = 0;
  {
    RunningLoop running(quit_app, app);
    status = g_application_run(app, static_cast<int>(strings.size()),
                               strings.empty() ? nullptr : argv.data());
  }
  if (rethrow_parked_exception() || EG(exception) != nullptr) RETURN_THROWS();  // Rethrow mode
  RETURN_LONG(status);
}
