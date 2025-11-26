@extends('layouts.master')

@section('title', 'Manage Training Record Portfolio')

@section('page-content')
    <div class="page-header">
        <h1>Manage Training Record Portfolio - Remove Elements</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                        onclick="window.location.href='{{ route('trainings.show', $training) }}'">
                        <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
                    </button>
                    <div class="hr hr-12 hr-dotted"></div>
                </div>
            </div>
            
            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box transparent">
                        <div class="widget-header"><h5 class="widget-title">Remove Units</h5></div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <p class="alert alert-info">
                                    This functionality allows you to remove elements (optional units) from <strong>[{{ $portfolio->qan }}] {{ $portfolio->title }}</strong>.<br>
                                    Following are the optional units available to remove from this portfolio.<br>
                                    Please note that unit can only be removed if there is no evidence mapped to it.
                                </p>
                                <div class="responsive">
                                    <div class="widget-box transparent ui-sortable-handle">
                                        <div class="widget-header">
                                            <h5 class="widget-title">
                                                <i class="fa fa-graduation-cap"></i> <strong>{{ $portfolio->qan }} {{ $portfolio->title }}</strong>
                                            </h5>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                @foreach($portfolio->units AS $unit)
                                                @php
                                                    $a = 0;
                                                    if($unit->isMandatory() || $unit->pcsWithEvidence() > 0)
                                                    {
                                                        continue;
                                                    }
                                                    $a++;
                                                @endphp
                                                <table class="table table-bordered table-hover">
                                                    <tr>
                                                        <th class="center" style="width: 8%;">
                                                            <button type="button" class="btn btn-danger btn-md btn-round btnRemoveUnit"   
                                                                data-element-type="unit" data-element-id="{{ $unit->id }}" 
                                                                title="Remove this unit and all of its pcs from the portfolio">
                                                                <i class="fa fa-times fa-lg"></i>
                                                            </button>                                                            
                                                        </th>
                                                        <th>
                                                            <i class="fa fa-folder fa-lg"></i> 
                                                            [{{ $unit->unit_owner_ref }}, {{ $unit->unique_ref_number }}] 
                                                            <h5 class="bolder" style="display: inline;">{{ $unit->title }}</h5>
                                                            <span class="pull-right"><i class="ace-icon fa fa-chevron-down" onclick="showUnitEvidencesRows('{{ $unit->id }}', this);"></i></span>
                                                        </th>
                                                    </tr>
                                                    @foreach($unit->pcs AS $pc)
                                                    <tr style="display: none;" id="RowOfUnit{{ $unit->id }}Evidence{{ $pc->id }}">
                                                        <td></td>
                                                        <td>
                                                            <i class="fa fa-folder-open"></i> [{{ $pc->reference }}] {!! nl2br($pc->title) !!}
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </table>
                                                @endforeach
                                                {!! $a == 0 ? '<span class="text-info"><i class="fa fa-info-circle"></i> No units are available to remove from this portfolio.</span>' : '' !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>        
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@section('page-inline-scripts')

    <script>
        $(function() {

            $("button.btnRemoveUnit").on('click', function(e){
                e.preventDefault();

                var btn = $(this);
                var elementType = $(this).attr('data-element-type');
                var elementId = $(this).attr('data-element-id');

                bootbox.confirm({
                    title: 'Sure to Remove?',
                    message: 'Are you sure you want to remove this unit and its criteria?',
                    buttons: {
                        cancel: {
                            label: '<i class="fa fa-times"></i> Cancel'
                        },
                        confirm: {
                            label: '<i class="fa fa-check-o"></i> Yes Remove',
                            className: 'btn-danger'
                        }
                    },
                    callback: function(result) {
                        if (result) {
                            $.ajax({
                                type: 'PATCH',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                    '_method': 'PATCH'
                                },
                                beforeSend: function() {
                                    btn.attr('disabled', true);
                                    btn.html('<i class="fa fa-spinner fa-spin"></i>');
                                },
                                url: '{{ route('trainings.portfolios.remove_element', [$training, $portfolio]) }}',
                                data: {
                                    training_id: {{ $training->id }},
                                    portfolio_id: {{ $portfolio->id }},
                                    element_type: elementType,
                                    element_id: elementId
                                },
                                success: function(response) {
                                    bootbox.alert('Unit has been removed from the portfolio.', function() {
                                        window.location.reload();
                                    });

                                },
                                error: function(errorInfo, code, errorMessage) {
                                    btn.attr('disabled', false);
                                    btn.html('<i class="fa fa-plus fa-lg"></i>');
                                    bootbox.alert({
                                        title: "Error: " + (errorInfo.statusText !== undefined ? errorInfo.statusText : code),
                                        message: errorInfo.responseJSON.message !==
                                            undefined ? errorInfo.responseJSON.message :
                                            errorMessage
                                    });
                                }
                            });
                        }
                    }
                });
                
            });

        });

        function showUnitEvidencesRows(unit_id, element)
        {
            var rows_id = 'RowOfUnit'+unit_id+'Evidence';
            $("tr[id^=" + rows_id + "]").toggle();
            $(element).toggleClass('fa-chevron-down fa-chevron-up');
        }

    </script>

@endsection
