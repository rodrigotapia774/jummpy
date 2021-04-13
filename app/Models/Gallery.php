<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $table = "gallery";

    protected $fillable = [
        'user_id',
        'product_id',
        'image'
    ];

    /**Unimos el o las galerías con su producto utilizando el metodo hasOne.
     *
     */
    public function product()
    { 
    	return $this->hasOne(Product::class, "id", "product_id");
    }
}
