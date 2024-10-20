<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pacientes extends Model
{
    /** @use HasFactory<\Database\Factories\PacientesFactory> */
    use HasFactory;

    protected $fillable = ['nombre','apellido','email','telefono','fecha_nacimiento'];



}

