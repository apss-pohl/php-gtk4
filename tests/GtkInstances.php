<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use ReflectionClass;

/**
 * One live instance per registered class, for the two generic sweeps that need a target to
 * call methods on: {@see RobustnessTest} (hostile arguments) and {@see TypeDeclarationTest}
 * (arg-less getters against their declared return type).
 *
 * Both used to build their own, and both skipped whatever they could not `new`: an abstract
 * base, a handle GTK only ever hands out (`new` throws on it - CLAUDE.md, `core/fundamental`),
 * a constructor with an argument the generic path cannot invent. That is the bulk of the
 * suite's skips, and it skipped exactly the surface no hand-written test reaches either.
 *
 * A named branch here puts a whole class under both sweeps, so keep them cheap and keep them
 * honest: build the handle the way an application would (`$display->get_clipboard()`,
 * `$notebook->get_page($child)`), never by reaching around the binding.
 *
 * What stays unbuildable is listed in {@see UNREACHABLE}; the sweeps still skip those.
 */
final class GtkInstances
{
    /**
     * Classes no test can produce an instance of, with the reason. Not used in code - it is
     * the answer to "why is this still skipped", kept next to the factory that would hold the
     * branch if one existed.
     *
     * @var array<string, string>
     */
    public const UNREACHABLE = [
        // Real input events. GTK 4 has no public constructor for any of them (GDK 3's
        // gdk_event_new() is gone), and X11 under Xvfb has no device to synthesise from.
        \Gtk4\GdkEvent::class => 'only GDK creates events, from real input',
        \Gtk4\GdkButtonEvent::class => 'only GDK creates events, from real input',
        \Gtk4\GdkCrossingEvent::class => 'only GDK creates events, from real input',
        \Gtk4\GdkFocusEvent::class => 'only GDK creates events, from real input',
        \Gtk4\GdkGrabBrokenEvent::class => 'only GDK creates events, from real input',
        \Gtk4\GdkKeyEvent::class => 'only GDK creates events, from real input',
        \Gtk4\GdkPadEvent::class => 'only GDK creates events, from real input',
        \Gtk4\GdkScrollEvent::class => 'only GDK creates events, from real input',
        \Gtk4\GdkTouchEvent::class => 'only GDK creates events, from real input',
        \Gtk4\GdkTouchpadEvent::class => 'only GDK creates events, from real input',
        \Gtk4\GdkEventSequence::class => 'identity of a touch sequence, only ever seen in an event',
        // A drag needs a real pointer grab; the icon needs the drag.
        \Gtk4\GdkDrag::class => 'needs a pointer grab a headless X server cannot give',
        \Gtk4\GdkDrop::class => 'needs a drag from another client',
        \Gtk4\GtkDragIcon::class => 'GtkDragIcon::get_for_drag() needs a GdkDrag',
        // A render node whose constructor takes what the binding does not speak.
        \Gtk4\GskSubsurfaceNode::class => 'needs a GdkSubsurface, a gpointer GDK keeps private',
        // An animation iterator needs a GTimeVal start time (GLib.TimeVal is not bound).
        \Gtk4\GdkPixbufAnimationIter::class => 'GdkPixbufAnimation::get_iter() takes a GTimeVal, which is not bound',
        // Printing hands these out while a print job runs and nowhere else.
        \Gtk4\GtkPrintContext::class => 'only exists inside GtkPrintOperation\'s draw-page (PrintTest)',
        \Gtk4\GtkPrintSetup::class => 'only GtkPrintDialog::setup_finish() makes one, after a dialog',
        // wrap() only falls back to an interface class when *no* class up the GType chain is
        // registered. Every widget descends from the registered GtkWidget, so the fallbacks
        // for widget interfaces can never be reached - GtkEntry::get_delegate() answers with
        // the bound GtkText, not with GtkEditableObject. They exist for the non-widget case.
        \Gtk4\GtkEditableObject::class => 'every GtkEditable is a widget, so wrap() finds a class',
        \Gtk4\GtkNativeObject::class => 'every GtkNative is a widget, so wrap() finds a class',
        \Gtk4\GtkOrientableObject::class => 'every GtkOrientable is a widget, so wrap() finds a class',
        \Gtk4\GtkRootObject::class => 'every GtkRoot is a widget, so wrap() finds a class',
        // The non-widget fallback that could be reached is not: every GIcon GTK hands out is a
        // GThemedIcon, which is bound, and the others (GFileIcon, GBytesIcon, GEmblemedIcon)
        // are neither bound nor buildable without a GFile.
        \Gtk4\GIconObject::class => 'every GIcon a bound call answers is a GThemedIcon, which wrap() finds',
        // A URI scheme request only exists inside the handler WebKitWebContext::register_uri_scheme()
        // runs for a navigation to that scheme; nothing outside it can hold one.
        \Gtk4\WebKitURISchemeRequest::class => 'only exists inside a registered URI scheme handler',
        \Gtk4\GtkScrollableObject::class => 'every GtkScrollable is a widget, so wrap() finds a class',
        \Gtk4\GtkStyleProviderObject::class => 'the only style providers PHP can reach are bound',
        \Gtk4\GAsyncResultObject::class => 'every GAsyncResult PHP sees is a bound GTask',
        \Gtk4\GActionObject::class => 'every GAction PHP can reach is a bound GSimpleAction',
        \Gtk4\GActionGroupObject::class => 'the action groups PHP can reach are bound',
        \Gtk4\GActionMapObject::class => 'the action maps PHP can reach are bound',
        // A live bus connection is a process-wide singleton with exit-on-close set: the sweeps'
        // acceptable garbage (null for a ?GCancellable) would close() it and end the process.
        // GDBusTest drives the surface by hand on a GTestDBus.
        \Gtk4\GDBusConnection::class => 'the session-bus singleton: close() under the sweeps ends the process',
        \Gtk4\GDBusProxy::class => 'needs a bus and a peer; every call is a round trip GDBusTest makes on purpose',
        \Gtk4\GDBusMethodInvocation::class => 'only exists inside the method-call handler of an exported object',
        // WebKit hands these out inside a signal while a page does something - asks for a
        // permission, submits a form, opens a chooser - and nowhere else; a headless test page
        // that never loads from the network cannot make it do any of that.
        \Gtk4\WebKitAuthenticationRequest::class => 'only WebKitWebView::authenticate hands one out',
        \Gtk4\WebKitAutomationSession::class => 'only WebKitWebContext::automation-started hands one out',
        \Gtk4\WebKitColorChooserRequest::class => 'only WebKitWebView::run-color-chooser hands one out',
        \Gtk4\WebKitFileChooserRequest::class => 'only WebKitWebView::run-file-chooser hands one out',
        \Gtk4\WebKitFormSubmissionRequest::class => 'only WebKitWebView::submit-form hands one out',
        \Gtk4\WebKitHitTestResult::class => 'only WebKitWebView::mouse-target-changed hands one out (real input)',
        \Gtk4\WebKitNotification::class => 'only WebKitWebView::show-notification hands one out',
        \Gtk4\WebKitOptionMenu::class => 'only WebKitWebView::show-option-menu hands one out',
        \Gtk4\WebKitOptionMenuItem::class => 'only a WebKitOptionMenu holds them',
        \Gtk4\WebKitScriptDialog::class => 'only WebKitWebView::script-dialog hands one out',
        \Gtk4\WebKitScriptMessageReply::class => 'only ::script-message-with-reply-received hands one out',
        \Gtk4\WebKitNavigationAction::class => 'only a WebKitNavigationPolicyDecision carries one',
        \Gtk4\WebKitPolicyDecision::class => 'only WebKitWebView::decide-policy hands one out',
        \Gtk4\WebKitNavigationPolicyDecision::class => 'only WebKitWebView::decide-policy hands one out',
        \Gtk4\WebKitResponsePolicyDecision::class => 'only WebKitWebView::decide-policy hands one out',
        \Gtk4\WebKitPermissionStateQuery::class => 'only WebKitWebView::query-permission-state hands one out',
        \Gtk4\WebKitClipboardPermissionRequest::class => 'only WebKitWebView::permission-request hands one out',
        \Gtk4\WebKitDeviceInfoPermissionRequest::class => 'only WebKitWebView::permission-request hands one out',
        \Gtk4\WebKitGeolocationPermissionRequest::class => 'only WebKitWebView::permission-request hands one out',
        \Gtk4\WebKitMediaKeySystemPermissionRequest::class => 'only WebKitWebView::permission-request hands one out',
        \Gtk4\WebKitNotificationPermissionRequest::class => 'only WebKitWebView::permission-request hands one out',
        \Gtk4\WebKitPointerLockPermissionRequest::class => 'only WebKitWebView::permission-request hands one out',
        \Gtk4\WebKitUserMediaPermissionRequest::class => 'only WebKitWebView::permission-request hands one out',
        \Gtk4\WebKitWebsiteDataAccessPermissionRequest::class => 'only WebKitWebView::permission-request hands one out',
        \Gtk4\WebKitXRPermissionRequest::class => 'only WebKitWebView::permission-request hands one out',
        \Gtk4\WebKitPermissionRequestObject::class => 'every permission request WebKit makes is a bound class',
        // The result of a finished load or of an asynchronous fetch, which the factory cannot wait for.
        \Gtk4\WebKitBackForwardListItem::class => 'needs a finished load (WebKitWebView::load-changed)',
        \Gtk4\WebKitWebResource::class => 'needs a finished load (WebKitWebView::get_main_resource())',
        \Gtk4\WebKitURIResponse::class => 'needs a finished load (WebKitWebResource::get_response())',
        \Gtk4\WebKitWebsiteData::class => 'only WebKitWebsiteDataManager::fetch_finish() hands them out',
        \Gtk4\WebKitUserContentFilter::class => 'only WebKitUserContentFilterStore::save_finish() hands one out',
        \Gtk4\WebKitITPFirstParty::class => 'only WebKitWebsiteDataManager::get_itp_summary_finish() hands them out',
        \Gtk4\WebKitITPThirdParty::class => 'only WebKitWebsiteDataManager::get_itp_summary_finish() hands them out',
        \Gtk4\WebKitDownload::class => 'WebKitWebView::download_uri() starts a network transfer the suite must not',
    ];

