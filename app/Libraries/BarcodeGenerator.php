<?php

namespace App\Libraries;

class BarcodeGenerator
{
    private $width;
    private $height;
    private $fontSize;
    
    public function __construct($width = 200, $height = 100, $fontSize = 3)
    {
        $this->width = $width;
        $this->height = $height;
        $this->fontSize = $fontSize;
    }
    
    /**
     * Generate a simple barcode image with text below
     */
    public function generateBarcode($data, $filename = null)
    {
        // Create image with true color support
        $image = imagecreatetruecolor($this->width, $this->height);
        
        // Define colors
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        
        // Fill background with white
        imagefill($image, 0, 0, $white);
        
        // Generate simple barcode pattern
        $this->drawBarcodePattern($image, $data, $black);
        
        // Add text below barcode
        $this->addTextBelow($image, $data, $black);
        
        // Output or save image
        if ($filename) {
            imagepng($image, $filename);
        } else {
            // Set header for direct output
            header('Content-Type: image/png');
            imagepng($image);
        }
        
        // Clean up
        imagedestroy($image);
        
        return $filename;
    }
    
    /**
     * Draw a simple barcode pattern
     */
    private function drawBarcodePattern($image, $data, $color)
    {
        $barWidth = 3;
        $barHeight = 50;
        $startX = 30;
        $startY = 15;
        
        // Convert data to binary pattern (simple representation)
        $pattern = $this->dataToPattern($data);
        
        $x = $startX;
        foreach ($pattern as $bar) {
            if ($bar) {
                // Draw vertical bar - make it thicker and more visible
                imagefilledrectangle($image, $x, $startY, $x + $barWidth - 1, $startY + $barHeight, $color);
            }
            $x += $barWidth;
        }
        
        // Add border around the barcode area for better visibility
        imagerectangle($image, $startX - 3, $startY - 3, $x - 1, $startY + $barHeight + 3, $color);
        
        // Add quiet zones (empty spaces at start and end)
        imagefilledrectangle($image, 10, $startY, $startX - 1, $startY + $barHeight, imagecolorallocate($image, 255, 255, 255));
        imagefilledrectangle($image, $x, $startY, $this->width - 10, $startY + $barHeight, imagecolorallocate($image, 255, 255, 255));
    }
    
    /**
     * Convert data to a simple binary pattern
     */
    private function dataToPattern($data)
    {
        $pattern = [];
        
        // Add start pattern (Code 128 style)
        $pattern = array_merge($pattern, [1, 0, 1, 1, 0, 0, 1, 0, 1, 0]);
        
        // Convert each character to pattern
        for ($i = 0; $i < strlen($data); $i++) {
            $char = ord($data[$i]);
            
            // Create more varied pattern for better visibility
            $charPattern = [];
            for ($j = 0; $j < 6; $j++) {
                $charPattern[] = ($char + $j) % 2;
            }
            $pattern = array_merge($pattern, $charPattern);
        }
        
        // Add end pattern
        $pattern = array_merge($pattern, [1, 0, 1, 1, 0, 0, 1, 0, 1, 0]);
        
        return $pattern;
    }
    
    /**
     * Add text below the barcode
     */
    private function addTextBelow($image, $data, $color)
    {
        $fontWidth = imagefontwidth($this->fontSize);
        $fontHeight = imagefontheight($this->fontSize);
        
        // Calculate text position (centered)
        $textWidth = $fontWidth * strlen($data);
        $textX = ($this->width - $textWidth) / 2;
        $textY = $this->height - $fontHeight - 8; // More space from bottom
        
        // Add text with background for better visibility
        $bgColor = imagecolorallocate($image, 255, 255, 255);
        imagefilledrectangle($image, $textX - 2, $textY - 2, $textX + $textWidth + 2, $textY + $fontHeight + 2, $bgColor);
        
        // Add text
        imagestring($image, $this->fontSize, $textX, $textY, $data, $color);
    }
    
    /**
     * Generate barcode and return as base64 string
     */
    public function generateBase64($data)
    {
        ob_start();
        $this->generateBarcode($data);
        $imageData = ob_get_contents();
        ob_end_clean();
        
        return 'data:image/png;base64,' . base64_encode($imageData);
    }
    
    /**
     * Save barcode to file and return filename
     */
    public function saveBarcode($data, $directory = 'writable/barcodes/')
    {
        // Create directory if it doesn't exist
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        $filename = $directory . 'barcode_' . $data . '_' . time() . '.png';
        $this->generateBarcode($data, $filename);
        
        return $filename;
    }
}
