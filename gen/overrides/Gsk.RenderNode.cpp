// deserialize(): GskParseErrorFunc collects the first error for the ValueError.
#include <string>

namespace {

// GskParseErrorFunc: remember the first problem (line:column: message) in the std::string
// deserialize() passed as user data; GSK keeps calling for later errors, which say less.
void parse_error(const GskParseLocation *start, const GskParseLocation *end, const GError *error,
                 gpointer user_data) {
  (void)end;
  auto *first = static_cast<std::string *>(user_data);
  if (!first->empty()) return;
  *first = std::to_string(start->lines + 1) + ":" + std::to_string(start->line_chars + 1) + ": " +
           (error != nullptr && error->message != nullptr ? error->message : "parse error");
}

}  // namespace

