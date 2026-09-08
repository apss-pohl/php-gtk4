<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GError;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\GTlsCertificate;

/*
 * Gtk4\GTlsCertificate - an X.509 certificate, read with GIO.
 *
 * The class a web view's get_tls_info() answers with, and what a network session is told to
 * accept for a host. It is abstract in GIO - the TLS backend owns the concrete class - so PHP
 * builds one through the factories: new_from_pem(), new_from_file(), or list_new_from_file()
 * for a bundle. This page reads the certificate authorities the machine itself trusts and walks
 * through them on every click; get_issuer_name() the same as the subject is what "root" means.
 *
 *   bin/php-gtk4 examples/demo.php GTlsCertificate
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GTlsCertificate',
    'an X.509 certificate: the roots this machine trusts',
    function (GtkWindow $win): GtkWidget {
        // Where Debian and Ubuntu keep the concatenated trust store; a distribution that does
        // not, or a machine without it, gets the message instead of a page that lies.
        $bundle = '/etc/ssl/certs/ca-certificates.crt';
        $certificates = [];
        $failure = null;
        try {
            $certificates = is_file($bundle) ? GTlsCertificate::list_new_from_file($bundle) : [];
        } catch (GError $e) {
            $failure = $e->getMessage();
        }

        $label = Demo::label();
        $at = 0;
        $render = function () use (&$at, $certificates, $label, $bundle, $failure): void {
            if ($certificates === []) {
                $label->set_markup(sprintf(
                    "<span size=\"x-large\"><b>no trust store to read</b></span>\n\n<small>%s</small>",
                    htmlspecialchars($failure ?? "$bundle is not on this machine"),
                ));
                return;
            }
            $cert = $certificates[$at % count($certificates)];
            $names = $cert->get_dns_names();
            $label->set_markup(sprintf(
                "<span size=\"x-large\"><b>%s</b></span>\n\n"
                . "<small>issued by</small>\n<tt>%s</tt>\n\n"
                . "<small>%s · names: %s</small>\n\n<small>click for the next one</small>",
                htmlspecialchars((string) $cert->get_subject_name()),
                htmlspecialchars((string) $cert->get_issuer_name()),
                $cert->get_issuer_name() === $cert->get_subject_name()
                    ? 'a root: it signed itself'
                    : 'signed by another certificate',
                $names === [] ? 'none' : htmlspecialchars(implode(', ', $names)),
            ));
            Demo::status(sprintf(
                '%d of %d certificates in %s',
                ($at % count($certificates)) + 1,
                count($certificates),
                $bundle,
            ));
        };

        $button = new GtkButton();
        $button->set_child($label);
        $button->connect('clicked', function () use (&$at, $render): void {
            $at++;
            $render();
        });
        $render();
        return $button;
    },
    520,
    300,
);