    /**
     * Classes the factory builds only where the machine has what they need, with what that is.
     * A branch answers null when it is missing, and {@see GtkInstancesTest} skips the class with
     * this reason instead of failing the guard that every other class is swept or excused.
     *
     * @var array<string, string>
     */
    public const ENVIRONMENTAL = [
        \Gtk4\GTlsCertificate::class => 'needs a GIO TLS backend (Debian/Ubuntu: glib-networking)',
        \Gtk4\GdkToplevelObject::class => 'its window-manager requests are real off X11 (tests/run.sh forces'
            . ' GDK_BACKEND=x11, where Xvfb has no manager to answer them): on the Windows desktop the'
            . ' sweeps\' invented calls on a standalone toplevel surface left GDK in a state that killed'
            . ' the next window presented (an access violation in VfuncTest, 2026-09-11)',
    ];

    /**
     * Owners of handles that are only valid while the object they came from lives - the
     * notebook behind a page, the layout manager behind a layout child. Held until
     * {@see release()}, which both sweeps call from tearDown().
     *
     * @var list<object>
     */
    private static array $owners = [];

    /** Drop the pinned owners. Called from tearDown(), so a sweep never leaks a window. */
    public static function release(): void
    {
        foreach (self::$owners as $owner) {
            if ($owner instanceof \Gtk4\GtkWindow) {
                $owner->destroy();
            }
        }
        self::$owners = [];
    }

