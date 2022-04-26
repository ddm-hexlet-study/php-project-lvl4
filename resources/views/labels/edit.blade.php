@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Изменение метки</h1>
    {{ Form::model($label, ['route' => ['labels.update', $label], 'method' => 'patch',  'class' => 'w-50']) }}
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
    {{ Form::submit('Обновить', ['class' => 'btn btn-primary mt-3']) }}
    {{ Form::close() }}
@endsection
