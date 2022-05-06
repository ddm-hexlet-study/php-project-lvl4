@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('views.tasks.editeTitle') }}</h1>
    {{ Form::model($task, ['route' => ['tasks.update', $task], 'method' => 'patch',  'class' => 'w-50']) }}
    {{ Form::token() }}
    @include('tasks.form')
    {{ Form::submit(__('views.tasks.editeButton'), ['class' => 'btn btn-primary mt-3']) }}
    {{ Form::close() }}
@endsection
