<?php

namespace Bryse\Eloquent\Files\Traits;

use Illuminate\Http\UploadedFile;
use Bryse\Eloquent\Files\Models\File;
use Illuminate\Support\Facades\Storage;
use Bryse\Eloquent\Files\Support\FileHelper;

trait HasFiles
{
    public static function bootHasFiles()
    {
        static::deleting(function($model) {
            $model->files->each(function ($file) {
                $file->delete();
            });
        });
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function upload($files, $folder, $options = 'public')
    {
        if (!is_array($files)) {
            $files = [$files];
        }

        $files = array_filter($files, function ($file) {
            return $file instanceof UploadedFile;
        });

        return array_map(function($file) use ($folder, $options) {
            $helper = new FileHelper($file);

            $file = new File([
                'name'        => $file->getClientOriginalName(),
                'description' => null,
                'key'         => Storage::putFile($folder, $file, $options),
                'type'        => $helper->getMimeType(),
                'extension'   => $helper->getExtension(),
                'size'        => $file->getClientSize()
            ]);

            return $this->files()->save($file);
        }, $files);
    }
}
