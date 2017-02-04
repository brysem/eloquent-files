<?php

namespace Bryse\Eloquent\Files\Support;

use Illuminate\Http\UploadedFile;

class FileHelper
{
    protected $file;

    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }

    public function getExtension()
    {
        if (!empty($this->file->guessExtension())) {
            return $this->file->guessExtension();
        }

        if (!empty($this->file->getExtension())) {
            return $this->file->getExtension();
        }

        return pathinfo($this->file->getClientOriginalName(), \PATHINFO_EXTENSION);
    }

    public function getMimeType()
    {
        return $this->file->getMimeType();
    }
}
