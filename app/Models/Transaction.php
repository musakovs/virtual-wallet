<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $from_wallet
 * @property int $to_wallet
 * @property string $amount
 * @property int $fraudulent
 */
class Transaction extends Model
{
    use softDeletes;

    protected $table = 'transactions';

    protected $fillable = ['from_wallet', 'to_wallet', 'amount'];

    protected $casts = [
        'from_wallet' => 'int',
        'to_wallet'   => 'int'
    ];

    public function from()
    {
        return $this->hasOne(Wallet::class, 'id', 'from_wallet');
    }

    public function to()
    {
        return $this->hasOne(Wallet::class, 'id', 'to_wallet');
    }
}
