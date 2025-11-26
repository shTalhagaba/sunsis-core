@push('after-styles')
    <style>
        .bg-gold {
            background-color: #FFFACD
        }
    </style>
@endpush    

<div class="row">
    <div class="col-sm-12">
        <span class="bolder text-info">Date of Portfolio Audit: </span> {{ optional($audit->date_of_portfolio_audit)->format('d/m/Y') }}
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>IQA Portfolio Check</th>
                        <th class="text-center">Yes</th>
                        <th class="text-center">No</th>
                        <th class="text-center">N/A</th>
                        <th>Comments</th>
                        <th>Actioned By</th>
                        <th>Date Completed</th>
                    </tr>
                </thead>
                <tbody>
                    <col width="30%">
                    <col width="5%">
                    <col width="5%">
                    <col width="5%">
                    <col width="30%">
                    <col width="10%">
                    <col width="10%">
                    @foreach ($questions as $q)
                        <tr id="trQues{{ $q->id }}">
                            <td class="bg-{{ $q->bg_color }}">
                                {!! nl2br($q->description) !!}
                            </td>
                            <td class="text-center">
                                {!! (isset($formData->{"ansQues$q->id"}) && $formData->{"ansQues$q->id"} == "Yes") ? '<i class="fa fa-check-circle fa-lg green"></i>' : '' !!}
                            </td>
                            <td class="text-center">
                                {!! (isset($formData->{"ansQues$q->id"}) && $formData->{"ansQues$q->id"} == "No") ? '<i class="fa fa-check-circle fa-lg green"></i>' : '' !!}
                            </td>
                            <td class="text-center">
                                {!! (isset($formData->{"ansQues$q->id"}) && $formData->{"ansQues$q->id"} == "N/A") ? '<i class="fa fa-check-circle fa-lg green"></i>' : '' !!}
                            </td>
                            <td>
                                {!! ($formData->{"commentsQues$q->id"}) ? nl2br(e($formData->{"commentsQues$q->id"})) : '' !!}
                            </td>
                            <td>
                                {!! ($formData->{"actionByQues$q->id"}) ? $usersNames[$formData->{"actionByQues$q->id"}] ?? '' : '' !!}
                            </td>
                            <td>
                                {!! ($formData->{"dateQues$q->id"}) ? Carbon\Carbon::parse($formData->{"dateQues$q->id"})->format('d/m/Y') : '' !!}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th>Strengths</th>
                        <td colspan="4">
                            {!! ($formData->commentsStrengths) ? nl2br(e($formData->commentsStrengths)) : '' !!}
                        </td>
                        <td>
                            {!! ($formData->actionByStrengths) ? $usersNames[$formData->actionByStrengths] ?? '' : '' !!}
                        </td>
                        <td>
                            {!! ($formData->dateStrengths) ? Carbon\Carbon::parse($formData->dateStrengths)->format('d/m/Y') : '' !!}
                        </td>
                    </tr>
                    <tr>
                        <th>Actions relating to the portfolio</th>
                        <td colspan="4">
                            {!! ($formData->commentsPortfolio) ? nl2br(e($formData->commentsPortfolio)) : '' !!}
                        </td>
                        <td>
                            {!! ($formData->actionByPortfolio) ? $usersNames[$formData->actionByPortfolio] ?? '' : '' !!}
                        </td>
                        <td>
                            {!! ($formData->datePortfolio) ? Carbon\Carbon::parse($formData->datePortfolio)->format('d/m/Y') : '' !!}
                        </td>
                    </tr>
                    <tr>
                        <th>Good practice identified to be shared</th>
                        <td colspan="4">
                            {!! ($formData->commentsPractice) ? nl2br(e($formData->commentsPractice)) : '' !!}
                        </td>
                        <td>
                            {!! ($formData->actionByPractice) ? $usersNames[$formData->actionByPractice] ?? '' : '' !!}
                        </td>
                        <td>
                            {!! ($formData->datePractice) ? Carbon\Carbon::parse($formData->datePractice)->format('d/m/Y') : '' !!}
                        </td>
                    </tr>


                </tbody>
            </table>
        </div>
        @if($audit->signed())
            <div class="alert alert-success">
                <span class="bolder text-info">Signed By: </span>{{ $audit->completedBy() }}
                <br>
                <span class="bolder text-info">Signed Date: </span>{{ optional($audit->completed_by_date)->format('d/m/Y') }}
            </div>
        @endif
    </div>
</div>
