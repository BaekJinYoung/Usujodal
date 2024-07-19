<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearlyImage extends Model
{
    use HasFactory;

    protected $fillable = ['year', 'image_path'];
}
