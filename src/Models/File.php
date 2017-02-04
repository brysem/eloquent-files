<?php

namespace Bryse\Eloquent\Files\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class File extends Model
{
    protected $fillable = ['name', 'description', 'key', 'extension', 'type', 'size', 'order'];

    public static function boot()
    {
        static::deleting(function($model) {
            Storage::delete($model->key);
        });
    }

    public function imageable()
    {
        return $this->morphTo();
    }

    public function scopeImages(Builder $query)
    {
        $query->where('type', 'like', 'image/%');
    }

    public function scopeVideos(Builder $query)
    {
        $query->where('type', 'like', 'video/%');
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->key);
    }

    public function isImage()
    {
        return str_is('image/*', $this->type);
    }

    public function isVideo()
    {
        return str_is('video/*', $this->type);
    }
}
