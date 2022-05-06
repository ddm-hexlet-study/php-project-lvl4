@extends('layouts.app')

@section('content')
    <h1 class="mb-5">{{ __('views.labels.indexTitle') }}</h1>
    @if (Auth::check())
        <a href="{{ route('labels.create') }}" class="btn btn-primary">
            {{ __('views.labels.createTitle') }}</a>
    @endif
    <table class="table mt-2">
        <thead>
        <tr>
            <th>{{ __('views.labels.id') }}</th>
            <th>{{ __('views.labels.name') }}</th>
            <th>{{ __('views.labels.description') }}</th>
            <th>{{ __('views.labels.creationDate') }}</th>
            @canany(['delete', 'update'], $labels->all())
                <th>{{ __('views.labels.actions') }}</th>
            @endcanany
        </tr>
        </thead>
        @foreach($labels as $label)
            <tr>
                <td>{{ $label->id }}</td>
                <td>{{ $label->name }}</td>
                <td>{{ $label->description ?? '' }}</td>
                <td>{{ $label->created_at }}</td>
                <td>
                    @can('update', $label)
                        <a class="text-decoration-none" href="{{ route('labels.edit', $label) }}">
                            {{ __('views.labels.change') }}</a>
                    @endcan
                    @can('delete', $label)
                        <a
                            class="text-danger text-decoration-none"
                            href="{{ route('labels.destroy', $label) }}"
                            data-confirm="__('views.labels.deleteConfirm')"
                            data-method="delete"
                        >
                            {{ __('views.labels.delete') }}</a>
                    @endcan
                </td>
            </tr>
        @endforeach
    </table>
@endsection
