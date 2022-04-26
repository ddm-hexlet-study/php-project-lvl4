@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Создать метку</h1>
    {{ Form::open(['route' => 'labels.store', 'class' => 'w-50']) }}
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
    {{ Form::submit('Создать', ['class' => 'btn btn-primary mt-3']) }}
    {{ Form::close() }}
@endsection
