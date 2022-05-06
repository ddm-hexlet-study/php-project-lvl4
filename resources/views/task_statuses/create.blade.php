@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('views.statuses.createTitle') }}</h1>
    {{ Form::model($task_status, ['route' => 'task_statuses.store', 'class' => 'w-50']) }}
    {{ Form::token() }}
    @include('task_statuses.form')
    {{ Form::submit(__('views.statuses.createButton'), ['class' => 'btn btn-primary mt-3']) }}
    {{ Form::close() }}
@endsection
