@extends('layouts.master')

@section('title', 'View ALS Review Details')

@section('page-content')
    <div class="page-header">
        <h1>
            View ALS Review Details
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                view details about this ALS review record
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.show', $training) }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>
            @if (auth()->user()->isStaff() && auth()->user()->can('update-training-record') && !$alsReview->locked())
                <button class="btn btn-sm btn-primary btn-round" type="button"
                    onclick="window.location.href='{{ route('trainings.als_reviews.edit', [$training, $alsReview]) }}'">
                    <i class="ace-icon fa fa-edit bigger-110"></i> Edit
                </button>
                {!! Form::open([
                    'method' => 'DELETE',
                    'url' => route('trainings.als_reviews.destroy', [$training, $alsReview]),
                    'id' => 'frmDeleteAlsReview',
                    'style' => 'display: inline;',
                    'class' => 'form-inline',
                ]) !!}
                {!! Form::hidden('als_review_id_to_del', $alsReview->id) !!}
                {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-110"></i> Delete', [
                    'data-rel' => 'tooltip',
                    'class' => 'btn btn-danger btn-xs btn-round',
                    'type' => 'click',
                    'id' => 'btnDeleteAlsReview',
                ]) !!}
                {!! Form::close() !!}
            @endif
            @if (!$alsReview->assessor_sign && auth()->user()->id === $alsReview->assessor)
                <button class="btn btn-sm btn-primary btn-round" type="button"
                    onclick="window.location.href='{{ route('trainings.als_reviews.edit', [$training, $alsReview]) }}?subaction=als_review_form_assessor'">
                    <i class="ace-icon fa fa-edit bigger-110"></i> Review Form - Assessor Section
                </button>
            @endif
            @if (!$alsReview->tutor_sign && auth()->user()->id === $alsReview->tutor)
                <button class="btn btn-sm btn-primary btn-round" type="button"
                    onclick="window.location.href='{{ route('trainings.als_reviews.edit', [$training, $alsReview]) }}?subaction=als_review_form_tutor'">
                    <i class="ace-icon fa fa-edit bigger-110"></i> Review Form - Tutor Section
                </button>
            @endif
            @if ($alsReview->readyToSignForLearner() && auth()->user()->id == $training->student_id)
                <button class="btn btn-sm btn-primary btn-round" type="button"
                    onclick="window.location.href='{{ route('trainings.als_reviews.edit', [$training, $alsReview]) }}?subaction=als_review_form_learner'">
                    <i class="ace-icon fa fa-edit bigger-110"></i> Review Form - Learner Sign
                </button>
            @endif

            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h5 class="widget-title">ALS Review Entry Details</h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="info-div info-div-striped">
                                    <div class="info-div-row">
                                        <div class="info-div-name">Assessor</div>
                                        <div class="info-div-value">
                                            {{ optional(App\Models\User::find($alsReview->assessor))->full_name }}
                                        </div>
                                        <div class="info-div-name">Tutor</div>
                                        <div class="info-div-value">
                                            {{ optional(App\Models\User::find($alsReview->tutor))->full_name }}
                                        </div>
                                        <div class="info-div-name">Planned Date</div>
                                        <div class="info-div-value">{{ $alsReview->planned_date->format('d/m/Y') }}</div>
                                        <div class="info-div-name">Date of Review</div>
                                        <div class="info-div-value">
                                            {{ optional($alsReview->date_of_review)->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('trainings.als_reviews.partials.assessor_section_read')

            {{-- @include('trainings.als_reviews.partials.tutor_section_read') --}}

            {{-- @include('trainings.als_reviews.partials.learner_section_read') --}}

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h5 class="widget-title">Signatures</h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="info-div info-div-striped">
                                    <div class="info-div-row">
                                        <div class="info-div-name">Learner Signature</div>
                                        <div class="info-div-value">{!! $alsReview->learner_sign ? '<i class="fa fa-check-circle green fa-2x"></i>' : '' !!}</div>
                                        <div class="info-div-name">Learner Signature date</div>
                                        <div class="info-div-value">
                                            {{ optional($alsReview->learner_sign_date)->format('d/m/Y') }}</div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name">Assessor Signature</div>
                                        <div class="info-div-value">{!! $alsReview->assessor_sign ? '<i class="fa fa-check-circle green fa-2x"></i>' : '' !!}</div>
                                        <div class="info-div-name">Assessor Signature date</div>
                                        <div class="info-div-value">
                                            {{ optional($alsReview->assessor_sign_date)->format('d/m/Y') }}</div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name">Tutor Signature</div>
                                        <div class="info-div-value">{!! $alsReview->tutor_sign ? '<i class="fa fa-check-circle green fa-2x"></i>' : '' !!}</div>
                                        <div class="info-div-name">Tutor Signature date</div>
                                        <div class="info-div-value">
                                            {{ optional($alsReview->tutor_sign_date)->format('d/m/Y') }}</div>
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

@push('after-scripts')
    <script>
        $("button#btnDeleteAlsReview").on('click', function(e) {
            e.preventDefault();

            var form = $(this).closest('form');

            bootbox.confirm({
                title: 'Sure to Remove?',
                message: 'This action is irreversible, are you sure you want to continue?',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-xs btn-round'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Yes Remove',
                        className: 'btn-danger btn-xs btn-round'
                    }
                },
                callback: function(result) {
                    if (result) {
                        form.submit();
                    }
                }
            });
        });
    </script>
@endpush
