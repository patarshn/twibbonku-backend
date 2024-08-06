<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperCampaign extends Model
{
    use HasFactory;

    public function twibbon()
    {
        return $this->belongsTo(Twibbon::class);
    }
}
