@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Создать задачу</h1>
    {{ Form::open(['route' => 'tasks.store', 'class' => 'w-50']) }}
    {{ Form::token() }}
    <div class="form-group mb-3">
        {{ Form::label('name', 'Имя') }}
        {{ Form::text('name', null, ['class' => 'form-control']) }}
        @error('name')
        <span class="text-danger" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group mb-3">
        {{ Form::label('description', 'Описание') }}
        {{ Form::textarea('description', null, ['class' => 'form-control']) }}
    </div>
    <div class="form-group mb-3">
        {{ Form::label('status_id', 'Статус') }}
        {{ Form::select('status_id', $statuses, null, ['placeholder' => '----------', 'class' => 'form-control']) }}
        @error('status_id')
        <span class="text-danger" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <div class="form-group mb-3">
        {{ Form::label('assigned_to_id', 'Исполнитель') }}
        {{ Form::select('assigned_to_id', $users, null, ['placeholder' => '----------', 'class' => 'form-control']) }}
    </div>
    <div class="form-group mb-3">
        {{ Form::label('labels', 'Метка') }}
        {{ Form::select('labels[]', $labels, null, ['class' => 'form-control', 'multiple' => '']) }}
    </div>
    {{ Form::submit('Создать', ['class' => 'btn btn-primary mt-3']) }}
    {{ Form::close() }}
@endsection
