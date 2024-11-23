<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    use HasFactory;

    protected $table = 'habitacion';

    protected $fillable = [
        'id_hotel',
        'cantidad',
        'tipo_habitacion',
        'acomodacion',
        'estado',
    ];

    public function hotel()
    {
        return $this->hasOne(Hotel::class, 'id', 'id_hotel');
    }
}



