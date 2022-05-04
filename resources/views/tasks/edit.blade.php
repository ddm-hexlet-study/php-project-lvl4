@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Изменение задачи</h1>
    {{ Form::model($task, ['route' => ['tasks.update', $task], 'method' => 'patch',  'class' => 'w-50']) }}
    {{ Form::token() }}
    @include('tasks.form')
    {{ Form::submit('Обновить', ['class' => 'btn btn-primary mt-3']) }}
    {{ Form::close() }}
@endsection
