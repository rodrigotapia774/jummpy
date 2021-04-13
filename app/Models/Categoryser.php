<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoryser extends Model
{
    use HasFactory;

    protected $table = "categoryser";

    protected $fillable = [
        'user_id',
        'parent_id',
        'name',
        'state'
    ];
}
