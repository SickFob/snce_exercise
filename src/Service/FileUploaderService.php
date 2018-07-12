<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\Product;

class FileUploaderService
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @param UploadedFile $file - file to upload
     * @return $fileName - unique file name
     */
    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->getTargetDirectory(), $fileName);
        return $fileName;
    }

    /**
     * @return $targetDirectory - directory to store uploaded files
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    /**
     * @param $fileName - file to delete
     */
    public function deleteUploadedFile($fileName) {
      unlink($this->getTargetDirectory()."/".$fileName);
    }
}