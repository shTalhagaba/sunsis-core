@extends('layouts.master')

@section('title', 'ALS Review Form')

@section('page-content')
    <div class="page-header">
        <h1>
            ALS Review Form
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                complete details about this ALS review form
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

            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="space"></div>
                    {!! Form::model($alsReview, [
                        'method' => 'PATCH',
                        'url' => route('trainings.als_reviews.update', [$training, $alsReview]),
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' => 'frmAlsReviewLearner',
                    ]) !!}
                    {!! Form::hidden('update_by', 'learner') !!}

                    <div class="col-sm-6">
                        @include('trainings.als_reviews.partials.assessor_section_read')
                    </div>
                    <div class="col-sm-6">
                        @include('trainings.als_reviews.partials.tutor_section_read')
                    </div>

                    <div class="col-sm-12">
                        <div class="widget-box widget-color-green">
                            <div class="widget-header">
                                <h4 class="smaller">Learner Signature</h4>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main">
                                    <div class="form-group row">
                                        <div class="col-sm-12 text-center">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="learner_sign" type="checkbox" value="1">
                                                    <span class="lbl bolder"> &nbsp; Tick this option to confirm your signature if
                                                        the form is fully completed.</span>
                                                    <div class="space-2"></div>
                                                    <span class="text-info small" style="margin-left: 2%">
                                                        &nbsp; <i class="fa fa-info-circle"></i>
                                                        After you tick this option and save then form will be locked for further
                                                        changes.
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>                                
                                </div>
                                <div class="widget-toolbox padding-8 clearfix">
                                    <div class="center">
                                        <button class="btn btn-sm btn-success btn-round" type="submit">
                                            <i class="ace-icon fa fa-save bigger-110"></i>Save Information
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>

        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@push('after-scripts')
    <script>
    </script>
@endpush
