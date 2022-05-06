@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('views.statuses.editeTitle') }}</h1>
    {{ Form::model($taskStatus, ['route' => ['task_statuses.update', $taskStatus], 'method' => 'patch', 'class' => 'w-50']) }}
    {{ Form::token() }}
    @include('task_statuses.form')
    {{ Form::submit(__('views.statuses.editeButton'), ['class' => 'btn btn-primary mt-3']) }}
    {{ Form::close() }}
@endsection
