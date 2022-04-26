@extends('layouts.app')

@section('content')
    <h1 class="mb-5">Статусы</h1>
    @if (Auth::check())
    <a href="{{ route('task_statuses.create') }}" class="btn btn-primary">
        Создать статус        </a>
    @endif
    <table class="table mt-2">
        <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Дата создания</th>
            @if (Auth::check())
            <th>Действия</th>
            @endif
        </tr>
        </thead>
        @foreach ($statuses as $status)
        <tr>
            <td>{{ $status->id }}</td>
            <td>{{ $status->name }}</td>
            <td>{{ $status->created_at }}</td>
            @if (Auth::check())
            <td>
                <a
                    class="text-danger text-decoration-none"
                    href="{{ route('task_statuses.destroy', $status) }}"
                    data-confirm="Вы уверены?"
                    data-method="delete"
                >
                    Удалить</a>
                <a class="text-decoration-none" href="{{ route('task_statuses.edit', $status) }}">Изменить</a>
            </td>
            @endif
        </tr>
        @endforeach
    </table>

@endsection