    /**
     * An instance of $class, or null when nothing can build one.
     *
     * @param class-string $class
     */
    public static function make(string $class): ?object
    {
        return match ($class) {
            \Gtk4\GtkApplication::class => new \Gtk4\GtkApplication(null, 1 << 5),
            \Gtk4\GApplication::class => new \Gtk4\GApplication(null, 1 << 5),
            \Gtk4\GSimpleAction::class => new \Gtk4\GSimpleAction('a', 's'),
            \Gtk4\PhpValue::class => new \Gtk4\PhpValue('v'),
            \Gtk4\GdkTexture::class => \Gtk4\GdkTexture::new_from_bytes(PngFixture::red(2, 1)),
            // GSK: a render node is built from its parts (the base class through a colour node),
            // a renderer through the cairo one, a path through its builder.
            \Gtk4\GskRenderNode::class => self::colorNode(),
            \Gtk4\GskColorNode::class => self::colorNode(),
            \Gtk4\GskOpacityNode::class => new \Gtk4\GskOpacityNode(self::colorNode(), 0.5),
            \Gtk4\GskBlurNode::class => new \Gtk4\GskBlurNode(self::colorNode(), 2.0),
            \Gtk4\GskClipNode::class => new \Gtk4\GskClipNode(self::colorNode(), self::rect()),
            \Gtk4\GskRoundedClipNode::class => new \Gtk4\GskRoundedClipNode(
                self::colorNode(),
                self::rounded(),
            ),
            \Gtk4\GskContainerNode::class => new \Gtk4\GskContainerNode([self::colorNode(), self::colorNode()]),
            \Gtk4\GskTransformNode::class => new \Gtk4\GskTransformNode(self::colorNode(), new \Gtk4\GskTransform()),
            \Gtk4\GskDebugNode::class => new \Gtk4\GskDebugNode(self::colorNode(), 'debug'),
            \Gtk4\GskColorMatrixNode::class => new \Gtk4\GskColorMatrixNode(
                self::colorNode(),
                self::colorMatrix(),
                \Gtk4\GrapheneVec4::zero(),
            ),
            \Gtk4\GskCairoNode::class => new \Gtk4\GskCairoNode(self::rect()),
            // Values with static constructors, or constructor arguments the generic path cannot
            // invent (a time zone, a tab count, a title, an icon name, a certificate).
            \Gtk4\GrapheneMatrix::class => self::colorMatrix(),
            \Gtk4\GrapheneVec2::class => \Gtk4\GrapheneVec2::zero(),
            \Gtk4\GrapheneVec3::class => \Gtk4\GrapheneVec3::zero(),
            \Gtk4\GrapheneVec4::class => \Gtk4\GrapheneVec4::zero(),
            \Gtk4\GTimeZone::class => \Gtk4\GTimeZone::new_utc(),
            \Gtk4\GDateTime::class => \Gtk4\GDateTime::new_from_unix_utc(0),
            \Gtk4\GNotification::class => new \Gtk4\GNotification('title'),
            \Gtk4\GThemedIcon::class => new \Gtk4\GThemedIcon('edit-copy'),
            \Gtk4\GTlsCertificate::class => self::certificate(),
            \Gtk4\GtkAspectFrame::class => new \Gtk4\GtkAspectFrame(0.5, 0.5, 1.0, false),
            // The abstract streams through the memory-backed ones, the way the sweeps build
            // every abstract base: the members the base declares are the ones under test.
            \Gtk4\GInputStream::class => \Gtk4\GMemoryInputStream::new_from_bytes('bytes'),
            \Gtk4\GOutputStream::class => \Gtk4\GMemoryOutputStream::new_resizable(),
            // The seat and its pointer device, which the headless X server does provide.
            \Gtk4\GdkSeat::class => self::display()->get_default_seat(),
            \Gtk4\GdkDevice::class => self::display()->get_default_seat()?->get_pointer(),
            // Pango: a layout the way a widget has one, the interned default language, tab stops.
            \Gtk4\PangoLayout::class => self::pin(new \Gtk4\GtkLabel('x'))->get_layout(),
            \Gtk4\PangoLanguage::class => \Gtk4\PangoLanguage::get_default(),
            \Gtk4\PangoTabArray::class => new \Gtk4\PangoTabArray(2, true),
            \Gtk4\GskTextNode::class => self::textNode(),
            // Pango: the backend owns the font map, fonts, families, faces and font sets; a
            // widget's context is where all of them come from.
            \Gtk4\PangoFontMap::class => self::pangoFont()->get_font_map(),
            \Gtk4\PangoFont::class => self::pangoFont(),
            \Gtk4\PangoFontFace::class => self::pangoFont()->get_face(),
            \Gtk4\PangoFontFamily::class => self::pangoFont()->get_face()->get_family(),
            \Gtk4\PangoFontset::class => self::pangoContext()->load_fontset(
                \Gtk4\PangoFontDescription::from_string('Sans 12'),
                \Gtk4\PangoLanguage::get_default(),
            ),
            \Gtk4\PangoFontMetrics::class => self::pangoFont()->get_metrics(null),
            \Gtk4\GskCrossFadeNode::class => new \Gtk4\GskCrossFadeNode(self::colorNode(), self::colorNode(), 0.5),
            \Gtk4\GskBlendNode::class => new \Gtk4\GskBlendNode(
                self::colorNode(),
                self::colorNode(),
                \Gtk4\GskBlendMode::Multiply,
            ),
            \Gtk4\GskMaskNode::class => new \Gtk4\GskMaskNode(
                self::colorNode(),
                self::colorNode(),
                \Gtk4\GskMaskMode::Alpha,
            ),
            \Gtk4\GskRepeatNode::class => new \Gtk4\GskRepeatNode(self::rect(), self::colorNode()),
            \Gtk4\GskBorderNode::class => new \Gtk4\GskBorderNode(
                self::rounded(),
                [1.0, 1.0, 1.0, 1.0],
                [self::rgba(), self::rgba(), self::rgba(), self::rgba()],
            ),
            \Gtk4\GskInsetShadowNode::class => new \Gtk4\GskInsetShadowNode(
                self::rounded(),
                self::rgba(),
                1.0,
                1.0,
                1.0,
                2.0,
            ),
            \Gtk4\GskOutsetShadowNode::class => new \Gtk4\GskOutsetShadowNode(
                self::rounded(),
                self::rgba(),
                1.0,
                1.0,
                1.0,
                2.0,
            ),
            \Gtk4\GskShadowNode::class => new \Gtk4\GskShadowNode(self::colorNode(), [[self::rgba(), 1.0, 1.0, 2.0]]),
            \Gtk4\GskLinearGradientNode::class => new \Gtk4\GskLinearGradientNode(
                self::rect(),
                new \Gtk4\GraphenePoint(0.0, 0.0),
                new \Gtk4\GraphenePoint(4.0, 4.0),
                self::stops(),
            ),
            \Gtk4\GskRepeatingLinearGradientNode::class => new \Gtk4\GskRepeatingLinearGradientNode(
                self::rect(),
                new \Gtk4\GraphenePoint(0.0, 0.0),
                new \Gtk4\GraphenePoint(4.0, 4.0),
                self::stops(),
            ),
            \Gtk4\GskRadialGradientNode::class => new \Gtk4\GskRadialGradientNode(
                self::rect(),
                new \Gtk4\GraphenePoint(2.0, 2.0),
                2.0,
                2.0,
                0.0,
                1.0,
                self::stops(),
            ),
            \Gtk4\GskRepeatingRadialGradientNode::class => new \Gtk4\GskRepeatingRadialGradientNode(
                self::rect(),
                new \Gtk4\GraphenePoint(2.0, 2.0),
                2.0,
                2.0,
                0.0,
                1.0,
                self::stops(),
            ),
            \Gtk4\GskConicGradientNode::class => new \Gtk4\GskConicGradientNode(
                self::rect(),
                new \Gtk4\GraphenePoint(2.0, 2.0),
                0.0,
                self::stops(),
            ),
            \Gtk4\GskTextureNode::class => new \Gtk4\GskTextureNode(
                \Gtk4\GdkTexture::new_from_bytes(PngFixture::red(2, 1)),
                self::rect(),
            ),
            \Gtk4\GskTextureScaleNode::class => new \Gtk4\GskTextureScaleNode(
                \Gtk4\GdkTexture::new_from_bytes(PngFixture::red(2, 1)),
                self::rect(),
                \Gtk4\GskScalingFilter::Linear,
            ),
            \Gtk4\GskFillNode::class => new \Gtk4\GskFillNode(
                self::colorNode(),
                self::path(),
                \Gtk4\GskFillRule::Winding,
            ),
            \Gtk4\GskStrokeNode::class => new \Gtk4\GskStrokeNode(
                self::colorNode(),
                self::path(),
                new \Gtk4\GskStroke(1.0),
            ),
            \Gtk4\GskRenderer::class => new \Gtk4\GskCairoRenderer(),
            \Gtk4\GskPath::class => self::path(),
            \Gtk4\GskPathMeasure::class => new \Gtk4\GskPathMeasure(self::path()),
            \Gtk4\GskPathPoint::class => self::path()->get_start_point()
                ?? throw new \RuntimeException('a closed path has a start point'),
            \Gtk4\GskStroke::class => new \Gtk4\GskStroke(1.0),
            \Gtk4\GskRoundedRect::class => self::rounded(),
            // GdkPixbuf: a 2x2 RGBA image, its loader, a format from the registry; the texture
            // bridge from the same PNG fixture the GdkTexture branch uses.
            \Gtk4\GdkPixbuf::class => new \Gtk4\GdkPixbuf(\Gtk4\GdkColorspace::Rgb, true, 8, 2, 2),
            \Gtk4\GdkPixbufFormat::class => \Gtk4\GdkPixbuf::get_formats()[0]
                ?? throw new \RuntimeException('gdk-pixbuf ships at least the PNG loader'),
            \Gtk4\GdkPixbufAnimation::class => self::pixbufAnimation(),
            \Gtk4\GdkMemoryTexture::class => new \Gtk4\GdkMemoryTexture(
                2,
                2,
                \Gtk4\GdkMemoryFormat::R8g8b8a8,
                str_repeat("\xff\x00\x00\xff", 4),
                8,
            ),
            \Gtk4\GdkTextureDownloader::class => new \Gtk4\GdkTextureDownloader(
                \Gtk4\GdkTexture::new_from_bytes(PngFixture::red(2, 1)),
            ),
            // Printing: a named paper size (null is the locale's default), the rest through `new`.
            \Gtk4\GtkPaperSize::class => new \Gtk4\GtkPaperSize(null),
            \Gtk4\GError::class => new \Gtk4\GError('x'),
            \Gtk4\GListStore::class => new \Gtk4\GListStore(),
            \Gtk4\GtkFilter::class, \Gtk4\GtkCustomFilter::class => new \Gtk4\GtkCustomFilter(fn() => true),
            \Gtk4\GtkSorter::class, \Gtk4\GtkCustomSorter::class => new \Gtk4\GtkCustomSorter(fn() => 0),
            \Gtk4\GtkFilterListModel::class, \Gtk4\GtkSortListModel::class,
            \Gtk4\GtkDrawingArea::class => new $class(),
            \Gtk4\GtkWidget::class => new \Gtk4\GtkButton(),   // abstract: exercise through a subclass
            \Gtk4\GParamSpec::class => self::paramSpec(),
            \Gtk4\GObject::class => new \Gtk4\PhpValue('v'),
            \Gtk4\GdkRGBA::class, \Gtk4\GdkRectangle::class, \Gtk4\GMainLoop::class,
            \Gtk4\GtkWindow::class, \Gtk4\GtkButton::class, \Gtk4\GtkLabel::class => new $class(),
            \Gtk4\GtkApplicationWindow::class => new \Gtk4\GtkApplicationWindow(self::registeredApp()),
            \Gtk4\GtkBox::class => new \Gtk4\GtkBox(\Gtk4\GtkOrientation::Horizontal, 0),
            \Gtk4\GtkPaned::class => new \Gtk4\GtkPaned(\Gtk4\GtkOrientation::Horizontal),
            \Gtk4\GtkScale::class => new \Gtk4\GtkScale(\Gtk4\GtkOrientation::Horizontal),
            \Gtk4\GtkAdjustment::class => new \Gtk4\GtkAdjustment(0.0, 0.0, 100.0, 1.0, 10.0, 0.0),
            \Gtk4\GtkSpinButton::class => new \Gtk4\GtkSpinButton(null, 1.0, 0),
            \Gtk4\GtkEntryBuffer::class => new \Gtk4\GtkEntryBuffer('abc', -1),
            \Gtk4\GtkBitset::class => \Gtk4\GtkBitset::new_range(0, 4),
            // Concrete classes whose only obstacle is a required argument the generic path
            // cannot invent (an enum case, a content type). One line each is all it takes to
            // put their whole method surface under the sweeps.
            \Gtk4\GtkSeparator::class => new \Gtk4\GtkSeparator(\Gtk4\GtkOrientation::Horizontal),
            \Gtk4\GtkBoxLayout::class => new \Gtk4\GtkBoxLayout(\Gtk4\GtkOrientation::Horizontal),
            \Gtk4\GtkGesturePan::class => new \Gtk4\GtkGesturePan(\Gtk4\GtkOrientation::Horizontal),
            \Gtk4\GtkSizeGroup::class => new \Gtk4\GtkSizeGroup(\Gtk4\GtkSizeGroupMode::Both),
            \Gtk4\GtkStringObject::class => new \Gtk4\GtkStringObject('x'),
            \Gtk4\GtkTextMark::class => new \Gtk4\GtkTextMark('m', false),
            \Gtk4\GtkDropTarget::class => new \Gtk4\GtkDropTarget('string', 1 << 0),
            \Gtk4\GtkDropTargetAsync::class => new \Gtk4\GtkDropTargetAsync(null, 1 << 0),
            \Gtk4\GtkEventControllerScroll::class => new \Gtk4\GtkEventControllerScroll(1 << 0),
            // Abstract bases, exercised through the plainest concrete subclass.
            \Gtk4\GtkGesture::class => new \Gtk4\GtkGestureClick(),
            \Gtk4\GtkEventController::class => new \Gtk4\GtkEventControllerKey(),
            \Gtk4\GtkLayoutManager::class => new \Gtk4\GtkBoxLayout(\Gtk4\GtkOrientation::Horizontal),
            \Gtk4\GMenuModel::class => new \Gtk4\GMenu(),
            \Gtk4\GdkSnapshot::class => new \Gtk4\GtkSnapshot(),
            // Handles GTK hands out but never lets PHP build - they come from their owner,
            // and the handle is what keeps that owner alive (BOXED_OWNERS for the iter, a
            // plain reference for the page).
            \Gtk4\GtkTextIter::class => new \Gtk4\GtkTextBuffer()->get_start_iter(),
            \Gtk4\GtkStackPage::class => self::pin(new \Gtk4\GtkStack())->add_child(new \Gtk4\GtkButton()),
            \Gtk4\GdkDisplay::class => \Gtk4\GdkDisplay::get_default(),
            \Gtk4\GtkTreeListModel::class => self::treeListModel(),
            \Gtk4\GtkTreeListRow::class => self::treeListModel()->get_child_row(0),
            // The display's own handles: no constructor, one owner that outlives every test.
            \Gtk4\GdkClipboard::class => self::display()->get_clipboard(),
            \Gtk4\GdkSurface::class => \Gtk4\GdkSurface::new_toplevel(self::display()),
            // A toplevel surface is a backend-private GdkSurface subclass; wrap() refines it
            // to the interface's fallback (INTERFACE_FALLBACK_BASE), so the same call builds it.
            \Gtk4\GdkToplevelObject::class => self::toplevelSurface(),
            // never up(): a GTestDBus is bypassed inside this suite (CLAUDE.md "Tests"), but its
            // flags/address getters and add_service_dir() are a surface like any other
            \Gtk4\GTestDBus::class => new \Gtk4\GTestDBus(\Gtk4\GTestDBusFlags::NONE),
            // D-Bus introspection: a node parsed from XML, and what hangs off it
            \Gtk4\GDBusNodeInfo::class => self::dbusNode(),
            \Gtk4\GDBusInterfaceInfo::class => self::dbusNode()->interfaces[0],
            \Gtk4\GDBusMethodInfo::class => self::dbusNode()->interfaces[0]->methods[0],
            \Gtk4\GDBusSignalInfo::class => self::dbusNode()->interfaces[0]->signals[0],
            \Gtk4\GDBusPropertyInfo::class => self::dbusNode()->interfaces[0]->properties[0],
            \Gtk4\GDBusArgInfo::class => self::dbusNode()->interfaces[0]->methods[0]->in_args[0],
            \Gtk4\GdkMonitor::class => self::display()->get_monitors()->get_item(0),
            \Gtk4\GtkIconPaintable::class => \Gtk4\GtkIconTheme::get_for_display(self::display())
                ->lookup_icon('list-add', null, 16, 1, \Gtk4\GtkTextDirection::None, 0),
            \Gtk4\GrapheneRect::class => self::bounds(),
            \Gtk4\CairoSurface::class => \Gtk4\GdkTexture::new_from_bytes(PngFixture::red(2, 1))->download(),
            \Gtk4\CairoContext::class => self::pin(new \Gtk4\GtkSnapshot())->append_cairo(self::bounds()),
            \Gtk4\GdkPaintableObject::class => self::pin(new \Gtk4\GtkSnapshot())->to_paintable(null),
            \Gtk4\GtkNotebookPage::class => self::notebookPage(),
            \Gtk4\GtkLayoutChild::class, \Gtk4\GtkGridLayoutChild::class => self::layoutChild(
                new \Gtk4\GtkGridLayout(),
            ),
            \Gtk4\GtkFixedLayoutChild::class => self::layoutChild(new \Gtk4\GtkFixedLayout()),
            \Gtk4\GtkOverlayLayoutChild::class => self::layoutChild(new \Gtk4\GtkOverlayLayout()),
            \Gtk4\GtkSelectionModelObject::class => self::pin(new \Gtk4\GtkStack())->get_pages(),
            \Gtk4\GListModelObject::class => self::pin(new \Gtk4\GtkBox(\Gtk4\GtkOrientation::Horizontal, 0))
                ->observe_children(),
            \Gtk4\GtkBuilderScopeObject::class => self::pin(new \Gtk4\GtkBuilder())->get_scope(),
            \Gtk4\GtkCssSection::class => self::cssSection(),
            \Gtk4\GtkListItem::class => self::listItem(),
            // WebKit (--enable-gtk4-webkit): one web view for the whole run - each one starts a
            // web process - and what an application reaches through it; the records with
            // constructor arguments the generic path cannot invent (an enum case, a script).
            \Gtk4\WebKitWebView::class, \Gtk4\WebKitWebViewBase::class => self::webView(),
            \Gtk4\WebKitBackForwardList::class => self::webView()->get_back_forward_list(),
            \Gtk4\WebKitFindController::class => self::webView()->get_find_controller(),
            \Gtk4\WebKitWebInspector::class => self::webView()->get_inspector(),
            \Gtk4\WebKitEditorState::class => self::webView()->get_editor_state(),
            \Gtk4\WebKitWindowProperties::class => self::webView()->get_window_properties(),
            \Gtk4\WebKitWebViewSessionState::class => self::webView()->get_session_state(),
            \Gtk4\WebKitCookieManager::class => self::webView()->get_network_session()->get_cookie_manager(),
            \Gtk4\WebKitWebsiteDataManager::class => self::webView()->get_network_session()->get_website_data_manager(),
            \Gtk4\WebKitFaviconDatabase::class => self::faviconDatabase(),
            // The abstract input-method context has no bound concrete subclass; a PHP one is the
            // plainest there is. A scheme response is a stream with a length.
            \Gtk4\WebKitInputMethodContext::class => self::inputMethodContext(),
            \Gtk4\WebKitURISchemeResponse::class => new \Gtk4\WebKitURISchemeResponse(
                \Gtk4\GMemoryInputStream::new_from_bytes('<p>hi</p>'),
                -1,
            ),
            \Gtk4\SoupCookie::class => new \Gtk4\SoupCookie('name', 'value', 'example.test', '/', -1),
            \Gtk4\SoupMessageHeaders::class => new \Gtk4\SoupMessageHeaders(\Gtk4\SoupMessageHeadersType::Request),
            \Gtk4\WebKitSecurityManager::class => self::webView()->get_context()->get_security_manager(),
            \Gtk4\WebKitGeolocationManager::class => self::webView()->get_context()->get_geolocation_manager(),
            \Gtk4\WebKitPrintOperation::class => new \Gtk4\WebKitPrintOperation(self::webView()),
            \Gtk4\WebKitContextMenuItem::class => \Gtk4\WebKitContextMenuItem::new_separator(),
            \Gtk4\WebKitCredential::class => new \Gtk4\WebKitCredential(
                'user',
                'secret',
                \Gtk4\WebKitCredentialPersistence::None,
            ),
            \Gtk4\WebKitFeatureList::class => \Gtk4\WebKitSettings::get_all_features(),
            \Gtk4\WebKitFeature::class => \Gtk4\WebKitSettings::get_all_features()->get(0),
            \Gtk4\WebKitGeolocationPosition::class => new \Gtk4\WebKitGeolocationPosition(52.5, 13.4, 10.0),
            \Gtk4\WebKitInputMethodUnderline::class => new \Gtk4\WebKitInputMethodUnderline(0, 1),
            \Gtk4\WebKitSecurityOrigin::class => new \Gtk4\WebKitSecurityOrigin('https', 'example.org', 443),
            \Gtk4\WebKitURIRequest::class => new \Gtk4\WebKitURIRequest('about:blank'),
            \Gtk4\WebKitUserContentFilterStore::class => new \Gtk4\WebKitUserContentFilterStore(
                sys_get_temp_dir() . '/php-gtk4-filters-' . getmypid(),
            ),
            \Gtk4\WebKitUserMessage::class => new \Gtk4\WebKitUserMessage('ping'),
            \Gtk4\WebKitUserScript::class => new \Gtk4\WebKitUserScript(
                'window.phpgtk = 1;',
                \Gtk4\WebKitUserContentInjectedFrames::AllFrames,
                \Gtk4\WebKitUserScriptInjectionTime::Start,
            ),
            \Gtk4\WebKitUserStyleSheet::class => new \Gtk4\WebKitUserStyleSheet(
                'body { color: red; }',
                \Gtk4\WebKitUserContentInjectedFrames::AllFrames,
                \Gtk4\WebKitUserStyleLevel::User,
            ),
            \Gtk4\JSCValue::class => \Gtk4\JSCValue::new_number(self::jsContext(), 1.0),
            \Gtk4\JSCException::class => new \Gtk4\JSCException(self::jsContext(), 'x'),
            default => self::plain($class),
        };
    }

