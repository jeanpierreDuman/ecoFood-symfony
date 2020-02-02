<?php

namespace App\Services;

use App\Entity\Food;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $foodDirectory;

    public function __construct($foodDirectory)
    {
        $this->foodDirectory = $foodDirectory;
    }

    public function upload(Food $food, UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->foodDirectory, $fileName);
            $food->setPicture($fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $food;
    }

    public function pathExist($path)
    {
        if(file_exists($this->getFoodDirectory($path))) {
            return true;
        }

        return false;
    }

    public function getFoodDirectory($path)
    {
        return $this->foodDirectory . '/' . $path;
    }

    public function showFile($path)
    {
        if(file_exists($path)) {
            return new BinaryFileResponse($path);
        }

        throw new \Exception('file does not exist');
    }
}