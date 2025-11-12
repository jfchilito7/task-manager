<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Definir los campos asignables
    protected $fillable = [
        'titulo',
        'descripcion',
        'estado',
        'user_id',
    ];

    // Relación con el modelo User (una tarea pertenece a un usuario)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
