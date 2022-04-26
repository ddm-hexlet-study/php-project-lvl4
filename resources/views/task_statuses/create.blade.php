@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Создать статус</h1>
    {{ Form::open(['route' => 'task_statuses.store', 'class' => 'w-50']) }}
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
    {{ Form::submit('Создать', ['class' => 'btn btn-primary mt-3']) }}
    {{ Form::close() }}
@endsection
