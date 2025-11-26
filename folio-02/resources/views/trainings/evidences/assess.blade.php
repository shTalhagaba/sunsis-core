@extends('layouts.master')

@section('title', 'Assess Evidence')

@section('page-plugin-styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <style>
        .circle-icon {
            background: #ffc0c0;
            padding: 5px;
            border-radius: 50%;
        }

        .popover {
            max-width: 600px;
        }
    </style>
@endsection

@section('page-content')
    <div class="page-header">
        <h1>Check Evidence <small>{{ $training->system_ref }}</small></h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">

            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.show', $training) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            <div class="space-12"></div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h5 class="widget-title smaller">Evidence Details</h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                @include('trainings.evidences.partials.evidence-details', [
                                    '_evi_details' => $evidence,
                                ])
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>

            @if ($evidence->assessments()->count() > 0)
                <div class="row">
                    <div class="col-sm-12">
                        <div class="widget-box transparent">
                            <div class="widget-header">
                                <h5 class="widget-title smaller">Evidence Assessment</h5>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main">
                                    @forelse ($evidence->assessments as $assessment)
                                        <div class="profile-user-info profile-user-info-striped">
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Date and Time </div>
                                                <div class="profile-info-value">
                                                    <span>{{ $assessment->created_at->format('d/m/Y H:i:s') }}</span></div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Assessment Type </div>
                                                <div class="profile-info-value">
                                                    <span>{{ $assessment->typeDescription() }}</span>
                                                </div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Assessment Status </div>
                                                <div class="profile-info-value">
                                                    <span>{{ $assessment->statusDescription() }}</span>
                                                </div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Assessment Comments </div>
                                                <div class="profile-info-value">
                                                    <span>{!! nl2br(e($assessment->assessment_comments)) !!}</span><br>
                                                    @if ($assessment->media()->count() > 0)
                                                        @include('partials.file_media_well', [
                                                            'fileMedia' => $assessment->media()->first(),
                                                        ])
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="space-4"></div>
                                    @empty
                                        <i class="fa fa-info-circle"></i> This evidence has not been assessed yet.
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>

            @endif

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box  widget-color-blue2 light-border">
                        <div class="widget-header">
                            <h5 class="widget-title">Assessment Information</h5>
                        </div>
                        <div class="widget-body">
                            {!! Form::open([
                                'url' => route('trainings.evidences.saveAssessment', [$training, $evidence]),
                                'class' => 'frmEvidenceAssessment form-horizontal',
                                'files' => true,
                            ]) !!}
                            <div class="widget-main">
                                <div class="form-group row {{ $errors->has('evidence_status') ? 'has-error' : '' }}">
                                    {!! Form::label('evidence_status', 'Evidence Status', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('evidence_status', $assessment_ddl, null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                        {!! $errors->first('evidence_status', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('evidence_categories') ? 'has-error' : '' }}">
                                    {!! Form::label('evidence_categories', 'Evidence Categories', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        @foreach (App\Models\Lookups\TrainingEvidenceCategoryLookup::getSelectData() as $categoryKey => $categoryValue)
                                            <div class="col-sm-4">
                                                <div class="control-group">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input name="evidence_categories[]" class="ace ace-checkbox-2"
                                                                type="checkbox" value="{{ $categoryKey }}"
                                                                {!! in_array($categoryKey, $selectedCategories) ? 'checked' : '' !!}>
                                                            <span class="lbl"> {{ $categoryValue }}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('assessor_comments') ? 'has-error' : '' }}">
                                    {!! Form::label('assessor_comments', 'Assessor Comments', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::textarea('assessor_comments', null, [
                                            'class' => 'form-control',
                                            'rows' => '10',
                                            'id' => 'assessor_comments',
                                        ]) !!}
                                        {!! $errors->first('assessor_comments', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('assessment_feedback_file') ? 'has-error' : '' }}">
                                    {!! Form::label('assessment_feedback_file', 'Upload File', [
                                        'class' => 'col-sm-4 control-label no-padding-right',
                                    ]) !!}
                                    <div class="col-sm-8">
                                        @include('partials.ace_file_control', [
                                            'aceFileControlRequired' => false,
                                            'aceFileControlId' => 'assessment_feedback_file',
                                            'aceFileControlName' => 'assessment_feedback_file',
                                        ])
                                        {!! $errors->first('assessment_feedback_file', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>

                                @include('trainings.evidences.partials.mapping_form', [
                                    'training' => $training,
                                    'evidence' => $evidence,
                                ])
                            </div>
                            <div class="widget-toolbox padding-8 clearfix">
                                <div class="center">
                                    @if (auth()->user()->isStaff() && auth()->user()->can('assess-evidence'))
                                        <button class="btn btn-sm btn-success btn-round" type="submit">
                                            <i class="ace-icon fa fa-save bigger-110"></i>
                                            Save Assessment
                                        </button>
                                    @endif
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- PAGE CONTENT ENDS -->

        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')
    <script type="text/javascript">
        $('input[type=radio][name=evidence_type]').on('click', function() {
            $('input[type=radio][name=evidence_type]').each(function() {
                $('#' + this.value).hide();
            });
            $('#' + this.value).show();
        });

        $(function() {
            $('[data-rel=tooltip]').tooltip();
            $('[data-rel=popover]').popover({
                html: true
            });

            $('input[type="checkbox"][name="chkPC[]"]').each(function() {
                if (this.checked) {
                    var unit_number = this.id.replace('pc' + this.value + 'OfUnit', '');
                    $('input[type="checkbox"][id="chkUnit' + unit_number + '"]').prop('checked', true);
                }
            });

            $('input[type=checkbox][id^=chkUnit]').on('click', function() {
                var unit_number = this.id.replace('chkUnit', '');
                if (this.checked) {
                    $("input[type='checkbox'][id$='OfUnit" + unit_number + "']").each(function() {
                        $(this).prop('checked', true);
                    });
                } else {
                    $("input[type='checkbox'][id$='OfUnit" + unit_number + "']").each(function() {
                        $(this).prop('checked', false);
                    });
                }
            });

            $('input[type="checkbox"][name="chkPC[]"]').on('click', function() {
                var unit_number = this.id.replace('pc' + this.value + 'OfUnit', '');
                if (this.checked) // if pc is clicked then check the Unit checkbox too.
                {
                    $('input[type="checkbox"][id="chkUnit' + unit_number + '"]').prop('checked', true);
                } else // if all pcs of a unit are unticked then untick the unit
                {
                    var allPCUnChecked = true;
                    $("input[type='checkbox'][id$='OfUnit" + unit_number + "']").each(function() {
                        if (this.checked) {
                            allPCUnChecked = false;
                            return false;
                        }
                    });
                    if (allPCUnChecked) {
                        $('input[type="checkbox"][id="chkUnit' + unit_number + '"]').prop('checked', false);
                    }
                }

            });

            $('.frmEvidenceAssessment').submit(function(e) {
                e.preventDefault();
                var currentForm = this;
                var evidence_status = currentForm.evidence_status.value;
                if (evidence_status == '') {
                    return $.alert({
                        title: 'Validation Error!',
                        content: 'Please select the evidence status.',
                        type: 'red',
                        action: function() {
                            return false;
                        }
                    });
                }
                if (currentForm.assessor_comments.value == '') {
                    return $.alert({
                        title: 'Validation Error!',
                        content: 'Please provide your comments.',
                        type: 'red',
                        action: function() {
                            return false;
                        }
                    });
                }
                if ($(this).find('input[name="chkPC[]"]:checked').length == 0 && evidence_status == 2) {
                    return $.alert({
                        title: 'Validation Error!',
                        content: 'You are going to accept this evidence but you have not selected any performance criteria. Please select the criteria this evidence satisfies.',
                        type: 'red',
                        action: function() {
                            return false;
                        }
                    });
                }

                var message = 'You are going to accept this evidence, are you sure you want to continue?';
                if (evidence_status == 3)
                    message = 'You are going to reject this evidence, are you sure you want to continue?';


                $.confirm({
                    title: "Confirmation",
                    content: message,
                    icon: 'fa fa-question-circle',
                    animation: 'scale',
                    theme: 'bootstrap',
                    closeIcon: true,
                    type: 'orange',
                    buttons: {
                        Cancel: {},
                        Confirm: function() {
                            currentForm.submit();
                        }
                    }
                });
            });

        });

        function showUnitEvidencesRows(unit_id, element) {
            var rows_id = 'RowOfUnit' + unit_id + 'Evidence';
            $("tr[id^=" + rows_id + "]").toggle();
            $(element).toggleClass('fa-chevron-down fa-chevron-up');
        }
    </script>
@endsection
