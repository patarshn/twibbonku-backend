<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwibbonComment extends Model
{
    use HasFactory;

    public function replies()
    {
        return $this->hasMany(TwibbonComment::class, 'parent_id', 'id');
    }
}
