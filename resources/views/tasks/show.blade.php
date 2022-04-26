@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Просмотр задачи: {{ $task->name }}
        @if (Auth::check())
            <a href="{{ route('tasks.edit', $task) }}">&#9881;</a>
        @endif
    </h1>

    <p>Имя: {{ $task->name }}</p>
    <p>Статус: {{ $task->status->name }}</p>
    <p>Описание: {{ $task->description }}</p>
    <p>Метки: </p>
    <ul>
        @foreach($task->labels as $label)
            <li>{{ $label->name }}</li>
        @endforeach
    </ul>
@endsection
