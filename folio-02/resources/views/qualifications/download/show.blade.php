@extends('layouts.master')

@section('title', 'Download Qualification')

@section('page-content')
    <div class="page-header">
        <h1>
            Download Qualification
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                download qualification from our central database
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('download_qualification.index') . '?keyword=' . $qualification->qan }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <h3 class="bolder text-info">
                        [{{ $qualification->qan }}] &nbsp; 
                        {{ $qualification->title }}
                    </h3>
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name"> Level</div>
                            <div class="info-div-value">
                                <span>
                                    <span>{{ $qualification->level }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Awarding Org. </div>
                            <div class="info-div-value"><span>{{ $qualification->awardingOrg->owner_org_name }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Type </div>
                            <div class="info-div-value"><span>{{ $qualification->qualType->description }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> SSA </div>
                            <div class="info-div-value"><span>{{ $qualification->qualSsa->description }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Status </div>
                            <div class="info-div-value"><span>{{ $qualification->qualStatus->description }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Regulation Start Date </div>
                            <div class="info-div-value">
                                <span>{{ $qualification->regulation_start_date }}</span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Operational Dates </div>
                            <div class="info-div-value"><span>{{ $qualification->operational_start_date }} -
                                    {{ $qualification->operational_end_date }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Certification End Date </div>
                            <div class="info-div-value">
                                <span>{{ $qualification->certification_end_date }}</span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Min. GLH </div>
                            <div class="info-div-value"><span>{{ $qualification->min_glh }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Max. GLH </div>
                            <div class="info-div-value"><span>{{ $qualification->max_glh }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> GLH </div>
                            <div class="info-div-value">
                                <span>{{ $qualification->glh }}</span> &nbsp; {!! $qualification->glh == $qualification->units->sum('glh')
                                    ? '<i data-rel="tooltip" title="Sum of units GLH equals this" class="fa fa-check-circle fa-lg"
                                                                                                            style="color: green;"></i>'
                                    : '<i data-rel="tooltip" title="Sum of units GLH does not equal this"
                                                                                                            class="fa fa-warning fa-lg" style="color: red;"></i>' !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Total Credits </div>
                            <div class="info-div-value">
                                <span>{{ $qualification->total_credits }}</span> &nbsp; {!! $qualification->total_credits == $qualification->units->sum('unit_credit_value')
                                    ? '<i data-rel="tooltip" title="Sum of units credit values equals this"
                                                                                                            class="fa fa-check-circle fa-lg" style="color: green;"></i>'
                                    : '<i data-rel="tooltip"
                                                                                                            title="Sum of units credit values does not equal this" class="fa fa-warning fa-lg"
                                                                                                            style="color: red;"></i>' !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Total Qualification Time </div>
                            <div class="info-div-value">
                                <span>{{ $qualification->total_qual_time != '' ? $qualification->total_qual_time . ' (hours)' : '' }}</span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Assessment Methods </div>
                            <div class="info-div-value"><span>{{ $qualification->assessment_methods }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="space"></div>
                    <h4 class="bolder text-info">
                        Units Selection
                    </h4>
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle-o"></i> 
                        @if($qualification->optionalUnitsCount() > 0)
                        This qualification has {{ $qualification->optionalUnitsCount() }} optional {{ \Str::plural('unit', $qualification->optionalUnitsCount()) }}. 
                        You can select which optional units to add.
                        @else
                        This qualification has no optional units. All of the following units will be added.
                        @endif
                    </div>

                    {!! Form::open([
                        'url' => route('download_qualification.store'), 
                        'class' => 'form-horizontal',
                        'name' => 'frmDownloadQual',
                    ]) !!}
                    {!! Form::hidden('qualification_id', $qualification->id) !!}
                    @foreach ($qualification->units as $unit)
                    <table class="table table-bordered">
                        <tr>
                            <td class="center" style="width: 2%" align="center">
                                @if ($unit->isMandatory())
                                <br>
                                    <i class="fa fa-check-circle green fa-2x" data-rel="tooltip"
                                        title="This unit is mandatory and will be added automatically."></i>
                                    <input name="chkUnit[]" id="chkUnit{{ $unit->id }}"
                                        value="{{ $unit->id }}" type="checkbox" checked
                                        style="display: none;" /> 
                                    &nbsp;                             
                                @else
                                <br>
                                    <div class="checkbox" title="Optional unit">
                                        <label>
                                            <input name="chkUnit[]" id="chkUnit{{ $unit->id }}"
                                                value="{{ $unit->id }}"
                                                class="chkUnit ace input-lg"
                                                {{ (is_array(old('chkUnit')) && in_array($unit->id, old('chkUnit'))) ? ' checked' : '' }}
                                                type="checkbox" />
                                            <span class="lbl"> </span>
                                        </label>
                                    </div> 
                                    &nbsp; 
                                @endif
                            </td>
                            <td>
                                <div class="widget-box transparent collapsed">
                                    <div class="widget-header">
                                        <h5 class="widget-title">
                                            <i class="fa fa-folder fa-lg"></i>
                                            [{{ $unit->unit_owner_ref }},&nbsp;{{ $unit->unique_ref_number }}]
                                            {{ $unit->title }}
                                        </h5>
                                        <div class="widget-toolbar">
                                            <a href="#" data-action="collapse" title="Click to view the list of criteria of this unit."><i
                                                    class="ace-icon fa fa-chevron-down"></i></a>
                                        </div>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            {{-- @forelse ($unit->pcs()->orderBy('pc_sequence')->get() as $pc) --}}
                                            @forelse ($unit->pcs as $pc)
                                                [{{ $pc->reference }}] {!! nl2br(e($pc->title)) !!}<hr style="margin-top: 10px; margin-bottom: 10px">
                                            @empty
                                                <span class="text-danger">
                                                    <i class="fa fa-triangle"></i> 
                                                    <i>This unit ([{{ $unit->unit_owner_ref }},&nbsp;{{ $unit->unique_ref_number }}] {{ $unit->title }}) has no criteria.</i>
                                                </span>                                        
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>            
                    @endforeach
                    <div class="widget-toolbox padding-8 clearfix">
                        <div class="center">
                            <button class="btn btn-sm btn-success btn-round" type="submit">
                                <i class="ace-icon fa fa-save bigger-110"></i>Download Qualification
                            </button>&nbsp; &nbsp; &nbsp;
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>


            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@section('page-inline-scripts')
    <script>

        $(function(){
            $("form[name=frmDownloadQual]").on('submit', function(){
                var form = $(this);
                form.find(':submit').attr("disabled", true);
                form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
                return true;
            });
        });

    </script>
@endsection
