<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GError;
use Gtk4\GTlsCertificate;

/**
 * An X.509 certificate: what a web view's `get_tls_info()` answers with, what a
 * `WebKitCredential` can carry and what a network session can be told to accept for a host.
 *
 * `GTlsCertificate` is abstract in GIO - the concrete class belongs to the TLS backend - so PHP
 * builds one through the factories rather than with `new`, and `wrap()` resolves whatever the
 * backend hands back to this class. The fixture below is a self-signed certificate generated for
 * this test alone; it carries no private key and is good for nothing but being parsed.
 */
final class GTlsCertificateTest extends GtkTestCase
{
    /** A self-signed certificate for php-gtk4.test, valid until 2126. Certificate only. */
    private const PEM = <<<'CERT'
            -----BEGIN CERTIFICATE-----
            MIIDZzCCAk+gAwIBAgIUaiQIRTxHxuKLDEKh+Qq0yxOGZRgwDQYJKoZIhvcNAQEL
            BQAwKzERMA8GA1UECgwIcGhwLWd0azQxFjAUBgNVBAMMDXBocC1ndGs0LnRlc3Qw
            IBcNMjYwOTA4MDk0MDUzWhgPMjEyNjA4MTUwOTQwNTNaMCsxETAPBgNVBAoMCHBo
            cC1ndGs0MRYwFAYDVQQDDA1waHAtZ3RrNC50ZXN0MIIBIjANBgkqhkiG9w0BAQEF
            AAOCAQ8AMIIBCgKCAQEAz1KBbacjAWX1IrikkM+RB20K3ng4ZVtAsr2vBbfACPIf
            ie/PRYr4cP/jaFyj09+LdLCmfAVC23zcGhZPgC6j9UhM68XBc3+F76AnSG2LWWdV
            FUDwoj4STv+b+AEs4vQw3KZ9ouctLMU8F7fNFfq2ObkGIAHZQjZ7tCDOKQH68qzq
            lXCMXJHE3dBud46hyemD+NNTYHvRGbUJA82w/LGWL562/q40THceburD1EjVzyD8
            RGMwGXktfijkC6emHprUgxOScUC2awPCgCoRsFSurzzDqOJAQlqT6QRJVAW64/Ue
            4jZ0sZxwDcOY1UlUh3VEyQ3s48Vci0kVjWokJvFUIwIDAQABo4GAMH4wHQYDVR0O
            BBYEFJziLFi+3YIcrPFxwPrNqQ36wNiCMB8GA1UdIwQYMBaAFJziLFi+3YIcrPFx
            wPrNqQ36wNiCMA8GA1UdEwEB/wQFMAMBAf8wKwYDVR0RBCQwIoINcGhwLWd0azQu
            dGVzdIIRd3d3LnBocC1ndGs0LnRlc3QwDQYJKoZIhvcNAQELBQADggEBAHeRyayN
            RbHyMsufAevtMUefvJbyEcapay68/vrKrXrpJxlar1c00oL90ygufXYTo01Rrkxh
            UtQSrhnQrfgynv5hoiV566TMKk5UlnwHsFD04oFtVhU2CIWgrWLZ2lqxr8lapoY6
            MAXNBeKXMB2Ost2ER5B2+onoycsyYKTj7dyKN6JaeEHlMnMKX3bpxvrkfdIQR0mr
            vsrqXNKorkutg/A+Aa9LHy7mXFthcbX9lou7INrCg4nmp1mKvpRZVzR0XUiteyxB
            bff1HoEEw9ejcLth4ASjugDzJI9xiF8lGY4DOHU7i1lvDa39XVVOK1yar0JDQ9DP
            k3Ccym9g2aZZF6E=
            -----END CERTIFICATE-----
        CERT;

    /** The fixture, for tests elsewhere that need a certificate (WebKitWebViewTest). */
    public static function pem(): string
    {
        return self::PEM;
    }

    /**
     * GTlsCertificate is abstract: the concrete class comes from GIO's TLS backend, and without
     * one every call here answers "TLS support is not available". That is the environment, not
     * the binding - Debian/Ubuntu put the backend in `glib-networking`, which a runner installed
     * with --no-install-recommends does not get, and the Windows GTK ships without one. Skip
     * rather than fail, the way a test of an optional feature does (tests/Features.php).
     */
    protected function setUp(): void
    {
        parent::setUp();
        try {
            GTlsCertificate::new_from_pem(self::PEM, -1);
        } catch (GError $e) {
            if (str_contains($e->getMessage(), 'TLS support is not available')) {
                self::markTestSkipped('no GIO TLS backend here (Debian/Ubuntu: glib-networking)');
            }
            throw $e;
        }
    }

    public function testAPemParsesIntoACertificate(): void
    {
        $cert = GTlsCertificate::new_from_pem(self::PEM, -1);

        self::assertInstanceOf(GTlsCertificate::class, $cert);
        self::assertSame('O=php-gtk4,CN=php-gtk4.test', $cert->get_subject_name());
        self::assertSame('O=php-gtk4,CN=php-gtk4.test', $cert->get_issuer_name(), 'self-signed');
    }

    /** The subjectAltName entries come back as strings, though GIO hands them out as GBytes. */
    public function testTheDnsNamesAreStrings(): void
    {
        $cert = GTlsCertificate::new_from_pem(self::PEM, -1);

        self::assertSame(['php-gtk4.test', 'www.php-gtk4.test'], $cert->get_dns_names());
    }

    /** Nothing signed this one, so there is no issuer certificate above it. */
    public function testASelfSignedCertificateHasNoIssuerCertificate(): void
    {
        self::assertNull(GTlsCertificate::new_from_pem(self::PEM, -1)->get_issuer());
    }

    public function testTwoCertificatesOfTheSamePemAreTheSame(): void
    {
        $one = GTlsCertificate::new_from_pem(self::PEM, -1);
        $two = GTlsCertificate::new_from_pem(self::PEM, -1);

        self::assertTrue($one->is_same($two));
    }

    /** A length shorter than the PEM cuts it off mid-certificate, which is a parse error. */
    public function testATruncatedPemIsAGError(): void
    {
        $this->expectException(GError::class);
        GTlsCertificate::new_from_pem(self::PEM, 40);
    }

    public function testTextThatIsNotACertificateIsAGError(): void
    {
        $this->expectException(GError::class);
        GTlsCertificate::new_from_pem('not a certificate', -1);
    }

    public function testAMissingFileIsAGError(): void
    {
        $this->expectException(GError::class);
        GTlsCertificate::new_from_file(sys_get_temp_dir() . '/php-gtk4-no-such-certificate.pem');
    }

    /** The abstract base is the TLS backend's contract: PHP cannot be a certificate. */
    public function testTheClassCannotBeConstructed(): void
    {
        self::assertFalse(
            new \ReflectionMethod(GTlsCertificate::class, '__construct')->isPublic(),
            'GTlsCertificate::__construct() is private: build one with new_from_pem()',
        );
    }
}
