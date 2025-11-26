@extends('layouts.master')

@section('title', 'IQA Planning')

@section('page-content')
    <div class="page-header">
        <h1>IQA Planning</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('iqa_sample_plans.index') }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')
            @include('partials.session_error')

            <div id="row">
                <div class="col-xs-12">
                    <div class="space"></div>

                    {!! Form::open(['url' => route('iqa_sample_plans.store'), 'class' => 'form-horizontal']) !!}
                    <div class="widget-box widget-color-green">
                        <div class="widget-header">
                            <h4 class="widget-title">IQA Plan Basic Details</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                @if (auth()->user()->isVerifier())
                                    {!! Form::hidden('verifier_id', auth()->user()->id, ['id' => 'verifier_id']) !!}
                                @else
                                    <div
                                        class="form-group row required {{ $errors->has('verifier_id') ? 'has-error' : '' }}">
                                        {!! Form::label('verifier_id', 'Verifier', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('verifier_id', $verifiers, null, [
                                                'class' => 'form-control',
                                                'placeholder' => '',
                                                'required',
                                                'id' => 'verifier_id',
                                            ]) !!}
                                            {!! $errors->first('verifier_id', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                @endif
                                <div
                                    class="form-group row required {{ $errors->has('assessor_id') ? 'has-error' : '' }}">
                                    {!! Form::label('assessor_id', 'Assessor', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('assessor_id', [], null, [
                                            'class' => 'form-control',
                                            'placeholder' => '',
                                            'required',
                                            'id' => 'assessor_id',
                                        ]) !!}
                                        {!! $errors->first('assessor_id', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div
                                    class="form-group row required {{ $errors->has('learning_aim_id') ? 'has-error' : '' }}">
                                    {!! Form::label('learning_aim_id', 'Learning Aim', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('learning_aim_id', [], null, [
                                            'class' => 'form-control',
                                            'placeholder' => '',
                                            'required',
                                            'id' => 'learning_aim_id',
                                        ]) !!}
                                        {!! $errors->first('learning_aim_id', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="widget-toolbox padding-8 clearfix">
                                <div class="center">
                                    <button class="btn btn-sm btn-success btn-round" type="submit">
                                        <i class="ace-icon fa fa-save bigger-110"></i> Save Basic Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div><!-- /.span -->
            </div><!-- /.user-profile -->


            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@push('after-scripts')
    <script>
        function loadAssessors(verifierId) {
            // Reset qualification
            $('#learning_aim_id').html('<option value="">Select Qualification</option>').prop('disabled', true);

            if (verifierId) {
                $.get('/getVerifierLinkedAssessors/' + verifierId, function (data) {
                    $('#assessor_id').prop('disabled', false).html('<option value="">Select Assessor</option>');
                    $.each(data, function (id, name) {
                        $('#assessor_id').append('<option value="' + id + '">' + name + '</option>');
                    });
                });
            } else {
                $('#assessor_id').html('<option value="">Select Assessor</option>').prop('disabled', true);
            }
        }

        $(function(){
            @if (auth()->user()->isVerifier())
                loadAssessors({{ auth()->user()->id }});
            @endif

            $('#verifier_id').on('change', function () {
                var verifierId = $(this).val();

                loadAssessors(verifierId);
            });

            $('#assessor_id').on('change', function () {
                var assessorId = $(this).val();
                var verifierId = $('#verifier_id').val();

                if (assessorId && verifierId) {
                    $.get('/getVerifierAndAssessorLinkedQualifications?verifier_id=' + verifierId + '&assessor_id=' + assessorId, function (data) {
                        $('#learning_aim_id').prop('disabled', false).html('<option value="">Select Qualification</option>');
                        $.each(data, function (id, name) {
                            $('#learning_aim_id').append('<option value="' + id + '">' + name + '</option>');
                        });
                    });
                } else {
                    $('#learning_aim_id').html('<option value="">Select Qualification</option>').prop('disabled', true);
                }
            });

        });
    </script>
@endpush
