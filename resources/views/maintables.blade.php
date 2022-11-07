@extends('layouts.app')
@section('content')
    <div class="container">
        <h2 align="center">{{ $header }}</h2>
        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addForm">
            Добавить
        </button>
        <div class="row justify-content-center">
            <table class="table">
                <thead>
                <tr>
                    @foreach($headers as $header)
                        <th scope="col">{{ $header }}</th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach ($data as $row)
                    <tr>
                        @foreach($row as $fieldName => $field)
                            @if ($fieldName == 'id')
                                <th scope="row">{{ $field['value'] }}</th>
                            @else
                                <td>{{ $field['value'] }}</td>
                            @endif
                        @endforeach
                        <td>
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#updateForm{{ $row['id']['value'] }}">
                                Изменить
                            </button>
                            <div class="modal fade" id="updateForm{{ $row['id']['value'] }}" tabindex="-1" role="dialog" aria-labelledby="updateFormLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="updateFormLabel">Редактировать</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                {{ csrf_field() }}
                                                {{ method_field('put') }}
                                                <input type="hidden" name="id" value="{{ $row['id']['value'] }}">
                                                @foreach($form as $fieldName => $field)
                                                    @continue(isset($field['value']))
                                                    <div class="form-group">
                                                        <label for="{{ $fieldName }}">{{ $field['label'] }}</label>
                                                        @if($field['type'] == 'text')
                                                            <input type="text" class="form-control" name="{{ $fieldName  }}" id="{{ $fieldName }}" value="{{ $row[$fieldName]['value'] }}">
                                                        @endif
                                                        @if($field['type'] == 'select' || $field['type'] == 'selectNotDb')
                                                            <select class="form-control" id="{{ $fieldName }}" name="{{ $fieldName }}">
                                                                @foreach ($field['list'] as $list)
                                                                    <option
                                                                        @if ($row[$fieldName]['valueId'] == $list->id)
                                                                            selected
                                                                        @endif
                                                                        value="{{ $list->id }}">{{ $list->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                        @if($field['type'] == 'datetime')
                                                            <div class='input-group date datetimepicker' id='{{ $fieldName }}'>
                                                                <input type='text' class="form-control" name="{{ $fieldName }}" value="{{ $row[$fieldName]['value'] }}"/>
                                                                <span class="input-group-addon">
                                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                                </span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                                                    <button type="submit" class="btn btn-primary">Сохранить</button>
                                                </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {!! $paginator->links() !!}
            </div>
        </div>
    </div>
    <div class="modal fade" id="addForm" tabindex="-1" role="dialog" aria-labelledby="addFormLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addFormLabel">Создать</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ csrf_field() }}
                        @foreach($form as $fieldName => $field)
                            @continue(isset($field['value']))
                            <div class="form-group">
                                <label for="{{ $fieldName }}">{{ $field['label'] }}</label>
                                @if($field['type'] == 'text')
                                    <input type="text" class="form-control" name="{{ $fieldName  }}" id="{{ $fieldName }}">
                                @endif
                                @if($field['type'] == 'select' || $field['type'] == 'selectNotDb')
                                    <select class="form-control" id="{{ $fieldName }}" name="{{ $fieldName }}">
                                        @foreach ($field['list'] as $list)
                                            <option value="{{ $list->id }}">{{ $list->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                @if($field['type'] == 'datetime')
                                    <div class='input-group date datetimepicker' id='{{ $fieldName }}'>
                                        <input type='text' class="form-control" name="{{ $fieldName }}" />
                                        <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    </div>
                                    <script type="text/javascript">
                                        $(function() {
                                            $('.datetimepicker').datetimepicker({
                                                format: 'DD-MM-YYYY HH:mm'
                                            });
                                        });
                                    </script>
                                @endif
                            </div>
                        @endforeach
                        <div class="form-group">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                            <button type="submit" class="btn btn-success">Сохранить</button>
                        </div>
                </form>
            </div>
        </div>
    </div>
@endsection
