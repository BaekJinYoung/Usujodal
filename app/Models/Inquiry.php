<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'contact',
        'company',
        'email',
        'message',
        'agreement',
    ];

    protected $dates = ['deleted_at'];
}
