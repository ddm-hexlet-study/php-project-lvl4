@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Изменение статуса</h1>
    {{ Form::model($taskStatus, ['route' => ['task_statuses.update', $taskStatus], 'method' => 'patch', 'class' => 'w-50']) }}
    {{ Form::token() }}
    @include('task_statuses.form')
    {{ Form::submit('Обновить', ['class' => 'btn btn-primary mt-3']) }}
    {{ Form::close() }}
@endsection
