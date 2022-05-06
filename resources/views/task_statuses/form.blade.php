<div class="form-group mb-3">
    {{ Form::label('name', __('views.statuses.name')) }}
    {{ Form::text('name', null, ['class' => 'form-control']) }}
    @error('name')
    <span class="text-danger" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
