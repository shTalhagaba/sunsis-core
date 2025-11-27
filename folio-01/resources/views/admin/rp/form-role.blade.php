@php
    $is_protected_role = false;
    $protected_roles = [
        'Administrator',
        'Assessor',
        'Tutor',
        'Student',
        'Verifier'
    ];
    if(isset($role) && in_array($role->name, $protected_roles))
        $is_protected_role = true;
@endphp

<div class="widget-box widget-color-green">
    <div class="widget-header">
        <h4 class="widget-title">Role Details</h4>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <div class="form-group row required {{ $errors->has('name') ? 'has-error' : ''}}">
                {!! Form::label('name', 'Role Name', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                <div class="col-sm-8">
                    @if ($is_protected_role)
                    {!! Form::hidden('name', $role->name) !!}
                    <h4>{{ $role->name }}</h4>
                    @else
                    {!! Form::text('name', null, ['class' => 'form-control', 'required', 'maxlength' => '100']) !!}
                    @endif
                    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group row {{ $errors->has('description') ? 'has-error' : ''}}">
                {!! Form::label('description', 'Role Description', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
                    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                </div>
            </div>

            <div class="form-group row {{ $errors->has('permissions') ? 'has-error' : ''}}">
                {!! Form::label('permissions', 'Select Permissions', ['class' => 'col-sm-12']) !!} &nbsp;
                {!! $errors->first('permissions', '<p class="help-block">:message</p>') !!}
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table id="tblPermissions" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="center"><label class="pos-rel"><input type="checkbox" class="ace" /> <span class="lbl"></span></label></th>
                                    <th>Permission Name</th>
                                    <th>Permission Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($permissions AS $permission)
                                <tr>
                                    <td class="center">
                                        <label class="pos-rel">
                                            <input type="checkbox" class="ace" name="permissions[]" value="{{ $permission->id }}"
                                            {{ isset($role) && $role->hasPermissionTo($permission) ? 'checked' : '' }} />
                                            <span class="lbl"></span>
                                        </label>
                                    </td>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ $permission->description }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3">No permission found. Please create permission(s) first and then create a new role.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                {!! $errors->first('permissions', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
        <div class="widget-toolbox padding-8 clearfix">
            <div class="clearfix center">
                <div class="col-md-offset-3 col-md-9">
                    <button class="btn btn-success btn-sm btn-round" type="submit">
                        <i class="ace-icon fa fa-save bigger-110"></i>
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


