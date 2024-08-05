<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Twibbon extends Model
{
    use HasFactory;

    protected $hidden = ['pivot'];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'twibbon_tags', 'twibbon_id', 'tag_id');
    }

    public function twibbon_images()
    {
        return $this->hasMany(TwibbonImage::class);
    }

    public function image()
    {
        return $this->belongsTo(TwibbonImage::class);
    }

}
