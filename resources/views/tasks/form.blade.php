<div class="form-group mb-3">
    {{ Form::label('name', __('views.tasks.name')) }}
    {{ Form::text('name', null, ['class' => 'form-control']) }}
    @error('name')
    <span class="text-danger" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
<div class="form-group mb-3">
    {{ Form::label('description', __('views.tasks.description')) }}
    {{ Form::textarea('description', null, ['class' => 'form-control']) }}
</div>
<div class="form-group mb-3">
    {{ Form::label('status_id', __('views.tasks.status')) }}
    {{ Form::select('status_id', $statuses, null, ['placeholder' => '----------', 'class' => 'form-control']) }}
    @error('status_id')
    <span class="text-danger" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
<div class="form-group mb-3">
    {{ Form::label('assigned_to_id', __('views.tasks.executor')) }}
    {{ Form::select('assigned_to_id', $users, null, ['placeholder' => '----------', 'class' => 'form-control']) }}
</div>
<div class="form-group mb-3">
    {{ Form::label('labels', __('views.tasks.label')) }}
    {{ Form::select('labels[]', $labels, null, ['placeholder' => '', 'class' => 'form-control', 'multiple' => '']) }}
</div>
