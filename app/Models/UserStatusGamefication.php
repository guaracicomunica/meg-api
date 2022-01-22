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

    ];

    protected $hidden = [ 'id', 'created_at',  'updated_at' ];

    /***
     * Ao modificar a nota do aluno, é recalculada a quantidade de moedas.
     * Se a nota atual for maior que a anterior,
     * Seu status global deve receber as novas e excluir as últimas ganhas
     * pela mesma atividade, pois já não valem mais após retificação.
     * @param int $newCoins
     * @param int $oldCoins
     * @return int
     */
    public function recalculateCoins(int $newCoins, int $oldCoins): int
    {
        if($newCoins > $oldCoins)
        {
            return ($this->coins - $oldCoins) + $newCoins;
        } else {
            return $this->coins - ($oldCoins - $newCoins);
        }
    }
}
