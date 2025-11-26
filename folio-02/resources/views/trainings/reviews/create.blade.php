@extends('layouts.master')

@section('title', 'Create Review')

@section('page-plugin-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />

@endsection

@section('page-content')
    <div class="page-header">
        <h1>
            Create New Review
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                add a new review record in the system
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.show', $training) }}'">
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
                    {!! Form::open([
                        'url' => route('trainings.reviews.store', $training),
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' => 'frmReview',
                    ]) !!}
                    @include('trainings.reviews.form')
                    {!! Form::close() !!}
                </div>
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('page-inline-scripts')

    <script type="text/javascript">
        $(function() {
            
        });
    </script>

@endsection
