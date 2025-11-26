@extends('layouts.master')

@section('title', 'Edit Staff Development Support Entry')

@section('page-content')
    <div class="page-header">
        <h1>
            Edit Development Support Entry
        </h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('staff_development_support.show', ['staff_development_support' => $staffDevelopmentSupport]) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    @include('staff_development_support.view_details')

                    <div class="space-4"></div>
                    @include('staff_development_support.view_signatures')

                    @if (!$staffDevelopmentSupport->fullySigned())
                        <div class="space"></div>
                        {!! Form::open([
                            'url' => route('staff_development_support.saveSupportToSignForm', ['staff_development_support' => $staffDevelopmentSupport]),
                            'method' => 'PATCH',
                            'class' => 'form-horizontal',
                            'files' => true,
                            'id' => 'frmStaffDevelopmentSupport',
                        ]) !!}
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td>
                                        <span class="bolder">Staff comments: Describe what you have learnt and confirm how you will put this into practice? *</span>
                                        {!! Form::textarea('staff_comments', $details->staff_comments ?? '', ['class' => 'form-control', 'required']) !!}
                                        {!! $errors->first('staff_comments', '<p class="text-danger">:message</p>') !!}                
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3">
                                <div class="alert alert-info">
                                    <p>If you want to save and come back later, please leave the signature tickbox unticked.</p>
                                    <p>If the form is completed, tick the signature checkbox and Save.</p>
                                </div>
                                <div class="space-4"></div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3">
                                <div class="control-group">
                                    <div class="checkbox">
                                        <label>
                                            <input name="support_to_sign"  type="checkbox" value="1" class="ace input-lg" >
                                            <span class="lbl bolder"> &nbsp; Tick this option to confirm your signature if the form is fully completed.</span>
                                            <div class="space-2"></div>
                                            <span class="text-info small" style="margin-left: 2%"> 
                                                &nbsp; <i class="fa fa-info-circle"></i> 
                                                After you tick this option and save then form will be locked for further changes.
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <br>
                                {!! $errors->first('support_to_sign', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>

                        <div class="widget-toolbox padding-8 clearfix">
                            <div class="center">
                                <button class="btn btn-sm btn-success btn-round" type="submit">
                                    <i class="ace-icon fa fa-save bigger-110"></i>Save Information
                                </button>
                            </div>
                        </div>

                        {!! Form::close() !!}
                    @endif
                </div><!-- /.span -->
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@push('after-scripts')
    <script>
        $("form[name=frmStaffDevelopmentSupport]").on('submit', function() {
            var form = $(this);
            form.find(':submit').attr("disabled", true);
            form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
            return true;
        });
    </script>
@endpush
