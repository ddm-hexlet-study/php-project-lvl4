@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Задачи</h1>
    <div class="d-flex mb-3">
        {{ Form::open(['route' => 'tasks.index', 'method' => 'get', 'class' => 'w-50']) }}
        <div>
            <div class="row g-1">
                <div class="col">
                    {{ Form::select('filter[status_id]', $statuses, null, ['placeholder' => 'Статус', 'class' => 'form-control']) }}
                </div>
                <div class="col">
                    {{ Form::select('filter[created_by_id]', $users, null, ['placeholder' => 'Автор', 'class' => 'form-control']) }}
                </div>
                <div class="col">
                    {{ Form::select('filter[assigned_to_id]', $users, null, ['placeholder' => 'Исполнитель', 'class' => 'form-control']) }}
                </div>
                <div class="col">
                    {{ Form::submit('Применить', ['class' => 'btn btn-outline-primary me-2']) }}
                </div>

            </div>
        </div>
        {{ Form::close() }}
        @if (Auth::check())
            <div class="ms-auto">
                <a href="{{ route('tasks.create') }}" class="btn btn-primary ml-auto">Создать задачу</a>
            </div>
        @endif
    </div>



    <table class="table me-2">
        <thead>
        <tr>
            <th>ID</th>
            <th>Статус</th>
            <th>Имя</th>
            <th>Автор</th>
            <th>Исполнитель</th>
            <th>Дата создания</th>
            <th>Действия</th>
        </tr>
        </thead>
        @foreach ($tasks as $task)
        <tr>
            <td>{{ $task->id }}</td>
            <td>{{ $task->status->name }}</td>
            <td>
                <a class="text-decoration-none" href="{{ route('tasks.show', $task) }}">
                    {{ $task->name }}
                </a>
            </td>
            <td>{{ $task->created_by->name }}</td>
            <td>{{ optional($task->assigned_to)->name }}</td>
            <td>{{ $task->created_at }}</td>
            <td>
                @if (Auth::check())
                    <a class="text-decoration-none" href="{{ route('tasks.edit', $task) }}">Изменить</a>
                @endif
                @if (Gate::allows('delete', $task))
                <a
                    class="text-danger text-decoration-none"
                    href="{{ route('tasks.destroy', $task) }}"
                    data-confirm="Вы уверены?"
                    data-method="delete"
                >Удалить</a>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
    {{ $tasks->links() }}
@endsection
