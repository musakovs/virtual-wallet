<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $amount
 */
class Wallet extends Model
{
    use SoftDeletes;

    protected $table = 'wallets';

    protected $fillable = ['name'];

    protected $casts = [
        'user_id' => 'int'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function belongsToUser(User $user): bool
    {
        return $user->id === $this->user_id;
    }
}
