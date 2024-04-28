<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Imagen extends Model
{
    use HasFactory;

     //Muchas imagenes pertenecen a un evento
     public function content(): BelongsTo
     {
         return $this->belongsTo(Content::class);
     }
 
     protected $fillable = [
         'content_id',
         'img'
     ];
 
     public static function rules()
     {
         return [
             'img' => 'required'
         ];
     }
}
