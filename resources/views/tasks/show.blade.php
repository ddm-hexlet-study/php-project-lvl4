@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('views.tasks.show') }}: {{ $task->name }}
        @if (Auth::check())
            <a href="{{ route('tasks.edit', $task) }}">&#9881;</a>
        @endif
    </h1>

    <p>{{ __('views.tasks.name') }}: {{ $task->name }}</p>
    <p>{{ __('views.tasks.status') }}: {{ $task->status->name }}</p>
    <p>{{ __('views.tasks.description') }}: {{ $task->description }}</p>
    <p>{{ __('views.tasks.labels') }}: </p>
    <ul>
        @foreach($task->labels as $label)
            <li>{{ $label->name }}</li>
        @endforeach
    </ul>
@endsection
