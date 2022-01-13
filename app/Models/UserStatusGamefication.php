<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static firstOrCreate(array $array, array $array1)
 */
class UserStatusGamefication extends Model
{
    use HasFactory;
    protected $table = 'user_status_gamefications';
    protected $fillable = [
        'coins',
        'user_id',
        'created_at',
        'updated_at',
    ];

    /***
     * Ao modificar a nota do aluno, é recalculada a quantidade de moedas.
     * Seu status global deve receber as novas e excluir as últimas ganhas
     * pela mesma atividade, pois já não valem mais após retificação.
     * @param $newCoins
     * @param $oldCoins
     * @return int
     */
    public function recalculateCoins(int $newCoins, int $oldCoins): int
    {
        return ($this->coins - $oldCoins) + $newCoins;
    }
}
