<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrlHit extends Model
{
    use HasFactory;

    protected $fillable = [
        'url_id',
        'hit_time',
        'hit_number',
    ];

    public function url()
    {
        return $this->belongsTo(Url::class);
    }
}
