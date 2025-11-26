@extends('layouts.master')

@section('title', 'Individual Learning Support Assessment and Plan')

@section('page-content')
    <div class="page-header">
        <h1>
            Individual Learning Support Assessment and Plan
        </h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.als_assessment.show', [$training, $alsAssessment]) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="space"></div>
                    {!! Form::model($alsAssessment, [
                        'method' => 'PATCH',
                        'url' => route('trainings.als_assessment.update', [$training, $alsAssessment]),
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'name' => 'frmEditAlsAssessment',
                        'id' => 'frmEditAlsAssessment',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    {!! Form::hidden('basic_form', true) !!}
                    @include('trainings.als_assessment.form')
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script>
        $("form[name=frmEditAlsAssessment]").on('submit', function() {
            var form = $(this);
            form.find(':submit').attr("disabled", true);
            form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
            return true;
        });
    </script>
@endpush
