<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Helpers\FileHelper;

class FileController extends Controller
{
    public function upload()
    {
        if (!isset($_FILES['file'])) {
            return Response::json(['error' => 'No file uploaded'], 400);
        }

        $file = $_FILES['file'];
        $destination = $_POST['destination'] ?? 'general';
        
        $filepath = FileHelper::upload($file, $destination);
        
        if ($filepath) {
            return Response::json([
                'message' => 'File uploaded successfully',
                'file' => FileHelper::getFileInfo($filepath)
            ]);
        }
        
        return Response::json(['error' => 'Upload failed'], 500);
    }

    public function getFile($id)
    {
        $filepath = $_GET['filepath'] ?? null;

        if (!$filepath) {
            return Response::json(['error' => 'Filepath required'], 400);
        }

        $fileInfo = FileHelper::getFileInfo($filepath);

        if ($fileInfo) {
            return Response::json($fileInfo);
        }

        return Response::json(['error' => 'File not found'], 404);
    }

    public function delete($id)
    {
        $filepath = $_POST['filepath'] ?? null;
        
        if (!$filepath) {
            return Response::json(['error' => 'Filepath required'], 400);
        }
        
        if (FileHelper::delete($filepath)) {
            return Response::json(['message' => 'File deleted successfully']);
        }
        
        return Response::json(['error' => 'Delete failed'], 500);
    }
}