    private static ?\Gtk4\WebKitWebView $webView = null;

    /**
     * The one web view of the run, for the WebKit branches above. A web view starts a web
     * process and a network process; one is plenty for sweeping getters and argument checks,
     * and it is never destroyed, like {@see display()}'s handles.
     */
    private static function webView(): \Gtk4\WebKitWebView
    {
        return self::$webView ??= new \Gtk4\WebKitWebView();
    }

    private static ?\Gtk4\JSCContext $jsContext = null;

    /** The one JavaScript context of the run, for values and exceptions. */
    private static function jsContext(): \Gtk4\JSCContext
    {
        return self::$jsContext ??= new \Gtk4\JSCContext();
    }

    /**
     * Everything the table does not name: any class that takes no required constructor
     * argument is built plainly, so a newly bound class is covered the day it lands.
     *
     * @param class-string $class
     */
    public static function plain(string $class): ?object
    {
        $rc = new ReflectionClass($class);
        $ctor = $rc->getConstructor();
        if (!$rc->isInstantiable() || ($ctor !== null && $ctor->getNumberOfRequiredParameters() > 0)) {
            return null;
        }

        try {
            return $rc->newInstance();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Keep $owner alive until release(): the handles below are only meaningful while the
     * object that produced them exists.
     *
     * @template T of object
     * @param T $owner
     * @return T
     */
    private static function pin(object $owner): object
    {
        self::$owners[] = $owner;
        return $owner;
    }

    /**
     * A fresh application past `startup`. GTK refuses to take an application window before then
     * ("New application windows must be added after the GApplication::startup signal has been
     * emitted"), and register() is what emits it without entering run().
     *
     * The id has to be unique per instance, and cannot be the `null` every other application in
     * this file uses: registering exports the application on the session bus, an anonymous one
     * at /org/gtk/Application/anonymous, and a second anonymous registration in the same process
     * fails with "An object is already exported for the interface org.gtk.Application". A
     * per-instance id keeps every sweep row on its own application instead of sharing one.
     */
    /** A still image as an animation: gdk_pixbuf_animation_new_from_file() on the PNG fixture. */
    private static function pixbufAnimation(): \Gtk4\GdkPixbufAnimation
    {
        $file = sys_get_temp_dir() . '/php-gtk4-instances-' . getmypid() . '.png';
        file_put_contents($file, PngFixture::red(2, 1));
        try {
            return \Gtk4\GdkPixbufAnimation::new_from_file($file);
        } finally {
            unlink($file);
        }
    }

    /** A 4x4 rectangle at the origin, the bounds every GSK sample node uses. */
    private static function rect(): \Gtk4\GrapheneRect
    {
        return \Gtk4\GrapheneRect::alloc()->init(0.0, 0.0, 4.0, 4.0);
    }

    private static function rounded(): \Gtk4\GskRoundedRect
    {
        return new \Gtk4\GskRoundedRect(self::rect(), 1.0, 1.0, 1.0, 1.0);
    }

    private static function rgba(): \Gtk4\GdkRGBA
    {
        return new \Gtk4\GdkRGBA('red');
    }

    /** The identity colour matrix: a colour-matrix node that changes nothing. */
    private static function colorMatrix(): \Gtk4\GrapheneMatrix
    {
        $matrix = \Gtk4\GrapheneMatrix::alloc();
        $matrix->init_identity();
        return $matrix;
    }

    /** A widget's Pango context, the one with a font map behind it; the widget stays pinned. */
    private static function pangoContext(): \Gtk4\PangoContext
    {
        return self::pin(new \Gtk4\GtkLabel('x'))->get_pango_context();
    }

    /** The font the backend loads for "Sans 12". */
    private static function pangoFont(): \Gtk4\PangoFont
    {
        $font = self::pangoContext()->load_font(\Gtk4\PangoFontDescription::from_string('Sans 12'));
        \assert($font !== null, 'Sans is an alias family every font map lists');
        return $font;
    }

    /**
     * A text node the way GTK makes them: GtkSnapshot::append_layout() shapes a label's layout
     * into one - the only shaping this binding has, since pango_shape() and PangoItem are not
     * bound - and answers it as the snapshot's root when there is nothing else in it.
     */
    private static function textNode(): \Gtk4\GskTextNode
    {
        $snapshot = new \Gtk4\GtkSnapshot();
        $snapshot->append_layout(self::pin(new \Gtk4\GtkLabel('Hamburgefonstiv'))->get_layout(), self::rgba());
        $node = $snapshot->to_node();
        \assert($node instanceof \Gtk4\GskTextNode, 'one layout, one line, no decoration: a bare text node');
        return $node;
    }

    /**
     * The certificate GTlsCertificateTest parses, or null where GIO has no TLS backend to parse
     * it with ({@see ENVIRONMENTAL}).
     */
    private static function certificate(): ?\Gtk4\GTlsCertificate
    {
        try {
            return \Gtk4\GTlsCertificate::new_from_pem(GTlsCertificateTest::pem(), -1);
        } catch (\Gtk4\GError $e) {
            if (str_contains($e->getMessage(), 'TLS support is not available')) {
                return null;
            }
            throw $e;
        }
    }

    /**
     * A standalone toplevel surface for the sweeps, on X11 only (ENVIRONMENTAL says why): the
     * sweeps call GdkToplevel's window-manager requests with invented arguments, which reach
     * nobody under Xvfb and a real desktop otherwise. On the Windows runner the first window
     * presented after them died with an access violation (VfuncTest::testDrawingArea,
     * 2026-09-11); the suite with only this class's sweeps excluded ran clean, and the cell that
     * kept begin_resize()/begin_move() but dropped focus(), set_decorated(), set_deletable(),
     * set_icon_list(), set_title(), set_startup_id() and inhibit_system_shortcuts() passed too,
     * so it is one of those acting on a surface GTK never mapped - not isolated further, since
     * each round costs the Windows matrix.
     */
    private static function toplevelSurface(): ?\Gtk4\GdkToplevelObject
    {
        if (getenv('GDK_BACKEND') !== 'x11') {
            return null;
        }
        $surface = \Gtk4\GdkSurface::new_toplevel(self::display());

        return $surface instanceof \Gtk4\GdkToplevelObject ? $surface : null;
    }

    /** A PHP subclass: the plainest concrete input-method context there is. */
    private static function inputMethodContext(): \Gtk4\WebKitInputMethodContext
    {
        return new class extends \Gtk4\WebKitInputMethodContext {
            // nothing overridden: the sweep wants the base class's own members
        };
    }

    /** The favicon database exists once favicons are switched on for the data manager. */
    private static function faviconDatabase(): ?\Gtk4\WebKitFaviconDatabase
    {
        $manager = self::webView()->get_network_session()->get_website_data_manager();
        $manager->set_favicons_enabled(true);
        return $manager->get_favicon_database();
    }

    /** The plainest render node: a red square. */
    private static function colorNode(): \Gtk4\GskColorNode
    {
        return new \Gtk4\GskColorNode(self::rgba(), self::rect());
    }

    /** @return list<array{float, \Gtk4\GdkRGBA}> two colour stops, red to blue */
    private static function stops(): array
    {
        return [[0.0, self::rgba()], [1.0, new \Gtk4\GdkRGBA('blue')]];
    }

    /** A closed triangle. */
    private static function path(): \Gtk4\GskPath
    {
        $builder = new \Gtk4\GskPathBuilder();
        $builder->move_to(0.0, 0.0);
        $builder->line_to(4.0, 0.0);
        $builder->line_to(4.0, 4.0);
        $builder->close();
        return $builder->to_path();
    }

    private static function registeredApp(): \Gtk4\GtkApplication
    {
        /** @var int $n */
        static $n = 0;
        $app = self::pin(new \Gtk4\GtkApplication(
            sprintf('org.phpgtk4.tests.p%d.n%d', getmypid(), $n++),
            1 << 5,
        ));
        $app->register(null);
        return $app;
    }

    private static function display(): \Gtk4\GdkDisplay
    {
        $display = \Gtk4\GdkDisplay::get_default();
        assert($display instanceof \Gtk4\GdkDisplay);
        return $display;
    }

    /** A rectangle the way a widget produces one, for the snapshot APIs that need bounds. */
    private static function bounds(): \Gtk4\GrapheneRect
    {
        $button = new \Gtk4\GtkButton();
        $rect = $button->compute_bounds($button);
        assert($rect instanceof \Gtk4\GrapheneRect);
        return $rect;
    }

    private static function notebookPage(): \Gtk4\GtkNotebookPage
    {
        $notebook = self::pin(new \Gtk4\GtkNotebook());
        $child = new \Gtk4\GtkButton();
        $notebook->append_page($child, null);
        $page = $notebook->get_page($child);
        assert($page !== null);  // null only for a widget that is not a page of the notebook
        return $page;
    }

    /** The per-child state a layout manager keeps - one concrete class per manager. */
    private static function layoutChild(\Gtk4\GtkLayoutManager $manager): \Gtk4\GtkLayoutChild
    {
        $box = self::pin(new \Gtk4\GtkBox(\Gtk4\GtkOrientation::Horizontal, 0));
        $box->set_layout_manager($manager);
        $child = new \Gtk4\GtkButton();
        $box->append($child);
        return $manager->get_layout_child($child);
    }

    /** The section a stylesheet's `parsing-error` points at - CSS is the only way to get one. */
    private static function cssSection(): ?\Gtk4\GtkCssSection
    {
        $provider = self::pin(new \Gtk4\GtkCssProvider());
        $section = null;
        $provider->connect(
            'parsing-error',
            function (\Gtk4\GtkCssProvider $p, \Gtk4\GtkCssSection $s) use (&$section): void {
                $section ??= $s;
            },
        );
        $provider->load_from_string('button { color: ; }');
        return $section;
    }

    /** A list item, as the factory of a live GtkListView is handed one. */
    private static function listItem(): ?\Gtk4\GtkListItem
    {
        $item = null;
        $factory = new \Gtk4\GtkSignalListItemFactory();
        $factory->connect('setup', function (\Gtk4\GtkListItemFactory $f, \Gtk4\GtkListItem $i) use (&$item): void {
            $item ??= $i;
        });
        $view = new \Gtk4\GtkListView(new \Gtk4\GtkSingleSelection(new \Gtk4\GtkStringList(['a'])), $factory);
        $window = self::pin(new \Gtk4\GtkWindow());
        $window->set_child($view);
        $window->present();
        return $item;
    }

    private static function treeListModel(): \Gtk4\GtkTreeListModel
    {
        return new \Gtk4\GtkTreeListModel(
            new \Gtk4\GtkStringList(['a', 'b']),
            false,
            false,
            static fn(): ?\Gtk4\GListModel => null,
        );
    }

    private static function paramSpec(): \Gtk4\GParamSpec
    {
        $w = new \Gtk4\GtkWindow();
        $spec = null;
        $w->connect('notify::title', function (\Gtk4\GObject $o, \Gtk4\GParamSpec $p) use (&$spec): void {
            $spec ??= $p;
        });
        $w->set_title('x');
        $w->destroy();
        assert($spec instanceof \Gtk4\GParamSpec);
        return $spec;
    }

    /** One introspected interface with a method, a signal and a property, for the record sweeps. */
    private static function dbusNode(): \Gtk4\GDBusNodeInfo
    {
        return \Gtk4\GDBusNodeInfo::new_for_xml(
            '<node><interface name="org.example.Sweep">'
            . '<method name="Echo"><arg type="s" name="text" direction="in"/>'
            . '<arg type="s" name="out" direction="out"/></method>'
            . '<signal name="Ping"><arg type="s" name="what"/></signal>'
            . '<property name="Name" type="s" access="readwrite"/>'
            . '</interface></node>',
        );
    }
}
