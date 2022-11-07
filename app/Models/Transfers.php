<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfers extends Model
{
    protected $guarded = [];

    public function accountFrom()
    {
        return $this->belongsTo(Account::class, 'from_account_id', 'id');
    }

    public function accountTo()
    {
        return $this->belongsTo(Account::class, 'to_account_id', 'id');
    }

    public function previous()
    {
        return $this->belongsTo(Transfers::class, 'previous_id', 'id');
    }

    protected static function booted()
    {
        static::saved(function (self $transaction) {
            self::afterSaved($transaction);
        });
    }

    private static function afterSaved(self $transaction): void
    {
        if (isset($transaction->original['from_amount'])) {
            $accountFrom = Account::all()->find($transaction->original['from_account_id']);
            $accountFrom->amount += $transaction->original['from_amount'];
            $accountFrom->save();
        }
        if (isset($transaction->original['to_amount'])) {
            $accountTo = Account::all()->find($transaction->original['to_account_id']);
            $accountTo->amount -= $transaction->original['to_amount'];
            $accountTo->save();
        }
        if (isset($transaction->attributes['from_amount'])) {
            $accountFrom = Account::all()->find($transaction->attributes['from_account_id']);
            $accountFrom->amount -= $transaction->attributes['from_amount'];
            $accountFrom->save();
        }
        if (isset($transaction->attributes['to_amount'])) {
            $accountTo = Account::all()->find($transaction->attributes['to_account_id']);
            $accountTo->amount += $transaction->attributes['to_amount'];
            $accountTo->save();
        }
    }
}
