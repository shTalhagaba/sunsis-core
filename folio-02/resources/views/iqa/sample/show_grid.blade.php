@extends('layouts.master')

@section('title', 'IQA Sample Plan')

@section('page-content')
    <div class="page-header">
        <h1>View IQA Sample Plan - Grid View</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('iqa_sample_plans.show', $plan) }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>

            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            <div class="row">

                <div class="col-sm-12">
                    <h5 class="bolder text-primary">Sample Plan Details</h5>
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name"> Title </div>
                            <div class="info-div-value"><span>{{ $plan->title }}</span></div>
                            <div class="info-div-name"> Programme </div>
                            <div class="info-div-value"><span>{{ $plan->programme->title }}</span></div>
                            <div class="info-div-name"> Verifier </div>
                            <div class="info-div-value"><span>{{ $plan->verifier->full_name }}</span></div>
                        </div>
                    </div>
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name"> Type </div>
                            <div class="info-div-value"><span>{{ ucwords($plan->type) }}</span></div>
                            <div class="info-div-name"> Status </div>
                            <div class="info-div-value"><span>{!! $plan->getStatusLabel() !!}</span></div>
                            <div class="info-div-name"> Complete By </div>
                            <div class="info-div-value"><span>{{ $plan->completed_by_date->format('d/m/Y') }}</span></div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 table-responsive">
                    <h5 class="bolder text-primary">Learners</h5>
                    <table class="table table-bordered small">
                        <tr>
                            <th>Learner Name</th>
                            @foreach ($plan->units as $planUnit)
                                <th class="thUnitCols" title="{{ $planUnit->title }}">
                                    [{{ $planUnit->unit_owner_ref }}, {{ $planUnit->unique_ref_number }}]
                                </th>
                            @endforeach
                        </tr>
                        @foreach ($plan->trainings as $planTr)
                        @php
                            $selectedUnitsForTr = \DB::table('iqa_sample_plan_tr_units')
                                ->where('iqa_sample_id', $plan->id)
                                ->where('tr_id', $planTr->tr_id)
                                ->get();
                        @endphp
                        <tr>
                            <td>
                                {{ $planTr->record->student->full_name }}
                            </td>
                            @foreach ($plan->units as $planUnit)
                                @php
                                $currentUnitSelectedForTr = $selectedUnitsForTr->filter(function($item) use ($planUnit) {
                                    return $item->portfolio_unit_system_code === $planUnit->system_code ? $item : null;
                                });
                                $planTrUnit = $currentUnitSelectedForTr->count() > 0 ? $currentUnitSelectedForTr->first() : null;
                                @endphp
                                @if (! is_null($planTrUnit) )
                                    <td>
                                        @can('iqa-assessment')
                                            <button type="button" data-val="{{ $planTrUnit->portfolio_unit_id }}" 
                                                    class="btn btn-minier btn-primary btn-round btnIqaCheck"
                                                    onclick="window.location.href='{{ route('trainings.unit.iqa.show', ['training' => $planTrUnit->tr_id, 'unit' => $planTrUnit->portfolio_unit_id, 'iqa_sample_id' => $planTrUnit->iqa_sample_id]) }}'">
                                                <i class="ace-icon fa fa-check bigger-110"></i> IQA Check
                                            </button>
                                        @endcan
                                    </td>
                                @else
                                    <td></td>
                                @endif
                            @endforeach
                        </tr>                                
                        @endforeach
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection


@section('page-inline-scripts')
    <script></script>
@endsection
