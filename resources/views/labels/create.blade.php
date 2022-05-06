@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('views.labels.createTitle') }}</h1>
    {{ Form::model($label, ['route' => 'labels.store', 'class' => 'w-50']) }}
    {{ Form::token() }}
    @include('labels.form')
    {{ Form::submit(__('views.labels.createButton'), ['class' => 'btn btn-primary mt-3']) }}
    {{ Form::close() }}
@endsection
