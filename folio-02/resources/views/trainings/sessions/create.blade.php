@extends('layouts.master')

@section('title', 'Create Delivery Plan Session')

@section('page-plugin-styles')
    <style>
        .dataTable>thead>tr>th[class*="sort"]:before,
        .dataTable>thead>tr>th[class*="sort"]:after {
            content: "" !important;
        }
    </style>
@endsection

@section('page-content')
    <div class="page-header">
        <h1>
            Create Delivery Plan Session
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.show', $training) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', [
                'training' => $training,
                'showOverallPercentage' => true,
            ])

            <div class="space-12"></div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12 ">
                    <div class="widget-box collapsed">
                        <div class="widget-header">
                            <h4 class="widget-title">Available Template(s)</h4>
                            <div class="widget-toolbar">
                                <div class="widget-menu">
                                    <a href="#" data-action="collapse">
                                        <i class="ace-icon fa fa-chevron-down"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Action</th>
                                        <th>Session Details</th>
                                        <th>Performance Criteria</th>
                                    </tr>
                                    @foreach ($training->programme->templateSessions as $templateSession)
                                        <tr>
                                            <td>
                                                <span onclick="useTemplate({{ $templateSession->id }})"
                                                    class="btn btn-xs btn-info btn-round">
                                                    Use Template
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-info bolder">Session Number: </span>
                                                {{ $templateSession->session_number }}<br>
                                                <span class="text-info bolder">Details/Heading 1: </span>
                                                {{ nl2br(e($templateSession->session_details_1)) }}<br>
                                                <span class="text-info bolder">Details/Heading 2: </span>
                                                {{ nl2br(e($templateSession->session_details_2)) }}<br>
                                            </td>
                                            <td>
                                                @php
                                                    $hoursTotal = 0;
                                                    $templateSessionElements = !is_array(
                                                        json_decode($templateSession->session_pcs),
                                                    )
                                                        ? collect([])
                                                        : App\Models\Programmes\ProgrammeQualificationUnitPC::whereIn(
                                                            'id',
                                                            json_decode($templateSession->session_pcs),
                                                        )
                                                            ->with('unit:id,unit_sequence,unique_ref_number')
                                                            ->orderBy('pc_sequence')
                                                            ->get();
                                                    echo '<h4 class="text-info">Criteria (' .
                                                        count($templateSessionElements) .
                                                        ')</h4>';
                                                    foreach ($templateSessionElements as $templateSessionElement) {
                                                        echo '[' .
                                                            $templateSessionElement->unit->unique_ref_number .
                                                            '] ' .
                                                            nl2br(e($templateSessionElement->title)) .
                                                            '<hr style="margin-top: 10px; margin-bottom: 10px">';
                                                        $hoursTotal += $templateSessionElement->delivery_hours;
                                                    }
                                                @endphp
                                                <p><span class="bolder text-info">Total OTJ Hours:
                                                    </span>{{ $hoursTotal }}</p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="row">
                <div class="col-xs-12">
                    <div class="space"></div>

                    {!! Form::open([
                        'url' => route('trainings.sessions.store', $training),
                        'class' => 'form-horizontal',
                    ]) !!}

                    {!! Form::hidden('tr_id', $training->id) !!}

                    @include('trainings.sessions.form', ['training' => $training])

                    {!! Form::close() !!}

                </div><!-- /.span -->
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
@endsection

@section('page-inline-scripts')

    <script type="text/javascript">
        $(function() {
            $('#tblPcs').DataTable({
                "lengthChange": false,
                "paging": false,
                "info": false,
                "order": false
            });

            $('.dataTables_filter input[type="search"]').css({
                'width': '350px',
                'display': 'inline-block'
            });

        });

        function useTemplate(templateSessionId) {
            $.ajax({
                data: {
                    trainingId: {{ $training->id }},
                    templateSessionId: templateSessionId
                },
                url: "{{ route('getProgrammeDeliveryPlanSessionTemplate') }}",
            }).done(function(response) {
                console.log(response.data);
                $('#session_number').val(response.data.session_number);
                $('#session_details_1').val(response.data.session_details_1);
                $('#session_details_2').val(response.data.session_details_2);

                // response.data.pc_ids is an array. Each element of this array has tr_pc_id. We need to set the checked property of the elements[] checkboxes
                // based on the tr_pc_id values.
                var pcIds = response.data.pc_ids;
                $('input[name="elements[]"]').each(function() {
                    if (pcIds.includes(parseInt($(this).val()))) {
                        $(this).prop('checked', true);
                        $(this).closest('tr').addClass('bg-info');

                        // select the table of this checkbox and then select the first row of the table. This row is the unit checkbox. Check the unit checkbox
                        $(this).closest('table').find('input[type="checkbox"]').prop('checked', true);
                        // style="cursor: pointer; display: none;" change this for all rows of the table to display: 'table-row'
                        $(this).closest('table').find('tr').css('display', 'table-row');
                    } else {
                        $(this).prop('checked', false);
                        $(this).closest('tr').removeClass('bg-info');
                    }
                });

            }).fail(function(jqXHR, textStatus, errorThrown) {
                $.alert(errorThrown, textStatus);
            });
        }
    </script>

@endsection
