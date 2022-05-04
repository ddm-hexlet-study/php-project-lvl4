<div class="form-group mb-3">
    {{ Form::label('name', 'Имя') }}
    {{ Form::text('name', null, ['class' => 'form-control']) }}
    @error('name')
    <span class="text-danger" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
<div class="form-group mb-3">
    {{ Form::label('description', 'Описание') }}
    {{ Form::textarea('description', null, ['class' => 'form-control']) }}
</div>
