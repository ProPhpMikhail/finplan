<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    protected static function booted()
    {
        static::saved(function (self $transaction) {
            self::afterSaved($transaction);
        });
    }

    private static function afterSaved(self $transaction): void
    {
        $amoount = 0;
        if (isset($transaction->original['amount'])) {
            $account = Account::all()->find($transaction->original['account_id']);
            if (!$transaction->original['type']) {
                $account->amount += $transaction->original['amount'];
            } else {
                $account->amount -= $transaction->original['amount'];
            }
            if ($transaction->original['account_id'] == $transaction->attributes['account_id']) {
                $amoount = $account->amount;
            }
            $account->save();
        }
        $account = Account::all()->find($transaction->attributes['account_id']);
        if ($amoount) {
            $account->amount = $amoount;
        }
        if (!$transaction->attributes['type']) {
            $account->amount -= $transaction->attributes['amount'];
        } else {
            $account->amount += $transaction->attributes['amount'];
        }
        $account->save();
    }
}
