<div class="widget-box">
    <div class="widget-body">
        {!! Form::open([
            'url' => route('media.upload'),
            'files' => true,
            'id' => 'frmUploadMedia',
            'name' => 'frmUploadMedia',
        ]) !!}

        {!! Form::hidden('model_id', $associatedModel->id) !!}
        {!! Form::hidden('model_type', get_class($associatedModel)) !!}
        {!! Form::hidden('mediaSection', $sectionName) !!}
        {!! Form::hidden('collection_name', $collectionName ?? null) !!}

        <div class="widget-main">
            <div
                class="form-group {{ $errors->has('file') ? 'has-error' : '' }}">
                {!! Form::label('file', 'Select File *', ['class' => 'control-label no-padding-right']) !!}
                @include(
                    'partials.ace_file_control',
                    ['aceFileControlRequired' => true]
                )
                {!! $errors->first('file', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
        <div class="widget-toolbox padding-2 clearfix">
            <div class="center">
                <button
                    class="btn btn-sm btn-success btn-round"
                    type="submit">
                    <i
                        class="ace-icon fa fa-upload bigger-110"></i>
                    Upload File {{ $sectionName != '' ? 'In ' . Str::title(str_replace('-', ' ', $sectionName)) : '' }}
                </button>
                <div class="pull-left">
                    <span class="btn btn-xs btn-info btn-round" data-trigger="hover" data-rel="popover" data-original-title="File&nbsp;Size&nbsp;&&nbsp;Allowed&nbsp;Types" data-placement="auto" 
                        data-content="<p><strong>Maximum File Size: </strong>20MB</p><p><strong>Allowed File Types: </strong>{{ implode(", ", config('medialibrary.allowed_extensions')) }}</p>">
                        <i class="fa fa-info-circle" ></i>
                    </span>
                </div>
            </div>
            
        </div>
        {!! Form::close() !!}
    </div>
</div>

@push('after-scripts')
    <script>
        $("form[name=frmUploadMedia]").on('submit', function(){
            var form = $(this);
            form.find(':submit').attr("disabled", true);
            form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Uploading');
            form.closest('div.widget-box').widget_box('reload');
            return true;
        });
    </script>
@endpush