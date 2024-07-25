<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Banner extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'mobile_title',
        'image',
        'mobile_image',
    ];

    protected $dates = ['deleted_at'];

    public function getFileType($attribute)
    {
        $filePath = $this->{$attribute};

        if ($filePath) {
            $fileMimeType = Storage::disk('public')->mimeType($filePath);

            if (strpos($fileMimeType, 'image/') === 0) {
                return 'image';
            } elseif (strpos($fileMimeType, 'video/') === 0) {
                return 'video';
            }
        }

        return 'unknown';
    }
}
