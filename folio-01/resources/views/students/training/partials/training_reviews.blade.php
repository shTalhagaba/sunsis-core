@php
$review_number = 1;
$reviews = [];
$reviews_table_start_date = \Carbon\Carbon::parse($training_record->getOriginal('start_date'));
$reviews_table_end_date = \Carbon\Carbon::parse($training_record->getOriginal('planned_end_date'));

if($training_record->reviews->count() == 0)
{
    while($reviews_table_start_date->lessThanOrEqualTo($reviews_table_end_date))
    {
        if($review_number == 1)
            $reviews_table_start_date->addDays(28);
        else
            $reviews_table_start_date->addDays(56);

        if($reviews_table_start_date->greaterThan($reviews_table_end_date))
            break;

        $reviews[$review_number] = $reviews_table_start_date->format('d/m/Y');

        $_review = \App\Models\Training\TrainingReview::create([
            'tr_id' => $training_record->id,
            'due_date' => $reviews_table_start_date->format('Y-m-d')
        ]);

        $review_number++;
    }
}
@endphp
<div class="row">
    <div class="col-sm-12">
        <h4 class="lighter">Reviews<small>
                <i class="ace-icon fa fa-angle-double-right"></i> Here you can manage your reviews.</small>
        </h4>
    </div>
</div>

@if(!auth()->user()->isStudent())
<div class="row">
    <div class="col-sm-12">
        <span class="btn btn-primary btn-sm btn-round"
            onclick="window.location.href='{{ route('students.training.reviews.create', [$student, $training_record]) }}'">
            <i class="fa fa-plus"></i> Add New Review
        </span>
        <p><br></p>
    </div>
</div>
@endif

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <tr>
                <th>Type</th>
                <th>Due Date</th>
                <th>Start/End</th>
                <th>Portfolio</th>
                <th>Leanrer Sign</th>
                <th>Assessor Sign</th>
                <th></th>
            </tr>
            @foreach($training_record->reviews()->orderBy('due_date')->get() AS $review)
                <tr>
                    <td>{{ !is_null($review->type_of_review) ? \App\Models\LookupManager::getTrainingReviewTypes($review->type_of_review) : '' }}</td>
                    <td>{{ \Carbon\Carbon::parse($review->due_date)->format('d/m/Y') }} </td>
                    <td>
                        {{ !is_null($review->start_time) ? \Carbon\Carbon::parse($review->start_time)->format('H:i') : '' }} -
                        {{ !is_null($review->end_time) ? \Carbon\Carbon::parse($review->end_time)->format('H:i') : '' }}
                    </td>
                    <td>
                        {{ $training_record->portfolios()->where('id', $review->portfolio_id)->first()->title ?? '' }}
                    </td>
                    <td align="center">{!! (isset($review->review_form->l_sign) && $review->review_form->l_sign) == '1' ? '<i class="fa fa-check fa-lg green"></i>' : '' !!}</td>
                    <td align="center">{!! (isset($review->review_form->a_sign) && $review->review_form->a_sign) == '1' ? '<i class="fa fa-check fa-lg green"></i>' : '' !!}</td>
                    <td>
                        @if(!auth()->user()->isStudent())
                        <span class="btn btn-primary btn-round btn-xs btn-white" onclick="window.location.href='{{ route('students.training.reviews.edit', [$student, $training_record, $review]) }}'">
                            <i class="fa fa-edit blue"></i> Edit
                        </span> &nbsp;
                        @endif
                        <span class="btn btn-primary btn-round btn-xs btn-white" onclick="window.location.href='{{ route('students.training.reviews.open_review_form', [$student, $training_record, $review]) }}'">
                            <i class="fa fa-folder-open blue"></i> Review Form
                        </span> &nbsp;
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
