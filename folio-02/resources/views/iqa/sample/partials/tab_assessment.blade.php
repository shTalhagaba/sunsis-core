<a class="btn btn-xs btn-info btn-round" href="{{ route('iqa_sample_plans.show', $plan) }}?view=grid">
    <i class="fa fa-table"></i> Grid View
</a>
@foreach ($plan->trainings as $training)
<div class="widget-box border transparent" style="border:1px solid black; padding: 0.5%">
    <div class="widget-header">
        <img class="rounded-circle" alt="{{ $training->record->student->firstnames}}'s Avatar" src="{{ asset($training->record->student->avatar_url) }}" style="width: 4%; height: 4%; border-radius: 50%;" />
        <h5 class="widget-title">
            <span class="bolder">{{ $training->record->student->full_name }}</span>
            | <span class="small">SD: {{ $training->record->start_date->format('d/m/Y') }}, PED: {{ $training->record->planned_end_date->format('d/m/Y') }}</span>
        </h5>
        <div class="widget-toolbar">
            @can('read-training-record')
                <button type="button"
                        class="btn btn-minier btn-info btn-round"
                        onclick="window.location.href='{{ route('trainings.show', ['training' => $training->record]) }}'">
                    <i class="ace-icon fa fa-folder-open bigger-110"></i> View Training
                </button>
            @endcan
            <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-up"></i></a>
        </div>
    </div>
    <div class="widget-body">
        <div class="widget-main table-responsive">
            <table class="table table-bordered table-hover">
                <tbody>
                    @php
                        $trainingId = $training->record->id;
                        $portfolioUnits = App\Models\Training\PortfolioUnit::query()
                            ->whereHas('portfolio', function($query) use ($trainingId) {
                                return $query->where('tr_id', $trainingId);
                            })
                            ->whereIn('system_code', $plan->units()->pluck('system_code')->toArray())
                            ->get();
                    @endphp
                    @foreach ($portfolioUnits as $portfolioUnit)
                        <tr id="rowPortfolioUnit{{ $portfolioUnit->id }}">
                            <td>
                                <div class="checkbox">
                                    @if ($portfolioUnit->isAssessedByIqa())
                                    <label>
                                        <input class="ace input-lg" type="checkbox" checked disabled/>
                                        <span class="lbl"> </span>
                                    </label>
                                    @else
                                    <label>
                                        <input class="ace input-lg chkPortfolioUnit" type="checkbox"  
                                            data-iqa-sample-unit-id="{{ $portfolioUnit->id }}" 
                                            data-portfolio-unit-id="{{ $portfolioUnit->id }}" 
                                            data-portfolio-id="{{ $portfolioUnit->portfolio_id }}" 
                                            data-training-id="{{ $trainingId }}" 
                                            {{ in_array($portfolioUnit->id, $selectedTrainingUnits) ? 'checked' : '' }}
                                        />
                                        <span class="lbl"> </span>
                                    </label>                                                                        
                                    @endif
                                </div>
                            </td>
                            <td style="width: 84%">
                                [{{ $portfolioUnit->unique_ref_number }}] [{{ $portfolioUnit->unit_owner_ref }}] {!! nl2br(e($portfolioUnit->title)) !!} 
                                {!! $portfolioUnit->iqa_completed ? 
                                    '<span class="label label-md label-success arrowed-in arrowed-in-right small">IQA Fully Completed</span>' :
                                    (
                                        $portfolioUnit->iqa_status == App\Models\Training\PortfolioUnitIqa::STATUS_IQA_ACCEPTED ?
                                        '<span class="label label-md label-success arrowed-in arrowed-in-right small">IQA Accepted</span>' :
                                        (
                                            $portfolioUnit->iqa_status == App\Models\Training\PortfolioUnitIqa::STATUS_IQA_REFERRED ? 
                                            '<span class="label label-md label-danger arrowed-in arrowed-in-right">IQA Referred</span>' : 
                                            ''
                                        )                                                                        
                                    )
                                !!}
                            </td>
                            <td>
                                @can('iqa-assessment')
                                    <button type="button" data-val="{{ $portfolioUnit->id }}" 
                                            class="btn btn-minier btn-primary btn-round btnIqaCheck {{ in_array($portfolioUnit->id, $selectedTrainingUnits) ? '' : 'hidden' }}"
                                            onclick="window.location.href='{{ route('trainings.unit.iqa.show', ['training' => $training->record, 'unit' => $portfolioUnit, 'iqa_sample_id' => $plan->id]) }}'">
                                        <i class="ace-icon fa fa-check bigger-110"></i> IQA Check
                                    </button>
                                @endcan
                            </td>                                                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endforeach

@push('after-scripts')
    <script>
        $(function(){
            $("input.chkPortfolioUnit").on('change', function (){
                var trainingId = $(this).attr('data-training-id');
                var portfolioId = $(this).attr('data-portfolio-id');
                var portfolioUnitId = $(this).attr('data-portfolio-unit-id');

                if(this.checked)
                {
                    addRemoveLearnerUnit(portfolioUnitId, portfolioId, trainingId, 'add');
                    $("button.btnIqaCheck[data-val=" + portfolioUnitId + "]").removeClass('hidden');
                }
                else
                {
                    addRemoveLearnerUnit(portfolioUnitId, portfolioId, trainingId, 'remove');
                    $("button.btnIqaCheck[data-val=" + portfolioUnitId + "]").addClass('hidden');
                }
            });

        });

        function addRemoveLearnerUnit(portfolioUnitId, portfolioId, trainingId, action)
        {
            $("input.chkPortfolioUnit").prop('disabled', true);
            $.ajax({
                url:'{{ route("iqa_sample_plans.addRemoveTrainingUnits", [$plan]) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    training_id: trainingId,
                    portfolio_id: portfolioId,
                    unit_id: portfolioUnitId,
                    action: action
                }
            }).done(function(data) {
                //
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
                bootbox.alert(errorString);
            }).always(function() {
                $("input.chkPortfolioUnit").prop('disabled', false);
            });

        }


    </script>
@endpush