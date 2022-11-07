<?php

namespace App\Http\Controllers\Tables;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\Transfers;
use Illuminate\Support\Facades\Auth;

class TransferController extends TableController
{
    protected function getHeader(): string
    {
        return 'Переводы между счетами';
    }

    protected function getHeaders(): array
    {
        return [
            '#',
            'Счет источника',
            'Счет назначения',
            'Сумма источника',
            'Сумма назначения',
            'Курс',
            'Пердыдущий перевод',
            'Дата',
            'Операции',
        ];
    }

    protected function getData(): array
    {
        $this->data = Transfers::query()
            ->whereIn('from_account_id', Auth::user()->accounts()->pluck('id')->toArray())
            ->whereIn('to_account_id',  Auth::user()->accounts()->pluck('id')->toArray())
            ->paginate(15);
        $data = [];
        foreach ($this->data as $row) {
            $data[] = [
                'id' => [
                    'value' => $row->id,
                ],
                'from_account' => [
                    'value' => $row->accountFrom->name,
                    'valueId' => $row->accountFrom->id,
                ],
                'to_account' => [
                    'value' => $row->accountTo->name,
                    'valueId' => $row->accountTo->id,
                ],
                'from_amount' => [
                    'value' => $row->from_amount,
                ],
                'to_amount' => [
                    'value' => $row->to_amount,
                ],
                'rate' => [
                    'value' => $row->rate,
                ],
                'previous' => [
                    'value' => $row->previous ? $row->previous->accountFrom->name . ' > ' . $row->previous->accountTo->name . ' [' . $row->previous->id . ']' : null,
                    'valueId' => $row->previous ? $row->previous->id : null,
                ],
                'created_at' => [
                    'value' => $row->created_at->format('d-m-Y h:i'),
                ],
            ];
        }

        return $data;
    }

    protected function getForm(Request $request): array
    {
        $transfers = Transfers::query()
            ->whereIn('from_account_id', Auth::user()->accounts()->pluck('id')->toArray())
            ->whereIn('to_account_id',  Auth::user()->accounts()->pluck('id')->toArray())
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();
        $transfersPrepared = [
            new class {
                public $name = 'Нет';
                public $id = null;
            }
        ];
        foreach ($transfers as $transfer) {
            $transfersPrepared[] = new class($transfer->id, $transfer->accountFrom->name . ' > ' . $transfer->accountTo->name . ' [' . $transfer->id . ']') {
                public $name;
                public $id;
                public function __construct($id, $name)
                {
                    $this->id = $id;
                    $this->name = $name;
                }
            };
        }
        return [
            'from_account' => [
                'list' => Account::where('user_id', Auth::user()->id)->orderBy('sort', 'asc')->get(),
                'type' => 'select',
                'label' => 'Счет источника',
                'validate' => 'not_in:' . $request->get('to_account'),
            ],
            'to_account' => [
                'list' => Account::where('user_id', Auth::user()->id)->orderBy('sort', 'asc')->get(),
                'type' => 'select',
                'label' => 'Счет назначения',
                'validate' => 'not_in:' . $request->get('from_account'),
            ],
            'from_amount' => [
                'type' => 'text',
                'label' => 'Сумма источника',
                'validate' => 'required|numeric',
            ],
            'to_amount' => [
                'type' => 'text',
                'label' => 'Сумма назначения',
                'validate' => 'required|numeric',
            ],
            'rate' => [
                'value' => 'hidden'
            ],
            'previous' => [
                'list' => $transfersPrepared,
                'type' => 'select',
                'label' => 'Счет источника',
                'validate' => $request->get('id') ? 'not_in:' . $request->get('id') : '',
            ],
            'created_at' => [
                'type' => 'datetime',
                'label' => 'Дата',
                'validate' => 'required',
            ],
        ];
    }

    protected function getModelClass(): string
    {
        return Transfers::class;
    }
}
