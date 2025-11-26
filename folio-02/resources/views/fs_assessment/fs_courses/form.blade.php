

<div class="widget-box">
    <div class="widget-header">
        <h4 class="smaller">Details</h4>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <div class="form-group row required {{ $errors->has('title') ? 'has-error' : '' }}">
                {!! Form::label('title', 'Title', [
                    'class' => 'col-sm-4 control-label
                                no-padding-right',
                ]) !!}
                <div class="col-sm-8">
                    {!! Form::text('title', null, ['class' => 'form-control', 'required', 'maxlength' => 255]) !!}
                    {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row required {{ $errors->has('fs_type') ? 'has-error' : '' }}">
                {!! Form::label('fs_type', 'Type', [
                    'class' => 'col-sm-4 control-label
                                no-padding-right',
                ]) !!}
                <div class="col-sm-8">
                    {!! Form::select('fs_type', ['Maths' => 'Maths', 'English' => 'English', 'ICT' => 'ICT'], null, [
                                            'class' => 'form-control',
                                            'placeholder' => '',
                                            'required'
                                        ]) !!}
                    {!! $errors->first('fs_type', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row {{ $errors->has('details') ? 'has-error' : '' }}">
                {!! Form::label('details', 'Details', [
                    'class' => 'col-sm-4 control-label
                                no-padding-right',
                ]) !!}
                <div class="col-sm-8">
                    {!! Form::textarea('details', null, ['class' => 'form-control', 'rows' => 3, 'maxlength' => 800]) !!}
                    {!! $errors->first('details', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row {{ $errors->has('video_link') ? 'has-error' : '' }}">
                {!! Form::label('video_link', 'Video Link', [
                    'class' => 'col-sm-4 control-label
                                no-padding-right',
                ]) !!}
                <div class="col-sm-8">
                    {!! Form::text('video_link', null, ['class' => 'form-control', 'maxlength' => 2000]) !!}
                    {!! $errors->first('video_link', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
        </div>
        <div class="widget-toolbox padding-8 clearfix">
            <div class="center">
                <button class="btn btn-sm btn-success btn-round" type="submit">
                    <i class="ace-icon fa fa-save bigger-110"></i>
                    Save Information
                </button>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
    <script>
        $(document).ready(function() {

        });
    </script>
@endpush
