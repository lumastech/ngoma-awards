<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    use HasFactory;

    public function awardsCategory()
    {
        return $this->belongsTo(AwardsCategory::class, 'awards_category_id');
    }
}
