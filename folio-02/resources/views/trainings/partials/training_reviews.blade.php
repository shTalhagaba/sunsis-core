<div class="row">
    <div class="col-sm-12">
        <h4 class="lighter">Reviews<small>
                <i class="ace-icon fa fa-angle-double-right"></i> Here you can manage your reviews.</small>
        </h4>
    </div>
</div>

@if (auth()->user()->isStaff() && auth()->user()->can('update-training-record'))
    <div class="row">
        <div class="col-sm-12">
            <span class="btn btn-primary btn-sm btn-round"
                onclick="window.location.href='{{ route('trainings.reviews.create', $training) }}'">
                <i class="fa fa-plus"></i> Add New Review
            </span>
            <div class="hr hr-12 hr-dotted"></div>
        </div>
    </div>
@endif

<div class="row">
    <div class="col-xs-12 table-responsive">
        @if ($training->reviews->count() > 0)
            <h4 class="bigger blue text-center">{{ $training->reviews->count() }}
                {{ \Str::plural('Review', $training->reviews->count()) }}</h4>
        @endif
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Type</th>
                <th>Due Date</th>
                <th>Actual Date</th>
                <th>Start/End</th>
                <th style="width: 20%;">Portfolio</th>
                <th style="width: 10%;">Form Signs</th>
                <th></th>
            </tr>
            @foreach ($training->reviews as $review)
                <tr class="{{ $review->completed() ? 'green' : ($review->overdue() ? 'red' : '') }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ !is_null($review->type_of_review) ? \App\Models\LookupManager::getTrainingReviewTypes($review->type_of_review) : '' }}
                    </td>
                    <td>{{ optional($review->due_date)->format('d/m/Y') }} </td>
                    <td>{{ optional($review->meeting_date)->format('d/m/Y') }} </td>
                    <td>
                        {{ !is_null($review->start_time) ? \Carbon\Carbon::parse($review->start_time)->format('H:i') . ' - ' : '' }}
                        {{ !is_null($review->end_time) ? \Carbon\Carbon::parse($review->end_time)->format('H:i') : '' }}
                    </td>
                    <td>
                        {{ optional($review->portfolio)->title }}
                    </td>
                    <td align="left">
                        Assessor:&nbsp;{!! (isset($review->form->assessor_signed) && $review->form->assessor_signed) == '1'
                            ? '<i class="fa fa-check fa-lg green"></i>' . $review->form->assessorSignDate()
                            : '' !!}
                        <br>
                        Learner:&nbsp;{!! (isset($review->form->learner_signed) && $review->form->learner_signed) == '1'
                            ? '<i class="fa fa-check fa-lg green"></i>' . $review->form->learnerSignDate()
                            : '' !!}
                        <br>
                        Employer:&nbsp;{!! (isset($review->form->employer_signed) && $review->form->employer_signed) == '1'
                            ? '<i class="fa fa-check fa-lg green"></i>' . $review->form->employerSignDate()
                            : '' !!}
                    </td>
                    <td>
                        <span class="btn btn-xs btn-primary btn-round btn-white" onclick="window.location.href='{{ route('trainings.reviews.show', [$training, $review]) }}'">
                            <i class="fa fa-folder-open"></i> Review Details </span>

                        @if (optional($review->form)->completed())
                        <span class="btn btn-success btn-round btn-xs btn-white"
                            onclick="window.location.href='{{ route('trainings.reviews.form.show', ['training' => $training, 'review' => $review]) }}'">
                            <i class="fa fa-folder-open green"></i> Review Form
                        </span> &nbsp;    
                        @else
                        <span class="btn btn-primary btn-round btn-xs btn-white"
                            onclick="window.location.href='{{ route('trainings.reviews.form.show', ['training' => $training, 'review' => $review]) }}'">
                            <i class="fa fa-folder-open blue"></i> Review Form
                        </span> &nbsp;
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
