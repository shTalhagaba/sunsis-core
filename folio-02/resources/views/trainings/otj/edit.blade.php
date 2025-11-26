@extends('layouts.master')

@section('title', 'Edit OTJH entry')

@section('page-plugin-styles')
<style>
    .dataTable > thead > tr > th[class*="sort"]:before,
    .dataTable > thead > tr > th[class*="sort"]:after {
        content: "" !important;
    }
</style>
@endsection

@section('page-content')
    <div class="page-header">
        <h1>
            Edit OTJH Entry
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                edit off-the-job-hours record in the system
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.otj.show', [$training, $otj]) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="space"></div>
                    {!! Form::model($otj, [
                        'method' => 'PATCH',
                        'url' => route('trainings.otj.update', [$training, $otj]),
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'name' => 'frmEditOtj',
                        'id' => 'frmEditOtj',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    @include('trainings.otj.form', ['isEditable' => $otj->isEditable(), 'showAssessmentPanel' => $showAssessmentPanel])
                    {!! Form::close() !!}

                </div><!-- /.span -->
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
@endsection

@push('after-scripts')
    <script>
        $("form[name=frmEditOtj]").on('submit', function(){
            var form = $(this);
            form.find(':submit').attr("disabled", true);
            form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
            return true;
        });

        $(function(){
            $('#tblPcs').DataTable({
                "lengthChange": false,
                "paging" : false,
                "info" : false,
                "order": false
            });

            $('.dataTables_filter input[type="search"]').css({
                'width':'350px','display':'inline-block'
            });
            
        });
    </script>
@endpush