<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model para a tabela pontos_usuario
 * Representa um ponto postado por um usuário com coordenadas geográficas
 */
class PontoUsuario extends Model
{
    use HasFactory;

    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table = 'pontos_usuario';

    /**
     * Campos que podem ser preenchidos em massa
     *
     * @var array
     */
    protected $fillable = [
        'latitude',
        'longitude',
        'municipio_id',
        'geom'
    ];

    /**
     * Campos que devem ser convertidos para tipos nativos
     *
     * @var array
     */
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Relacionamento: Um ponto pertence a um município
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function municipio()
    {
        return $this->belongsTo(MunicipioGeometria::class, 'municipio_id');
    }
}
