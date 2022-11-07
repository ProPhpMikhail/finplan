<?php


namespace App\Http\Controllers\Tables;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Nette\Utils\DateTime;

abstract class TableController extends Controller
{
    protected $data;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    abstract protected function getHeader(): string;

    abstract protected function getHeaders(): array;

    abstract protected function getData(): array;

    abstract protected function getForm(Request $request): array;

    abstract protected function getModelClass(): string;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show(Request $request)
    {
        return view('maintables', ['header' => $this->getHeader(), 'headers' => $this->getHeaders(), 'data' => $this->getData(), 'form' => $this->getForm($request), 'paginator' => $this->data]);
    }

    public function add(Request $request)
    {
        $data = [];
        $validate = [];
        foreach ($this->getForm($request) as $fieldName => $field) {
            if (isset($field['validate']) && !empty($field['validate'])) {
                $validate[$fieldName] = $field['validate'];
            }
            if ($fieldName == 'rate') {
                $data[$fieldName] = $request->get('from_amount') / $request->get('to_amount');
                continue;
            }
            if (isset($field['value'])) {
                $data[$fieldName] = $field['value'];
                continue;
            }
            if ($field['type'] == 'select') {
                $data[$fieldName . '_id'] = $request->get($fieldName);
                continue;
            }
            if (in_array($field['type'], ['text', 'selectNotDb'])) {
                $data[$fieldName] = $request->get($fieldName);
                continue;
            }
            if ($field['type'] == 'datetime' && !empty($request->get($fieldName))) {
                $data[$fieldName] = \Nette\Utils\DateTime::createFromFormat('d-m-Y h:i', $request->get($fieldName));
                continue;
            }
            if ($fieldName == 'code') {
                $data[$fieldName] = strtolower($request->get('name'));
            }
        }
        $request->validate($validate);
        $class = $this->getModelClass();
        $model = new $class($data);
        $model->save();
        return Redirect::back()->with('status', 'Запись добавлена');
    }

    public function update(Request $request)
    {
        $data = [];
        $validate = [];
        foreach ($this->getForm($request) as $fieldName => $field) {
            if (isset($field['validate'])) {
                $validate[$fieldName] = $field['validate'];
            }
            if ($fieldName == 'rate') {
                if ($request->get('to_amount')) {
                    $data[$fieldName] = $request->get('from_amount') / $request->get('to_amount');
                } else {
                    $data[$fieldName] = 0;
                }
                continue;
            }
            if (isset($field['value'])) {
                $data[$fieldName] = $field['value'];
                continue;
            }
            if ($field['type'] == 'select') {
                $data[$fieldName . '_id'] = $request->get($fieldName);
                continue;
            }
            if (in_array($field['type'], ['text', 'selectNotDb'])) {
                $data[$fieldName] = $request->get($fieldName);
                continue;
            }
            if ($field['type'] == 'datetime') {
                $data[$fieldName] = DateTime::createFromFormat('d-m-Y h:i', $request->get($fieldName));
                continue;
            }
            if ($fieldName == 'code') {
                $data[$fieldName] = strtolower($request->get('name'));
            }
        }
        $request->validate($validate);
        $class = $this->getModelClass();
        $model = $class::all()->find($request->id);
        foreach ($data as $fieldName => $fieldValue) {
            $model->$fieldName = $fieldValue;
        }
        $model->save();
        return Redirect::back()->with('status', 'Запись обновлена');
    }
}
