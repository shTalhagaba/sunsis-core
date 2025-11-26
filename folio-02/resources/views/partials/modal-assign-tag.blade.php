<div class="widget-box widget-color-blue2 collapsed">
    <div class="widget-header">
        <h5 class="widget-title lighter smaller">Assign Tag</h5>
        <div class="widget-toolbar">
            <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down"></i></a>
        </div>
    </div>
    <div class="widget-body">
        {!! Form::open([
            'method' => 'POST',
            'url' => route('tags.assign'),
            'onsubmit' => 'return modal_assign_tag(this);',
        ]) !!}
        {!! Form::hidden('taggable_type', get_class($tagged_entity) ?? null) !!}
        {!! Form::hidden('taggable_id', $tagged_entity->id ?? null) !!}
        <div class="widget-main">
            <div class="form-group row {{ $errors->has('tag') ? 'has-error' : ''}}">
                {!! Form::label('tag', 'Select Tag', ['class' => 'col-sm-12 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::select('tag', $modal_assign_tags_options ?? App\Models\Tags\Tag::pluck('name', 'id')->toArray(), null, ['class' => 'form-control ', 'placeholder' => '']) !!}
                    {!! $errors->first('tag', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-12 text-center">
                    OR
                </div>
            </div>
            <div class="form-group row {{ $errors->has('new_tag') ? 'has-error' : ''}}">
                {!! Form::label('new_tag', 'Enter New Tag', ['class' => 'col-sm-12 control-label']) !!}
                <div class="col-sm-12">
                    {!! Form::text('new_tag', null, ['class' => 'form-control', 'maxlength' => '70']) !!}
                    {!! $errors->first('new_tag', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            
        </div>

        <div class="widget-toolbox padding-8 clearfix">
            <div class="center">
                <button class="btn btn-sm btn-success btn-round" type="submit">
                    <i class="ace-icon fa fa-save bigger-110"></i>
                    Save
                </button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>

@push('after-scripts')
    <script>
        function modal_assign_tag(form) {
            if ($(form).find("select[name=tag]").val() == '' && $(form).find("input[name=new_tag]").val().trim() == '') {
                return false;
            }

            $(form).find('button.btnModalAssignTagCancel').attr("disabled", true);
            $(form).find(':submit').attr("disabled", true);
            $(form).find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');

            return true;
        }
    </script>
@endpush
