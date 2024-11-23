<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $table = 'hotel';

    protected $fillable = [
        'nombre',
        'direccion',
        'ciudad',
        'nit',
        'digito_verificacion',
        'numero_habitaciones',
        'estado',
    ];
}
