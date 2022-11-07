<?php

namespace App\Http\Controllers\Tables;

use App\Models\Account;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends TableController
{
    protected function getHeader(): string
    {
        return 'Счета';
    }

    protected function getHeaders(): array
    {
        return [
            '#',
            'Название',
            'Сумма',
            'Валюта',
            'Код',
            'Сортировка',
            'Операции',
        ];
    }

    protected function getData(): array
    {
        $this->data = Account::where('user_id', Auth::id())->orderBy('sort', 'asc')->paginate(15);
        $data = [];
        foreach ($this->data as $row) {
            $data[] = [
                'id' => [
                    'value' => $row->id,
                ],
                'name' => [
                    'value' => $row->name,
                ],
                'amount' => [
                    'value' => $row->amount,
                ],
                'currency' => [
                    'value' => $row->currency->name,
                    'valueId' => $row->currency->id,
                ],
                'code' => [
                    'value' => $row->code,
                ],
                'sort' => [
                    'value' => $row->sort,
                ],
            ];
        }

        return $data;
    }

    protected function getForm(Request $request): array
    {
        return [
            'name' => [
                'type' => 'text',
                'label' => 'Название счета',
                'validate' => 'required|max:255',
            ],
            'amount' => [
                'type' => 'text',
                'label' => 'Сумма счета',
                'validate' => 'required|numeric',
            ],
            'currency' => [
                'list' => Currency::all(),
                'type' => 'select',
                'label' => 'Валюта',
            ],
            'user_id' => [
                'value' => Auth::user()->id,
            ],
            'code' => [
                'type' => 'text',
                'label' => 'Код',
                'validate' => 'required|max:255',
            ],
            'sort' => [
                'type' => 'text',
                'label' => 'Сортировка',
                'validate' => 'required|numeric',
            ],
        ];
    }

    protected function getModelClass(): string
    {
        return Account::class;
    }
}
