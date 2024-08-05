<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;


    protected $hidden = ['pivot'];

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;


    public function twibbons()
    {
        return $this->belongsToMany(Twibbon::class, 'twibbon_tags', 'tag_id', 'twibbon_id');
    }
}
