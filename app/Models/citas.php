<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class citas extends Model
{
    /** @use HasFactory<\Database\Factories\CitasFactory> */
    use HasFactory;

    protected $fillable = ['paciente_id', 'medico_id', 'fecha_cita', 'motivo'];
}
