<div class="row">
    <div class="col-xs-12">
        {!! Form::model($session->getAttributes(), [
            'method' => 'PATCH',
            'url' => route('trainings.sessions.save_view_or_sign', [$training, $session]),
            'class' => 'form-horizontal',
            'files' => true,
            'role' => 'form',
            'id' => 'frmSession',
        ]) !!}

        <div class="widget-box widget-color-green">
            <div class="widget-header">
                <h4 class="widget-title">Session Attendance</h4>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="row">
                        <div class="col-xs-3">
                            <span class="text-info bolder">Plan Month: </span> {{ optional($session->session_start_date)->format('M Y') }}
                            <div class="space-6"></div>
                            <span class="text-info bolder">Start Date:</span> {{ optional($session->session_start_date)->format('d/m/Y') }}
                        </div>
                        <div class="col-xs-9">
                            <div class="form-group row required {{ $errors->has('actual_date') ? 'has-error' : '' }}">
                                {!! Form::label('actual_date', 'Actual Date', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::date('actual_date', null, ['class' => 'form-control', 'required']) !!}
                                    {!! $errors->first('actual_date', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('session_start_time') ? 'has-error' : '' }}">
                                {!! Form::label('session_start_time', 'Session Start Time', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::time(
                                        'session_start_time',
                                        isset($session->session_start_time) ? \Carbon\Carbon::parse($session->session_start_time)->format('H:i') : null,
                                        ['class' => 'form-control', 'required'],
                                    ) !!}
                                    {!! $errors->first('session_start_time', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('session_end_time') ? 'has-error' : '' }}">
                                {!! Form::label('session_end_time', 'Session End Time', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::time(
                                        'session_end_time',
                                        isset($session->session_end_time) ? \Carbon\Carbon::parse($session->session_end_time)->format('H:i') : null,
                                        ['class' => 'form-control', 'required'],
                                    ) !!}
                                    {!! $errors->first('session_end_time', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('assessor_comments') ? 'has-error' : '' }}">
                                {!! Form::label('assessor_comments', 'Enter Your Comments', [
                                    'class' => 'col-sm-4 control-label no-padding-right',
                                ]) !!}
                                <div class="col-sm-8">
                                    {!! Form::textarea('assessor_comments', null, ['class' => 'form-control', 'required']) !!}
                                    {!! $errors->first('assessor_comments', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('session_evidence') ? 'has-error' : '' }}">
                                {!! Form::label('session_evidence', 'File/Resource', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    @include(
                                        'partials.ace_file_control',
                                        ['aceFileControlRequired' => false, 'aceFileControlId' => 'session_evidence', 'aceFileControlName' => 'session_evidence']
                                    )
                                    {!! $errors->first('session_evidence', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>

                             <div class="form-group required row {{ $errors->has('session_type') ? 'has-error' : '' }}">
                                    {!! Form::label('session_type', 'Visit Type', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('session_type', [
                                            'face_to_face' => 'Face-to-Face',
                                            'remote' => 'Remote'
                                        ], null, [
                                            'class' => 'form-control',
                                            'required'
                                        ]) !!}
                                        {!! $errors->first('session_type', '<p class="text-danger">:message</p>') !!}
                                    </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('assessor_sign') ? 'has-error' : '' }}">
                                {!! Form::label('assessor_sign', 'Tick this box to confirm your signature', [
                                    'class' => 'col-sm-4 control-label',
                                ]) !!}
                                <div class="col-sm-8">
                                    <div class="checkbox">
                                        <label class="block">
                                            <input name="assessor_sign" type="checkbox" class="ace input-lg" value="1"
                                                required>
                                            <span class="lbl bigger-120"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>                            
                        </div>
    
                    </div>
                </div>
                <div class="widget-toolbox padding-8 clearfix">
                    <div class="center">
                        <button class="btn btn-sm btn-success btn-round" type="submit">
                            <i class="ace-icon fa fa-save bigger-110"></i> Save Information
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>

@section('page-inline-scripts')
    <script type="text/javascript">
        $(function() {
            $('#frmSession').validate({
                errorElement: 'div',
                errorClass: 'help-block',
                focusInvalid: false,
                rules: {
                    actual_date: {
                        required: true
                    },
                    assessor_comments: {
                        required: true
                    },
                    assessor_sign: {
                        required: true
                    }
                },

                highlight: function(e) {
                    $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                },

                success: function(e) {
                    $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
                    $(e).remove();
                },

                errorPlacement: function(error, element) {
                    if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                        var controls = element.closest('div[class*="col-"]');
                        if (controls.find(':checkbox,:radio').length > 1) controls.append(error);
                        else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                    } else
                        error.insertAfter(element);
                },

                // On validation failure, re-enable the submit button
                invalidHandler: function(event, validator) {
                    $("form[id=frmSession]").find(':submit').attr("disabled", false);
                    $("form[id=frmSession]").find(':submit').html('<i class="ace-icon fa fa-save bigger-110"></i> Save Information');
                }

            });

            // Handle form submit
            $("form[id=frmSession]").on('submit', function() {
                var form = $(this);
                if (form.valid()) {
                    form.find(':submit').attr("disabled", true);
                    form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
                    return true;
                } else {
                    // Re-enable submit button if form is not valid
                    form.find(':submit').attr("disabled", false);
                    form.find(':submit').html('<i class="ace-icon fa fa-save bigger-110"></i> Save Information');
                    return false; // Prevent form submission if not valid
                }
            });
        });
    </script>
@endsection
