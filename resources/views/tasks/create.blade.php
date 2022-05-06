@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('views.tasks.createTitle') }}</h1>
    {{ Form::model($task, ['route' => 'tasks.store', 'class' => 'w-50']) }}
    {{ Form::token() }}
    @include('tasks.form')
    {{ Form::submit(__('views.tasks.createButton'), ['class' => 'btn btn-primary mt-3']) }}
    {{ Form::close() }}
@endsection
