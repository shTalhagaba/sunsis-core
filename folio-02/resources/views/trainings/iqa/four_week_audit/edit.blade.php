@extends('layouts.master')

@section('title', 'View Initial Portfolio Audit at 4 weeks')

@section('page-content')
    <div class="page-header">
        <h1>
            Initial Portfolio Audit at 4 weeks
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.four_week_audit.show', ['training' => $training, 'audit' => $audit]) }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>

            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">

                    {!! Form::model($audit, [
                        'method' => 'PATCH',
                        'url' => route('trainings.four_week_audit.update', ['training' => $training, 'audit' => $audit]),
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'id' => 'frmAudit',
                    ]) !!}
                    <div class="widget-box widget-color-green">
                        <div class="widget-header">
                            <h5 class="widget-title">
                                Four-week Audit
                            </h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                @include('trainings.iqa.four_week_audit.form')
                            </div>
                            @if(!$audit->signed())
                            <div class="widget-toolbox padding-8 clearfix">
                                <div class="center">
                                    <button class="btn btn-sm btn-success btn-round" type="submit">
                                        <i class="ace-icon fa fa-save bigger-110"></i> Save Information
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>
            </div>


            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@push('after-scripts')
    <script></script>
@endpush
