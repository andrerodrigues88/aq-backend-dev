<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model para a tabela municipios_geometria
 * Representa um município com sua geometria espacial
 */
class MunicipioGeometria extends Model
{
    use HasFactory;

    /**
     * Nome da tabela
     *
     * @var string
     */
    protected $table = 'municipios_geometria';

    /**
     * Campos que podem ser preenchidos em massa
     *
     * @var array
     */
    protected $fillable = [
        'nome_municipio',
        'geom'
    ];

    /**
     * Relacionamento: Um município pode ter vários pontos de usuário
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pontosUsuario()
    {
        return $this->hasMany(PontoUsuario::class, 'municipio_id');
    }
}
