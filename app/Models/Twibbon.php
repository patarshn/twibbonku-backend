<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Twibbon extends Model
{
    use HasFactory, SoftDeletes;

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

    public function keywords()
    {
        return $this->belongsToMany(Tag::class, 'twibbon_keywords', 'twibbon_id', 'keyword_id');
    }

    public function comments()
    {
        return $this->hasMany(TwibbonComment::class);
    }
    

}
