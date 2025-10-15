<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimation extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_name',
        'kloc',
        'salario_mensual',
        'modo',
        'factores_costo',
        'eaf',
        'pm',
        'duracion',
        'personal',
        'costo_total',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'factores_costo' => 'array',
    ];
}