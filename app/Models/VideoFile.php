<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'videoUrl',
    ];

    public static function rules()
    {
        return [
            'titulo' => 'required|max:50',
            'videoUrl' => 'required',
        ];
    }
}
