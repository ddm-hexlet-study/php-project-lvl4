@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Изменение метки</h1>
    {{ Form::model($label, ['route' => ['labels.update', $label], 'method' => 'patch',  'class' => 'w-50']) }}
    {{ Form::token() }}
    @include('labels.form')
    {{ Form::submit('Обновить', ['class' => 'btn btn-primary mt-3']) }}
    {{ Form::close() }}
@endsection
