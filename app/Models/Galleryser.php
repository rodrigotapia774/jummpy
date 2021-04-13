<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galleryser extends Model
{
    use HasFactory;

    protected $table = "galleryser";

    protected $fillable = [
        'user_id',
        'service_id',
        'image'
    ];

    /**Unimos el o las galerías con su producto utilizando el metodo hasOne.
     *
     */
    public function service()
    { 
    	return $this->hasOne(Product::class, "id", "service_id");
    }
}
