<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardsCategory extends Model
{
    use HasFactory;

    public function award()
    {
        return $this->belongsTo(Award::class, 'award_id');
    }

    public function artists()
    {
        return $this->hasMany(Artist::class, 'awards_category_id');
    }
}
