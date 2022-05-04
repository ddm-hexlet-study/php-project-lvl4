@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Создать статус</h1>
    {{ Form::model($task_status, ['route' => 'task_statuses.store', 'class' => 'w-50']) }}
    {{ Form::token() }}
    @include('task_statuses.form')
    {{ Form::submit('Создать', ['class' => 'btn btn-primary mt-3']) }}
    {{ Form::close() }}
@endsection
