@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('views.tasks.indexTitle') }}</h1>
    <div class="d-flex mb-3">
        {{ Form::open(['route' => 'tasks.index', 'method' => 'get', 'class' => 'w-50']) }}
        <div>
            <div class="row g-1">
                <div class="col">
                    {{ Form::select('filter[status_id]', $statuses, old('filter[status_id]'), ['placeholder' => __('views.tasks.status'), 'class' => 'form-control']) }}
                </div>
                <div class="col">
                    {{ Form::select('filter[created_by_id]', $users, old('filter[created_by_id]'), ['placeholder' => __('views.tasks.author'), 'class' => 'form-control']) }}
                </div>
                <div class="col">
                    {{ Form::select('filter[assigned_to_id]', $users, old('filter[assigned_to_id]'), ['placeholder' => __('views.tasks.executor'), 'class' => 'form-control']) }}
                </div>
                <div class="col">
                    {{ Form::submit(__('views.tasks.applyButton'), ['class' => 'btn btn-outline-primary me-2']) }}
                </div>

            </div>
        </div>
        {{ Form::close() }}
        @can('create', App\Models\Task::class)
            <div class="ms-auto">
                <a href="{{ route('tasks.create') }}"
                   class="btn btn-primary ml-auto">{{ __('views.tasks.createTitle') }}</a>
            </div>
        @endcan
    </div>
    <table class="table me-2">
        <thead>
        <tr>
            <th>{{ __('views.tasks.id') }}</th>
            <th>{{ __('views.tasks.status') }}</th>
            <th>{{ __('views.tasks.name') }}</th>
            <th>{{ __('views.tasks.author') }}</th>
            <th>{{ __('views.tasks.executor') }}</th>
            <th>{{ __('views.tasks.creationDate') }}</th>
            @canany(['delete', 'update'], $tasks->all())
                <th>{{ __('views.tasks.actions') }}</th>
            @endcanany
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
                <td>{{ $task->createdBy->name }}</td>
                <td>{{ optional($task->assignedTo)->name }}</td>
                <td>{{ $task->created_at->format('d.m.Y') }}</td>
                <td>
                    @canany('update', $task)
                        <a class="text-decoration-none"
                           href="{{ route('tasks.edit', $task) }}">{{ __('views.tasks.change') }}</a>
                    @endcan
                    @canany('delete', $task)
                        <a
                            class="text-danger text-decoration-none"
                            href="{{ route('tasks.destroy', $task) }}"
                            data-confirm="{{ __('views.tasks.deleteConfirm') }}"
                            data-method="delete"
                        >{{ __('views.tasks.delete') }}</a>
                    @endcan
                </td>
            </tr>
        @endforeach
    </table>
    {{ $tasks->links() }}
@endsection
