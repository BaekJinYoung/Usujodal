<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'mobile_title',
        'subtitle',
        'mobile_subtitle',
        'details',
        'mobile_details',
        'image',
        'mobile_image',
    ];
}
