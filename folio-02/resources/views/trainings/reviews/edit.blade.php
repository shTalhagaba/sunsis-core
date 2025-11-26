@extends('layouts.master')

@section('title', 'Edit Training Review')

@section('page-content')
    <div class="page-header">
        <h1>
            Edit Training Review
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                edit review record in the system
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.reviews.show', [$training, $review]) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            <div class="space-6"></div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="space"></div>
                    {!! Form::model($review, [
                        'method' => 'PATCH',
                        'url' => route('trainings.reviews.update', [$training, $review]),
                        'class' => 'form-horizontal',
                        'files' => true,
                        'role' => 'form',
                        'name' => 'frmEditReview',
                        'id' => 'frmEditReview',
                    ]) !!}
                    @include('trainings.reviews.form')
                    {!! Form::close() !!}

                </div><!-- /.span -->
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
