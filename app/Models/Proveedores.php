<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedores extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name', 
        'address',
        'phone',
        'country',
        'province',
        'content',
        'state',
    ];

    /**
     * Unimos el proveedor con la tabla productos utilizando el metodo hasMany.
     *
     */
    public function product()
    { 
        return $this->hasMany(Product::class, "proveedor_id", "id");
    }
}
