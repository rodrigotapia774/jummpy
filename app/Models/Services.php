<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'proveedor_id',
        'category_id',
        'name', 
        'resume',
        'content',
        'ubicacion',
        'price',
        'sale',
        'total',
        'state',
        'cierre',
        'entrega'
    ];

    /**
     * Unimos el o los productos con la tabla gallery utilizando el metodo hasMany.
     *
     */
    public function gallery()
    { 
        return $this->hasMany(Galleryser::class, "service_id", "id");
    }

    /**
     * Unimos el o los productos con la tabla category utilizando el metodo hasOne.
     *
     */
    public function category()
    { 
        return $this->hasOne(Categoryser::class, "id", "category_id");
    }

    /**
     * Unimos el o los productos con la tabla category utilizando el metodo hasOne.
     *
     */
    public function proveedor()
    { 
        return $this->hasOne(Proveedores::class, "id", "proveedor_id");
    }
}
