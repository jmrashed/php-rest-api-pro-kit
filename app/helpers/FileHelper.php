<?php

namespace App\Helpers;

class FileHelper
{
    public static function upload($file, $destination = 'uploads')
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return false;
        }

        $uploadDir = UPLOADS_PATH . '/' . $destination;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = uniqid() . '_' . basename($file['name']);
        $filepath = $uploadDir . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $destination . '/' . $filename;
        }

        return false;
    }

    public static function delete($filepath)
    {
        $fullPath = UPLOADS_PATH . '/' . $filepath;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }

    public static function getFileInfo($filepath)
    {
        $fullPath = UPLOADS_PATH . '/' . $filepath;
        if (file_exists($fullPath)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $fullPath);
            finfo_close($finfo);

            return [
                'filename' => basename($fullPath),
                'size' => filesize($fullPath),
                'mime_type' => $mimeType,
                'url' => '/storage/uploads/' . $filepath // Assuming public access via /storage/uploads
            ];
        }
        return null;
    }
}