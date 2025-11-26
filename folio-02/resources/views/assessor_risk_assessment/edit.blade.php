@extends('layouts.master')

@section('title', 'Edit Assessor Risk Assessment')

@section('page-content')
    <div class="page-header">
        <h1>
            Edit Assessor Risk Assessment
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('assessor_risk_assessment.show', $riskAssessment) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="space"></div>
                    {!! Form::model($riskAssessment, [
                        'method' => 'PATCH',
                        'url' => route('assessor_risk_assessment.update', $riskAssessment),
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'name' => 'frmRiskAssessment',
                        'id' => 'frmRiskAssessment',
                    ]) !!}
                    @include('assessor_risk_assessment.form')
                    {!! Form::close() !!}

                </div><!-- /.span -->
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@push('after-scripts')
    <script>
        $("form[name=frmRiskAssessment',]").on('submit', function(){
            var form = $(this);
            form.find(':submit').attr("disabled", true);
            form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
            return true;
        });

        $(function(){
           
        });
    </script>
@endpush