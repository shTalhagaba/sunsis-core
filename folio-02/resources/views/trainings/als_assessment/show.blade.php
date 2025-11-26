@extends('layouts.master')

@section('title', 'Individual Learning Support Assessment and Plan')

@section('page-content')
    <div class="page-header">
        <h1>
            Individual Learning Support Assessment and Plan
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.show', $training) }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>
            @if (auth()->user()->isStaff() && auth()->user()->can('update-training-record') && !$alsAssessment->locked())
                <button class="btn btn-sm btn-primary btn-round" type="button"
                    onclick="window.location.href='{{ route('trainings.als_assessment.edit', [$training, $alsAssessment]) }}'">
                    <i class="ace-icon fa fa-edit bigger-110"></i> Edit
                </button>
                {!! Form::open([
                    'method' => 'DELETE',
                    'url' => route('trainings.als_assessment.destroy', [$training, $alsAssessment]),
                    'id' => 'frmDeleteAlsAssessment',
                    'style' => 'display: inline;',
                    'class' => 'form-inline',
                ]) !!}
                {!! Form::hidden('als_assessment_id_to_del', $alsAssessment->id) !!}
                {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-110"></i> Delete', [
                    'data-rel' => 'tooltip',
                    'class' => 'btn btn-danger btn-xs btn-round',
                    'type' => 'click',
                    'id' => 'btnDeleteAlsAssessment',
                ]) !!}
                {!! Form::close() !!}
            @endif
            @if (auth()->user()->isStudent() && !$alsAssessment->learner_sign)
                <button class="btn btn-sm btn-primary btn-round" type="button"
                    onclick="window.location.href='{{ route('trainings.als_assessment.edit', [$training, $alsAssessment]) }}?subaction=assessment_form'">
                    <i class="ace-icon fa fa-edit bigger-110"></i> Sign Form
                </button>
            @endif

            @if ($alsAssessment->isAllowed(auth()->user()->id))
                <button class="btn btn-sm btn-primary btn-round" type="button"
                    onclick="window.location.href='{{ route('trainings.als_assessment.edit', [$training, $alsAssessment]) }}?subaction=assessment_form'">
                    <i class="ace-icon fa fa-edit bigger-110"></i> {{ !auth()->user()->isVerifier() ? 'Complete and Sign Form' : 'Sign Form' }} 
                </button>
            @endif


            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            @include('partials.session_message')

            @include('partials.session_error')

            @include('trainings.als_assessment.partials.form_contents_read', ['alsAssessment' => $alsAssessment])

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@push('after-scripts')
    <script>
        $("button#btnDeleteAlsAssessment").on('click', function(e) {
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
