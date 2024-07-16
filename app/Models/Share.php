<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Share extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'is_featured',
        'views',
    ];

    protected $dates = ['deleted_at'];

    public function scopeWithBooleanFormatted($query) {
        return $query->select(['id', 'title', 'is_featured', 'created_at'])
            ->selectRaw('IF(is_featured, "true", "false") AS is_featured');
    }
}
