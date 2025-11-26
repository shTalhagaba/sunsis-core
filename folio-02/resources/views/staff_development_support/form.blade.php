<div class="row">
    <div class="col-sm-12 ">
        <div class="widget-box widget-color-green">
            <div class="widget-header">
                <h4 class="smaller">Staff Development Support Form</h4>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="row">
                        <div class="col-sm-6">
                            {!! Form::hidden('support_from_id', auth()->user()->id) !!}
                            <div class="form-group row required {{ $errors->has('support_to_id') ? 'has-error' : '' }}">
                                {!! Form::label('support_to_id', 'Staff Name', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('support_to_id', $supportToList, null, ['class' => 'form-control', 'placeholder' => '', 'required']) !!}
                                    {!! $errors->first('support_to_id', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('support_type') ? 'has-error' : '' }}">
                                {!! Form::label('support_type', 'Support Type', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('support_type', null, ['class' => 'form-control', 'required', 'maxlength' => '50']) !!}
                                    {!! $errors->first('support_type', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row required {{ $errors->has('provision_date') ? 'has-error' : '' }}">
                                {!! Form::label('provision_date', 'Date', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::date('provision_date', $staffDevelopmentSupport->provision_date ?? '' , ['class' => 'form-control', 'required']) !!}
                                    {!! $errors->first('provision_date', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('duration') ? 'has-error' : '' }}">
                                {!! Form::label('duration', 'Duration', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::time('duration', isset($entry->duration) ? \Carbon\Carbon::parse($entry->duration)->format('H:i') : null, [
                                        'class' => 'form-control',
                                        'required',
                                    ]) !!}
                                    {!! $errors->first('duration', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    @foreach ($questionsList as $questionId => $questionDesc)
                                    <tr>
                                        <td>
                                            <span class="bolder">{{ $questionDesc }}</span>
                                            {!! Form::textarea($questionId, $details->$questionId ?? '', ['class' => 'form-control']) !!}
                                            {!! $errors->first($questionId, '<p class="text-danger">:message</p>') !!}                
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="alert alert-info">
                                <p>If you want to save and come back later, please leave the signature tickbox unticked.</p>
                                <p>If the form is completed, tick the signature checkbox and Save. System will then notify the staff personnel to view and sign.</p>
                            </div>
                            <div class="space-4"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="control-group">
                                <div class="checkbox">
                                    <label>
                                        <input name="support_from_sign"  type="checkbox" value="1" class="ace input-lg" >
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
                            {!! $errors->first('support_from_sign', '<p class="text-danger">:message</p>') !!}
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
</div>
