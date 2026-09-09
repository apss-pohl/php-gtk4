// __construct(): GTK asserts that the bytes cover the image; the check runs here first
// (ARG_PRECONDITIONS in gen/gir/config.php), with the per-format pixel size GTK keeps private.
namespace {

// Bytes per pixel of a GdkMemoryFormat (GTK 4.14's list; gdk_memory_format_bytes_per_pixel()
// is not public API).
gsize bytes_per_pixel(GdkMemoryFormat format) {
  switch (format) {
    case GDK_MEMORY_G8: return 1;
    case GDK_MEMORY_G8A8_PREMULTIPLIED:
    case GDK_MEMORY_G8A8:
    case GDK_MEMORY_G16: return 2;
    case GDK_MEMORY_R8G8B8:
    case GDK_MEMORY_B8G8R8: return 3;
    case GDK_MEMORY_R16G16B16:
    case GDK_MEMORY_R16G16B16_FLOAT: return 6;
    case GDK_MEMORY_R16G16B16A16_PREMULTIPLIED:
    case GDK_MEMORY_R16G16B16A16:
    case GDK_MEMORY_R16G16B16A16_FLOAT_PREMULTIPLIED:
    case GDK_MEMORY_R16G16B16A16_FLOAT: return 8;
    case GDK_MEMORY_R32G32B32_FLOAT: return 12;
    case GDK_MEMORY_R32G32B32A32_FLOAT_PREMULTIPLIED:
    case GDK_MEMORY_R32G32B32A32_FLOAT: return 16;
    default: return 4;  // the 8-bit RGBA layouts, G16A16 and anything newer
  }
}

}  // namespace

