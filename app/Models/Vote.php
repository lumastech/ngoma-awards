<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'artists_id',
    ];


    public function artist()
    {
        return $this->belongsTo(Artist::class, 'artists_id');
    }
}
