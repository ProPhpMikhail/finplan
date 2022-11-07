<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CurrenciesSeeder extends Seeder
{
    private const CURRENCIES = [
        'RUB',
        'USD',
        'TRY',
        'KGS',
        'USDT',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::CURRENCIES as $currencyName) {
            if (!Currency::where('code', $currencyName)->get()->isEmpty()) {
                continue;
            }
            $currency = new Currency([
                'name' => $currencyName,
                'code' => $currencyName
            ]);
            $currency->save();
        }
    }
}
