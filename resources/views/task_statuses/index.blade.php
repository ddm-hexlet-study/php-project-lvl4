@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('views.statuses.indexTitle') }}</h1>
    @if (Auth::check())
        <a href="{{ route('task_statuses.create') }}" class="btn btn-primary">
            {{ __('views.statuses.createTitle') }}        </a>
    @endif
    <table class="table mt-2">
        <thead>
        <tr>
            <th>{{ __('views.statuses.id') }}</th>
            <th>{{ __('views.statuses.name') }}</th>
            <th>{{ __('views.statuses.creationDate') }}</th>
            @canany(['delete', 'update'], $statuses->all())
                <th>{{ __('views.statuses.actions') }} </th>
            @endcanany
        </tr>
        </thead>
        @foreach ($statuses as $status)
            <tr>
                <td>{{ $status->id }}</td>
                <td>{{ $status->name }}</td>
                <td>{{ $status->created_at }}</td>
                <td>
                    @can('update', $status)
                        <a class="text-decoration-none" href="{{ route('task_statuses.edit', $status) }}"
                        >{{ __('views.statuses.change') }}</a>
                    @endcan
                    @can('delete', $status)
                        <a
                            class="text-danger text-decoration-none"
                            href="{{ route('task_statuses.destroy', $status) }}"
                            data-confirm="{{ __('views.statuses.deleteConfirm') }}"
                            data-method="delete"
                        >{{ __('views.statuses.delete') }}</a>
                    @endcan
                    </td>
            </tr>
        @endforeach
    </table>

@endsection
