<?php

namespace App\Http\Controllers\Tables;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends TableController
{
    protected function getHeader(): string
    {
        return 'Транзакции';
    }

    protected function getHeaders(): array
    {
        return [
            '#',
            'Название',
            'Счет',
            'Сумма',
            'Дата',
            'Тип',
            'Операции',
        ];
    }

    protected function getData(): array
    {
        $this->data = Transaction::whereIn('account_id', Auth::user()->accounts()->pluck('id')->toArray())->paginate(15);
        $data = [];
        foreach ($this->data as $row) {
            $data[] = [
                'id' => [
                    'value' => $row->id,
                ],
                'name' => [
                    'value' => $row->name,
                ],
                'account' => [
                    'value' => $row->account->name,
                    'valueId' => $row->account->id,
                ],
                'amount' => [
                    'value' => $row->amount,
                ],
                'created_at' => [
                    'value' => $row->created_at->format('d-m-Y h:i'),
                ],
                'type' => [
                    'value' => $row->type == 1 ? 'Приход' : 'Расход',
                    'valueId' => $row->type
                ],
            ];
        }

        return $data;
    }

    protected function getForm(Request $request): array
    {
        $maxAmount = $request->get('account') ? '|max:' . Account::all()->find($request->get('account'))->amount : '';
        return [
            'name' => [
                'type' => 'text',
                'label' => 'Название транзакции',
                'validate' => 'required|max:255',
            ],
            'account' => [
                'list' => Account::where('user_id', Auth::user()->id)->orderBy('sort', 'asc')->get(),
                'type' => 'select',
                'label' => 'Счет',
            ],
            'amount' => [
                'type' => 'text',
                'label' => 'Сумма',
                'validate' => 'required|numeric' . $maxAmount,
            ],
            'created_at' => [
                'type' => 'datetime',
                'label' => 'Дата',
                'validate' => 'required',
            ],
            'type' => [
                'list' => [
                    new class {
                        public $id = 0;
                        public $name = 'Расход';
                    },
                    new class {
                        public $id = 1;
                        public $name = 'Приход';
                    },
                ],
                'type' => 'selectNotDb',
                'label' => 'Тип',
            ],
        ];
    }

    protected function getModelClass(): string
    {
        return Transaction::class;
    }
}
