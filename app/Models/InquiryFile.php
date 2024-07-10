<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InquiryFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'inquiry_id',
        'file_path',
    ];

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }
}
