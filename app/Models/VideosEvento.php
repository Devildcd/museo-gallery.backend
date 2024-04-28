<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideosEvento extends Model
{
    use HasFactory;

    public function contenido(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    protected $fillable = [
        'content_id',
        'nombre',
        'url',
    ];

    public static function rules()
    {
        return [
            'content_id' => 'required',
            'nombre' => 'required|max:50',
            'url' => 'required'
        ];
    }
}
