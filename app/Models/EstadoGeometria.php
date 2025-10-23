<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model para a tabela estados_geometria
 * Representa um estado com sua geometria espacial
 */
class EstadoGeometria extends Model
{
    use HasFactory;

    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table = 'estados_geometria';

    /**
     * Campos que podem ser preenchidos em massa
     *
     * @var array
     */
    protected $fillable = [
        'nome_estado',
        'geom'
    ];
}
