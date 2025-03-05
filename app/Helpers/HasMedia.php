<?php

namespace App\Helpers;

use Awcodes\Curator\Models\Media;

trait HasMedia
{
    public function image(){
        return $this->hasOne(Media::class, 'id', 'image_id');
    }

    public function cover_image(){
        return $this->hasOne(Media::class, 'id', 'cover_image_id');
    }
}