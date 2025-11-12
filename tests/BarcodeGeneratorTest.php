<?php

namespace Tests;

use CodeIgniter\Test\CIUnitTestCase;
use App\Libraries\BarcodeGenerator;

class BarcodeGeneratorTest extends CIUnitTestCase
{
    protected $barcodeGenerator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->barcodeGenerator = new BarcodeGenerator();
    }

    public function testCanGenerateBarcode()
    {
        $barcode = $this->barcodeGenerator->generateBarcode();
        $this->assertIsString($barcode);
        $this->assertEquals(12, strlen($barcode)); // Standard UPC-A length
        $this->assertMatchesRegularExpression('/^[A-Z0-9]+$/', $barcode);
    }

    public function testCanValidateBarcode()
    {
        $validBarcode = 'BC1234567890'; // Valid barcode
        $invalidBarcode = 'bc123'; // Invalid length and case

        $this->assertEquals(1, $this->barcodeGenerator->validateBarcode($validBarcode));
        $this->assertEquals(0, $this->barcodeGenerator->validateBarcode($invalidBarcode));
    }

    public function testCanGenerateQRCodeData()
    {
        $data = 'Test QR Data';
        $qrData = $this->barcodeGenerator->generateQRCodeData($data);

        $this->assertIsString($qrData);
        $this->assertNotEmpty($qrData);
        // QR code data should contain the original data
        $this->assertStringContainsString($data, $qrData);
    }

    public function testBarcodeUniqueness()
    {
        $barcode1 = $this->barcodeGenerator->generateBarcode();
        sleep(1); // Sleep for 1 second to ensure different timestamp
        $barcode2 = $this->barcodeGenerator->generateBarcode();

        $this->assertNotEquals($barcode1, $barcode2);
    }
}
