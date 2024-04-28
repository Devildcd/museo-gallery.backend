<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Content extends Model
{
    use HasFactory;

    public function imagenes(): HasMany
    {
        return $this->hasMany(Imagen::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(VideosEvento::class);
    }

    protected $fillable = [
        'nombre',
        'info',
        'fecha',
        'detalles',
        'principal',
        'programado',
        'prioridad',
        'tipo',
        'visitas'
    ];

    protected $casts = [
        'principal',
        'programado',
        'prioridad'
    ];

    public static function rules()
    {
        return [

            'nombre' => ['required', 'between:20,100'],
            'info' => ['max:100'],
            'detalles' => ['required']
        ];
    }
}
