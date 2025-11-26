@extends('layouts.master')
@section('title', 'Qualifications')
@section('page-plugin-styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection
@section('page-inline-styles')
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('qualifications.show', $qualification) }}
@endsection

@section('page-content')
    <div class="page-header">
        <h1>View Qualification</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('qualifications.index') }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>
            {{-- <button class="btn btn-sm btn-primary btn-bold btn-round" type="button"
                onclick="window.location.href='{{ route('qualifications.copy', $qualification) }}'">
                <i class="ace-icon fa fa-copy bigger-120"></i> Copy Qualification
            </button> --}}

            <div class="btn-group">
                <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle btn-round" aria-expanded="false">
                    Actions
                    <i class="ace-icon fa fa-angle-down icon-on-right"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('qualifications.edit', $qualification) }}"><i class="ace-icon fa fa-edit bigger-120"></i> Edit Qualification</a>
                    </li>
                    <li>
                        <a href="{{ route('qualifications.units.createMultiple', $qualification) }}"><i class="ace-icon fa fa-plus bigger-120"></i> Add Multiple Units</a>
                    </li>
                    <li>
                        <a href="{{ route('qualifications.exportSingleQualification', $qualification) }}"><i class="ace-icon fa fa-file-excel-o bigger-120"></i> Export Qualification</a>
                    </li>
                    <li class="divider"></li>
                    {!! Form::open([
                        'method' => 'DELETE',
                        'url' => route('qualifications.destroy', [$qualification]),
                        'style' => 'display: inline;',
                        'class' => 'form-inline frmDeleteQualification',
                    ]) !!}
                        <li class="text-center">
                            {!! Form::button('<i class="ace-icon fa fa-trash bigger-120"></i> Delete Qualification', [
                                'class' => 'btn btn-danger btn-xs btn-round btnDeleteQualification',
                                'id' => 'btnDeleteQualification' . $qualification->id,
                                'type' => 'submit',
                                'style' => 'display: inline',
                            ]) !!} &nbsp; 
                        </li>
                    {!! Form::close() !!}
                </ul>
            </div>

            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')


            <div class="row">
                <div class="col-md-7">
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name"> Level & Status</div>
                            <div class="info-div-value">
                                <span>
                                    <span
                                        class="label label-md label-info arrowed-in-right arrowed-in">{{ $qualification->level }}</span>
                                    @php
                                        switch ($qualification->getOriginal('status')) {
                                            case '2':
                                                $status_color = 'warning';
                                                break;
                                            case '3':
                                                $status_color = 'danger';
                                                break;
                                            case '4':
                                                $status_color = 'default';
                                                break;
                                            default:
                                                $status_color = 'success';
                                                break;
                                        }
                                    @endphp
                                    <span
                                        class="label label-md label-{{ $status_color }} arrowed-in-right arrowed-in">{{ $qualification->status }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Qualification Number </div>
                            <div class="info-div-value"><span>{{ $qualification->qan }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Qualification Title </div>
                            <div class="info-div-value"><span>{{ $qualification->title }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Awarding Org. </div>
                            <div class="info-div-value"><span>{{ $qualification->owner_org_name }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Type </div>
                            <div class="info-div-value"><span>{{ $qualification->type }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> SSA </div>
                            <div class="info-div-value"><span>{{ $qualification->ssa }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Status </div>
                            <div class="info-div-value"><span>{{ $qualification->status }}</span></div>
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
                            <div class="info-div-name"> Overall Grading Type </div>
                            <div class="info-div-value"><span>{{ $qualification->overall_grading_type }}</span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Assessment Methods </div>
                            <div class="info-div-value"><span>{{ $qualification->assessment_methods }}</span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Specification </div>
                            <div class="info-div-value"><span><a target="_blank"
                                        href="{{ $qualification->link_to_specs }}">
                                        {{ str_limit($qualification->link_to_specs, 70) }}</a></span></div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    @include('partials.tags_widget', [
                        '_entity' => $qualification,
                        'tagTypeDesc' => 'Qualification',
                    ])
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="bolder">
                        Units
                         
                        <span data-rel="tooltip" title="Number of total units" class="badge badge-default">Total Units:
                            {{ $qualification->units->count() }}</span>
                        <span data-rel="tooltip" title="Number of mandatory units" class="badge badge-success">Mandaotry
                            Units:
                            {{ $mandatoryUnits->count() }}</span>
                        <span data-rel="tooltip" title="Number of optional units" class="badge badge-info">Optional Units:
                            {{ $optionalUnits->count() }}</span>
                        <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-info btn-xs dropdown-toggle btn-round" aria-expanded="false">
                                <i class="ace-icon fa fa-angle-down icon-on-right"></i>
                            </button>
                            <ul class="dropdown-menu">
                                @foreach ($qualification->units as $unit)
                                <li>
                                    <a href="#{{ $unit->unit_owner_ref }}">{{ $unit->unit_owner_ref }}, {{ $unit->unique_ref_number }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </h3>

                    <button type="button" class="btn btn-primary btn-round btn-bold btn-xs pull-left"
                        onclick="window.location.href='{{ route('qualifications.units.create', $qualification) }}'">
                        <i class="ace-icon fa fa-plus bigger-120"></i>
                        <span class="bigger-110">Add Single Unit</span>
                    </button>
                </div>

                <div class="col-sm-12">
                    <div class="space-4"></div>
                    @foreach ($qualification->units as $unit)
                        @include('partials.qualification_unit_with_pcs', [
                            'unit' => $unit,
                            'withButtonsToolbar' => true,
                            'unitEditUrl' => route('qualifications.units.edit', [$qualification, $unit]),
                            'unitDeleteUrl' => route('qualifications.units.destroy', [$qualification, $unit]),
                            'extraUnitPanelClasses' => 'UnitPanel' . $qualification->id,
                            'panelShowHide' => true,
                        ])
                    @endforeach
                </div>
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection

@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')

    <script type="text/javascript">
        $(function() {
            $('.show-details-btn').on('click', function(e) {
                e.preventDefault();
                $(this).closest('tr').next().toggleClass('open');
                $(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass(
                    'fa-angle-double-up');
            });
            $('[data-rel=tooltip]').tooltip();

        });

        function showHideAll() {
            console.log('inside function');
            $('.show-details-btn').each(function(i, obj) {
                $(this).closest('tr').next().toggleClass('open');
                $(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass(
                    'fa-angle-double-up');
            });
        }

        $('.btnDeleteQualification').on('click', function(e) {
            e.preventDefault();
            var form = this.closest('form');
            bootbox.confirm({
                title: 'Confirm Delete?',
                message: 'This action is irreversible, are you sure you want to continue?',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-sm btn-round'
                    },
                    confirm: {
                        label: '<i class="fa fa-trash-o"></i> Yes Delete',
                        className: 'btn-sm btn-danger btn-round'
                    }
                },
                callback: function(result) {
                    if (result) {
                        $('.loader').show();
                        $(form).find(':submit').attr("disabled", true);
                        $(form).find(':submit').html('<i class="fa fa-spinner fa-spin"></i>');
                        form.submit();
                    }
                }
            });
        });
    </script>

    @include('partials.qualification_unit_with_pcs_script')

@endsection
