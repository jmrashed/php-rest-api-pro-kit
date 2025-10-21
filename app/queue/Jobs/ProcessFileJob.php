<?php

namespace App\Queue\Jobs;

class ProcessFileJob extends BaseJob
{
    private $filePath;
    private $operation;
    private $options;

    public function __construct(string $filePath, string $operation = 'resize', array $options = [])
    {
        $this->filePath = $filePath;
        $this->operation = $operation;
        $this->options = $options;
        $this->maxRetries = 2;
        $this->delay = 60; // 1 minute delay on retry
    }

    public function handle(): bool
    {
        try {
            if (!file_exists($this->filePath)) {
                throw new \Exception("File not found: {$this->filePath}");
            }

            switch ($this->operation) {
                case 'resize':
                    return $this->resizeImage();
                case 'compress':
                    return $this->compressFile();
                case 'convert':
                    return $this->convertFile();
                default:
                    throw new \Exception("Unknown operation: {$this->operation}");
            }
        } catch (\Exception $e) {
            error_log("File processing job failed: " . $e->getMessage());
            throw $e;
        }
    }

    private function resizeImage(): bool
    {
        $width = $this->options['width'] ?? 800;
        $height = $this->options['height'] ?? 600;
        
        // Simulate image processing
        sleep(2); // Simulate processing time
        
        $outputPath = $this->getOutputPath('_resized');
        
        // In real implementation, use GD or ImageMagick
        copy($this->filePath, $outputPath);
        
        error_log("Image resized: {$this->filePath} -> {$outputPath}");
        return true;
    }

    private function compressFile(): bool
    {
        // Simulate file compression
        sleep(1);
        
        $outputPath = $this->getOutputPath('.zip');
        
        // In real implementation, use ZipArchive
        copy($this->filePath, $outputPath);
        
        error_log("File compressed: {$this->filePath} -> {$outputPath}");
        return true;
    }

    private function convertFile(): bool
    {
        $format = $this->options['format'] ?? 'pdf';
        
        // Simulate file conversion
        sleep(3);
        
        $outputPath = $this->getOutputPath(".{$format}");
        
        copy($this->filePath, $outputPath);
        
        error_log("File converted: {$this->filePath} -> {$outputPath}");
        return true;
    }

    private function getOutputPath(string $suffix): string
    {
        $pathInfo = pathinfo($this->filePath);
        return $pathInfo['dirname'] . '/' . $pathInfo['filename'] . $suffix . '.' . $pathInfo['extension'];
    }

    public function failed(\Exception $exception): void
    {
        error_log("File processing permanently failed for {$this->filePath}: " . $exception->getMessage());
    }
}