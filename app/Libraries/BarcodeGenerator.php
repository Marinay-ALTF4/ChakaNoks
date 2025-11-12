<?php

namespace App\Libraries;

class BarcodeGenerator
{
    public function generateBarcode($prefix = 'BC', $length = 12)
    {
        $timestamp = time();
        $microtime = explode(' ', microtime())[0];
        $random = rand(100000, 999999);
        $barcode = $prefix . $timestamp . substr($microtime, 2, 4) . $random;

        // Ensure the barcode is exactly the desired length
        if (strlen($barcode) > $length) {
            $barcode = substr($barcode, 0, $length);
        } elseif (strlen($barcode) < $length) {
            $barcode = str_pad($barcode, $length, '0', STR_PAD_RIGHT);
        }

        return $barcode;
    }

    public function validateBarcode($barcode)
    {
        // Basic validation: check if it's alphanumeric and within length
        return preg_match('/^[A-Z0-9]{8,20}$/', $barcode);
    }

    public function generateQRCodeData($data)
    {
        // Placeholder for QR code data generation
        // In a real implementation, you might use a library like chillerlan/php-qrcode
        return json_encode([
            'type' => 'inventory_item',
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
}
