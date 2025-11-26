<div class="row">
    <div class="col-sm-6">
        <h4 class="lighter">ALS Reviews<small>
                <i class="ace-icon fa fa-angle-double-right"></i> Here you can manage ALS reviews.</small>
        </h4>
    </div>
    <div class="col-sm-6">
        @if (auth()->user()->isAdmin() && auth()->user()->can('update-training-record'))
            <label for="show_als_tab_to_employer" class="control-label">Visible to Employer</label>
            <div>
                <label>
                    <input class="ace ace-switch ace-switch-5" name="show_als_tab_to_employer" type="checkbox"
                        {{ $training->show_als_tab_to_employer ? 'checked' : '' }} id="show_als_tab_to_employer">
                    <span class="lbl"></span><br>
                    <span class="text-info small">
                        <i class="fa fa-info-circle"></i> You can control the visibility of this tab for learner's
                        employer.
                    </span>
                </label>
            </div>
        @endif
    </div>
</div>

@if (auth()->user()->isStaff() && auth()->user()->can('update-training-record'))
    <div class="row">
        <div class="col-sm-12">
            @if(!$training->alsAssessmentPlan()->exists())
            <span class="btn btn-primary btn-sm btn-round"
                onclick="window.location.href='{{ route('trainings.als_assessment.create', $training) }}'">
                Create ALS Assessment
            </span>
            @endif
            &nbsp; 
            <span class="btn btn-primary btn-sm btn-round"
                onclick="window.location.href='{{ route('trainings.als_reviews.create', $training) }}'">
                <i class="fa fa-plus"></i> Add New ALS Review
            </span>
            <div class="hr hr-12 hr-dotted"></div>
        </div>
    </div>
@endif

@if($training->alsAssessmentPlan()->exists())
<div class="row">
    <div class="col-sm-12">
        <span class="btn btn-primary btn-sm btn-round"
            onclick="window.location.href='{{ route('trainings.als_assessment.show', ['training' => $training, 'als_assessment' => $training->alsAssessmentPlan]) }}'">
            View ALS Assessment
        </span>
    </div>
</div>
@endif

@if (auth()->user()->isAdmin() ||
        auth()->user()->isAssessor() ||
        auth()->user()->isTutor() ||
        auth()->user()->isStudent() ||
        (auth()->user()->isEmployerUser() && $training->show_als_tab_to_employer))
    <div class="row">
        <div class="col-xs-12 table-responsive">
            @if ($training->alsReviews->count() > 0)
                <h4 class="bigger blue text-center">{{ $training->alsReviews->count() }}
                    ALS {{ \Str::plural('Review', $training->reviews->count()) }}</h4>
            @endif
            <table class="table table-bordered" id="tblAlsReviews">
                <tr>
                    <th>#</th>
                    <th>Planned Date</th>
                    <th>Actual Date</th>
                    <th>Assessor</th>
                    <th>Tutor</th>
                    <th>Assessor Signed</th>
                    <th>Tutor Signed</th>
                    <th>Learner Signed</th>
                    <th></th>
                </tr>
                @foreach ($training->alsReviews as $alsReview)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ optional($alsReview->planned_date)->format('d/m/Y') }} </td>
                        <td>{{ optional($alsReview->date_of_review)->format('d/m/Y') }} </td>
                        <td>{{ $alsReview->assessorName() }}</td>
                        <td>{{ $alsReview->tutorName() }}</td>
                        <td>
                            {!! $alsReview->assessor_sign ? '<i class="fa fa-check fa-lg green"></i>' : 'No' !!}
                            {!! $alsReview->assessor_sign ? Carbon\Carbon::parse($alsReview->assessor_sign_date)->format('d/m/Y') : '' !!}
                        </td>
                        <td>
                            {!! $alsReview->tutor_sign ? '<i class="fa fa-check fa-lg green"></i>' : 'No' !!}
                            {!! $alsReview->tutor_sign ? Carbon\Carbon::parse($alsReview->tutor_sign_date)->format('d/m/Y') : '' !!}
                        </td>
                        <td>
                            {!! $alsReview->learner_sign ? '<i class="fa fa-check fa-lg green"></i>' : 'No' !!}
                            {!! $alsReview->learner_sign ? Carbon\Carbon::parse($alsReview->learner_sign_date)->format('d/m/Y') : '' !!}
                        </td>
                        <td>
                            <span class="btn btn-xs btn-primary btn-round btn-white"
                                onclick="window.location.href='{{ route('trainings.als_reviews.show', [$training, $alsReview]) }}'">
                                <i class="fa fa-folder-open"></i> Review Details </span>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endif

@push('after-scripts')
    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("input[type=checkbox][name=show_als_tab_to_employer]").on('change', function(e) {
                e.preventDefault();
                const checkedStatus = !!this.checked;
                $.ajax({
                    type: 'POST',
                    url: '{{ route('ajax.showAlsTabToEmployer') }}',
                    data: {
                        tr_id: '{{ $training->id }}',
                        show: checkedStatus ? 1 : 0,
                    },
                    success: function(data) {
                        $.alert('The change has been saved successfully.');
                    },
                    error: function(qXHR, errorThrown, textStatus) {
                        $.alert(errorThrown, textStatus);
                    }
                });
            });
        });
    </script>
@endpush
