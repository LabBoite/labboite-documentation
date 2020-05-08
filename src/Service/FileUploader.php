<?php

namespace App\Service;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function deleteFile($filename)
    {
        
        try {
            if (file_exists ($this->targetDirectory.'/'. $filename) && $filename != null) {
                unlink($this->targetDirectory.'/'. $filename);
            }
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

       
    }
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}