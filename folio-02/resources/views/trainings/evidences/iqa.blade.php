@extends('layouts.master')

@section('title', 'IQA Evidence')

@section('page-plugin-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
    <style>

    </style>
@endsection

@section('page-content')
    <div class="page-header">
        <h1>IQA Evidence <small>{{ $training->system_ref }}</small></h1>
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

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h5 class="widget-title smaller">Evidence Details</h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="tabbable">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#tabEvidence" data-toggle="tab">Details</a></li>
                                        <li><a href="#tabEvidenceSubmissions" data-toggle="tab">Submissions</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane in active" id="tabEvidence">
                                            @include('trainings.evidences.partials.evidence-details', [
                                                '_evi_details' => $evidence,
                                            ])
                                        </div>
                                        <div class="tab-pane" id="tabEvidenceSubmissions">
                                            @include('trainings.evidences.partials.evidence-submissions', [
                                                '_evi_details' => $evidence,
                                            ])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>

            @if(! $evidence->isIqaAccpeted() )
            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box  widget-color-blue2 light-border">
                        <div class="widget-header">
                            <h5 class="widget-title">IQA Assessment</h5>
                        </div>
                        <div class="widget-body">
                            {!! Form::open([
                                'url' => route('trainings.evidences.saveIqaAssessment', [$training, $evidence]),
                                'class' => 'form-horizontal',
                            ]) !!}
                            <div class="widget-main">
                                
                                <div class="form-group row {{ $errors->has('iqa_status') ? 'has-error' : '' }}">
                                    {!! Form::label('iqa_status', 'IQA Status', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('iqa_status', App\Models\Training\PortfolioUnitIqa::getStatusList(), null, [
                                            'class' => 'form-control',
                                            'required',
                                            'placeholder' => '',
                                        ]) !!}
                                        {!! $errors->first('iqa_status', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('verifier_comments') ? 'has-error' : '' }}">
                                    {!! Form::label('verifier_comments', 'IQA Comments', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::textarea('verifier_comments', null, [
                                            'class' => 'form-control',
                                            'rows' => '10',
                                            'id' => 'verifier_comments',
                                            'required',
                                        ]) !!}
                                        {!! $errors->first('verifier_comments', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                
                                
                            </div>
                            <div class="widget-toolbox padding-8 clearfix">
                                <div class="center">
                                    <button class="btn btn-sm btn-success btn-round" type="submit">
                                        <i class="ace-icon fa fa-save bigger-110"></i>
                                        Save Assessment
                                    </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box transparent ui-sortable-handle">
                        <div class="widget-header">
                            <h5 class="widget-title">Mapping <small><i class="ace-icon fa fa-angle-double-right"></i> Units
                                    and PCs this evidence is mapped to</small></h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <table class="table table-bordered">

                                    @forelse ($result AS $row)
                                        @if ($loop->first)
                                            <tr>
                                                <td><i class="fa fa-graduation-cap fa-2x"></i></td>
                                                <td colspan="3">[{{ $row->qan }}] {{ $row->portfolio_title }}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td><i class="fa fa-folder fa-lg"></i></td>
                                                <td colspan="2">[{{ $row->unit_owner_ref }},
                                                    {{ $row->unique_ref_number }}] {{ $row->unit_title }}</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td><i class="fa fa-folder-open"></i></td>
                                                <td>
                                                    [{{ $row->reference }}] {{ $row->pc_title }}
                                                    @if ($row->assessor_signoff == '1')
                                                        &nbsp; <span
                                                            class="label label-success arrowed-in arrowed-in-right pull-right">signed
                                                            off</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif

                                        @if ($loop->last && $result->count() > 1)
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td><i class="fa fa-folder-open"></i></td>
                                                <td>
                                                    [{{ $row->reference }}] {{ $row->pc_title }}
                                                    @if ($row->assessor_signoff == '1')
                                                        &nbsp; <span
                                                            class="label label-success arrowed-in arrowed-in-right pull-right">signed
                                                            off</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif

                                        @if (!$loop->first && !$loop->last)
                                            @if ($row->portfolio_title != $result[$loop->index - 1]->portfolio_title)
                                                <tr>
                                                    <td><i class="fa fa-graduation-cap fa-2x"></i></td>
                                                    <td colspan="3">[{{ $row->qan }}] {{ $row->portfolio_title }}
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($row->unit_title != $result[$loop->index - 1]->unit_title)
                                                <tr>
                                                    <td></td>
                                                    <td><i class="fa fa-folder fa-lg"></i></td>
                                                    <td colspan="2">[{{ $row->unit_owner_ref }},
                                                        {{ $row->unique_ref_number }}] {{ $row->unit_title }}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td><i class="fa fa-folder-open"></i></td>
                                                <td>
                                                    [{{ $row->reference }}] {{ $row->pc_title }}
                                                    @if ($row->assessor_signoff == '1')
                                                        &nbsp; <span
                                                            class="label label-success arrowed-in arrowed-in-right pull-right">signed
                                                            off</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif

                                    @empty
                                        <tr>
                                            <td colspan="3">Not mapped to any unit or pc.</td>
                                        </tr>
                                    @endforelse
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PAGE CONTENT ENDS -->

        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
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
        });
    </script>
@endsection
