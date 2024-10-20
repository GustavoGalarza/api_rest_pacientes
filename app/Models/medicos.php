<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class medicos extends Model
{
    /** @use HasFactory<\Database\Factories\MedicosFactory> */
    use HasFactory;

    protected $fillable = ['nombre', 'especialidad', 'email', 'telefono'];
}
