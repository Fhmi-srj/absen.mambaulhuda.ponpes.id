<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateQzCertificate extends Command
{
    protected $signature = 'qz:generate-cert';
    protected $description = 'Generate QZ Tray signing certificate';

    public function handle()
    {
        $certDir = storage_path('qz-certs');
        if (!is_dir($certDir)) {
            mkdir($certDir, 0755, true);
        }

        // Generate private key
        $config = [
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'config' => 'C:/laragon/etc/ssl/openssl.cnf', // Laragon OpenSSL config
        ];
        
        $key = openssl_pkey_new($config);
        if (!$key) {
            $this->error('Failed to generate key: ' . openssl_error_string());
            return 1;
        }

        // Export private key
        $privKey = null;
        openssl_pkey_export($key, $privKey, null, $config);
        if (!$privKey) {
            $this->error('Failed to export private key: ' . openssl_error_string());
            return 1;
        }
        file_put_contents($certDir . '/private-key.pem', $privKey);
        $this->info('Private key saved!');

        // Create self-signed cert
        $dn = [
            'commonName' => 'Aktivitas Print Server',
            'organizationName' => 'PPMH',
            'countryName' => 'ID',
        ];
        $csr = openssl_csr_new($dn, $key, $config);
        $cert = openssl_csr_sign($csr, null, $key, 3650, $config);

        // Export certificate
        $certOut = null;
        openssl_x509_export($cert, $certOut);
        file_put_contents($certDir . '/digital-certificate.txt', $certOut);
        $this->info('Certificate saved!');

        $this->info("Done! Files in: $certDir");
        return 0;
    }
}
