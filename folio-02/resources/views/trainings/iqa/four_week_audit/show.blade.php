@extends('layouts.master')

@section('title', 'View Initial Portfolio Audit at 4 weeks')

@section('page-content')
    <div class="page-header">
        <h1>
            View Initial Portfolio Audit at 4 weeks
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
            @if (auth()->user()->isAdmin() || auth()->user()->id == $audit->created_by)
                <button type="button" class="btn btn-primary btn-sm btn-round"
                    onclick="window.location.href='{{ $training->four_week_audit ? route('trainings.four_week_audit.edit', ['training' => $training, 'audit' => $audit]) : route('trainings.four_week_audit.create', $training) }}'">
                    <i class="fa fa-edit"></i> Edit Audit Form
                </button>
            @endif

            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            @include('partials.session_message')

            @include('partials.session_error')

            @include('trainings.iqa.four_week_audit.view')

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@push('after-scripts')
    <script></script>
@endpush
