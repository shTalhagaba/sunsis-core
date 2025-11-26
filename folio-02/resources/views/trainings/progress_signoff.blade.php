@extends('layouts.master')

@section('title', 'Signoff Portfolio Progress')

@section('page-plugin-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
    <style>
        .popover {
            max-width: 600px;
        }

        .icon-wrapper {
            position: relative;
            display: inline-block;
        }

        .icon-wrapper i {
            position: absolute;
            top: 0;
            left: 0;
            transition: opacity 0.3s ease;
        }

        .icon-wrapper i.hidden-icon {
            opacity: 0;
            visibility: hidden;
        }

        .icon-wrapper:hover i.hidden-icon {
            opacity: 0.7; 
            visibility: visible;
        }

        .icon-wrapper:hover i.green {
            opacity: 0.5; 
        }

    </style>
@endsection

@section('page-content')
    <div class="page-header">
        <h1>Signoff Progress <small>{{ $training->system_ref }}</small></h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">

            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.show', $training) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => false])

            <div class="space-12"></div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box widget-color-green">
                        <div class="widget-header">
                            <h5 class="widget-title">
                                Signoff Progress
                                <small class="white"><i class="ace-icon fa fa-angle-double-right"></i> select the
                                    performance criteria you want to signoff.</small>
                            </h5>
                        </div>
                        <div class="widget-body">
                            {!! Form::open([
                                'url' => route('trainings.portfolios.saveSignoffProgress', [$training, $portfolio]),
                                'class' => 'form-horizontal',
                                'name' => 'frmSignoffProgress',
                            ]) !!}
                            <div class="widget-main">
                                <p class="alert alert-info">Following are the units and performance criteria of this
                                    portfolio which have been mapped to the evidences.
                                    You can now signoff the performance criteria using this functionality.
                                </p>
                                <div class="responsive">
                                    <div class="widget-box transparent ui-sortable-handle">
                                        <div class="widget-header">
                                            <h5 class="widget-title">
                                                <i class="fa fa-graduation-cap"></i> <strong>{{ $portfolio->qan }}
                                                    {{ $portfolio->title }}</strong>
                                            </h5>
                                            <div class="widget-toolbar">
                                                <a href="#" data-action="collapse"><i
                                                        class="ace-icon fa fa-chevron-down"></i></a>
                                            </div>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                @foreach ($portfolio->units as $unit)
                                                    <table class="table table-bordered table-hover">
                                                        <tr>
                                                            <th class="brown" colspan="3"><i
                                                                    class="fa fa-folder fa-lg"></i>
                                                                [{{ $unit->unit_owner_ref }},
                                                                {{ $unit->unique_ref_number }}] <h5
                                                                    style="display: inline;">{{ $unit->title }}</h5>
                                                            </th>
                                                            <th class="center" style="width: 8%;">
                                                                @if (!$unit->isSignedOff() && $unit->isAnyPCReadyForSignoff() > 0)
                                                                    <div class="checkbox">
                                                                        <label>
                                                                            <input name="chkUnit[]"
                                                                                id="chkUnit{{ $unit->id }}"
                                                                                value="{{ $unit->id }}"
                                                                                class="ace ace-checkbox-2 chkUnit"
                                                                                type="checkbox" />
                                                                            <span class="lbl"> </span>
                                                                        </label>
                                                                    </div>
                                                                @endif
                                                            </th>
                                                        </tr>
                                                        @foreach ($unit->pcs as $pc)
                                                            <tr style="cursor: pointer;">
                                                                <td class="{{ $pc->assessor_signoff == 0 ? 'blue' : 'green' }}"
                                                                    style="width: 75%;" id="tdPcId{{ $pc->id }}PcTitle">
                                                                    <i class="fa fa-folder-open"></i>
                                                                    [{{ $pc->reference }}] {!! nl2br($pc->title) !!}</span>
                                                                </td>
                                                                <td>
                                                                    @foreach ($pc->mapped_evidences as $evidence)
                                                                        @include(
                                                                            'trainings.evidences.partials.evidence_popover',
                                                                            ['_evidence_popover' => $evidence]
                                                                        )
                                                                    @endforeach
                                                                </td>
                                                                <td
                                                                    title="Number of evidences accepted / Number of evidences required">
                                                                    {{-- {{ $pc->mapped_evidences()->where('evidence_status', App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED)->count() }}/{{ $pc->min_req_evidences }} --}}
                                                                    {{ $pcsStats[$pc->id] }}/{{ $pc->min_req_evidences }}
                                                                </td>
                                                                <td class="center" align="center">
                                                                    @if ($pc->isReadyForSignOff())
                                                                        <div class="checkbox">
                                                                            <label>
                                                                                <input name="chkPC[]"
                                                                                    id="pc{{ $pc->id }}OfUnit{{ $unit->id }}"
                                                                                    value="{{ $pc->id }}"
                                                                                    class="ace ace-checkbox-2 chkPC"
                                                                                    type="checkbox" />
                                                                                <span class="lbl"> </span>
                                                                            </label>
                                                                        </div>
                                                                    @endif
                                                                    @if ($pc->assessor_signoff == 1)
                                                                        @if( auth()->user()->can('cancel-signoff-progress') )
                                                                        <div class="icon-wrapper" title="This PC has been signed off, click to cancel sign off." onclick="cancelSignOff('{{ $pc->id }}');">
                                                                            <i class="fa fa-check-circle green fa-2x"></i>
                                                                            <i class="fa fa-times-circle red fa-2x hidden-icon"></i>
                                                                        </div>
                                                                        @else
                                                                        <i class="fa fa-check-circle green fa-2x" 
                                                                            data-rel="tooltip"
                                                                            title="This PC has been signed off"></i>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-toolbox padding-8 clearfix">
                                <div class="center">
                                    <button class="btn btn-sm btn-success btn-round" type="submit"><i
                                            class="ace-icon fa fa-save bigger-110"></i>Save Signoff</button>&nbsp; &nbsp;
                                    &nbsp;
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
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
@endsection

@section('page-inline-scripts')
    <script type="text/javascript">
	function cancelSignOff(pcId)
        {
            let pcTitle = $("#tdPcId" + pcId + "PcTitle").text();

            bootbox.prompt({
                title: '<h5 class="text-info bolder">Confirm cancel signoff?</h5><h6 class="muted small">' + pcTitle + '</h6><h5 class="text-info bolder">Please provide reason to cancel signoff for this PC.</h5>',
                inputType: 'textarea',
                centerVertical: true,
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-round btn-xs'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Submit',
                        className: 'btn-danger btn-round btn-xs'
                    }
                },
                callback: function(result) {
                    if (result) {
                        
                        let dialog = bootbox.dialog({
                            message: '<p><i class="fa fa-spin fa-spinner"></i> Updating...</p>'
                        });

                        dialog.init(function() {
                            
                            $.ajax({
                                url:'{{ route("trainings.portfolios.cancelPcSignoff", [$training, $portfolio]) }}',
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    training_id: '{{ $training->id }}',
                                    portfolio_id: '{{ $portfolio->id }}',
                                    pc_id: pcId,
                                    reason: result
                                }
                            }).done(function(data) {
                                setTimeout(function() {
                                    dialog.find('.bootbox-body').html(data.message);
                                    window.location.reload();
                                }, 3000);
                            }).fail(function(jqXHR, textStatus, errorThrown){
                                var response = JSON.parse(jqXHR.responseText);
                                var errorString = '<p class="bolder red">Errors:</p><ul>';
                                if(response.errors !== undefined && response.errors.length > 0)
                                {
                                    errorString += '<ul>';
                                    $.each( response.errors, function( key, value) {
                                        errorString += '<li>' + value + '</li>';
                                    });
                                    errorString += '</ul>';
                                }
                                else if(jqXHR.responseJSON.message !== undefined)
                                {
                                    errorString += jqXHR.responseJSON.message;
                                }
                                else
                                {
                                    errorString += 'Something went wrong, please try again or raise a support ticket.';
                                }

                                dialog.find('.bootbox-body').html(errorString);
                            });

                        });
                        
                    }
                }
            });
        }

        $('.icon-wrapper').hover(
            function() {
                $(this).find('.hidden-icon').css({ 'opacity': '0.7', 'visibility': 'visible' });
                $(this).find('.green').css('opacity', '0.5');
            },
            function() {
                $(this).find('.hidden-icon').css({ 'opacity': '0', 'visibility': 'hidden' });
                $(this).find('.green').css('opacity', '1');
            }
        );

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

            $("form[name=frmSignoffProgress]").on('submit', function() {
                var form = $(this);
                form.find(':submit').attr("disabled", true);
                form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
                return true;
            });
        });
    </script>
@endsection
