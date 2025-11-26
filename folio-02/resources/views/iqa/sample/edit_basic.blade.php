@extends('layouts.master')

@section('title', 'Edit IQA Sample Plan')

@section('page-content')
    <div class="page-header">
        <h1>Edit IQA Sample Plan</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                        onclick="window.location.href='{{ route('iqa_sample_plans.show', $plan) }}'">
                        <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
                    </button>
                    <div class="hr hr-12 hr-dotted"></div>
                </div>
            </div>
            
            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-8">
                    <div class="space"></div>

                    {!! Form::model($plan, [
                        'method' => 'PATCH',
                        'url' => route('iqa_sample_plans.update_basic', $plan),
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'id' => 'frmIqaSamplePlan',
                        'name' => 'frmIqaSamplePlan',
                    ]) !!}
                    
                    <div class="widget-box widget-color-green">
                        <div class="widget-header">
                            <h4 class="widget-title">IQA Sample Basic Details</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="form-group row required {{ $errors->has('title') ? 'has-error' : '' }}">
                                    {!! Form::label('title', 'Title', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('title', $plan->title, ['class' => 'form-control', 'required', 'maxlength' => '70']) !!}
                                        {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row required {{ $errors->has('type') ? 'has-error' : '' }}">
                                    {!! Form::label('type', 'Type', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select(
                                            'type',
                                            App\Models\IQA\IqaSamplePlan::getTypeList(),
                                            $plan->type,
                                            ['class' => 'form-control', 'placeholder' => '', 'required', 'id' => 'type'],
                                        ) !!}
                                        {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div
                                    class="form-group row required {{ $errors->has('completed_by_date') ? 'has-error' : '' }}">
                                    {!! Form::label('completed_by_date', 'Completed By Date', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::date('completed_by_date', $plan->completed_by_date->format('Y-m-d'), ['class' => 'form-control', 'required']) !!}
                                        {!! $errors->first('completed_by_date', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>                                
                            </div>
                            <div class="widget-toolbox padding-8 clearfix">
                                <div class="center">
                                    <button class="btn btn-sm btn-success btn-round" type="submit">
                                        <i class="ace-icon fa fa-save bigger-110"></i> Save Basic Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('page-inline-scripts')

    <script>
        $(function() {
            $("form[name=frmIqaSamplePlan]").on('submit', function(){
                var form = $(this);
                form.find(':submit').attr("disabled", true);
                form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
                return true;
            });
        });
        
    </script>

@endsection
