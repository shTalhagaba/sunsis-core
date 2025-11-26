<div class="row">
    <div class="col-sm-12 ">
        <div class="widget-box widget-color-green">
            <div class="widget-header">
                <h4 class="smaller">ALS Assessment Plan Basic Details</h4>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="row">
                        <div class="col-sm-8">
                            <div
                                class="form-group row required {{ $errors->has('assessor_id') ? 'has-error' : '' }}">
                                {!! Form::label('assessor_id', 'Assessor', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('assessor_id', $assessorList, null, [
                                        'class' => 'form-control',
                                        'placeholder' => '',
                                        'required',
                                    ]) !!}
                                    {!! $errors->first('assessor_id', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('fs_tutor_id') ? 'has-error' : '' }}">
                                {!! Form::label('fs_tutor_id', 'Functional Skills Tutor', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('fs_tutor_id', $tutorsList, null, [
                                        'class' => 'form-control',
                                        'placeholder' => '',
                                    ]) !!}
                                    {!! $errors->first('fs_tutor_id', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('iqa_id') ? 'has-error' : '' }}">
                                {!! Form::label('iqa_id', 'IQA/Verifier', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('iqa_id', $verifiersList, null, [
                                        'class' => 'form-control',
                                        'placeholder' => '',
                                        'required',
                                    ]) !!}
                                    {!! $errors->first('iqa_id', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('als_tutor_id') ? 'has-error' : '' }}">
                                {!! Form::label('als_tutor_id', 'ALS Tutor', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('als_tutor_id', $tutorsList, null, [
                                        'class' => 'form-control',
                                        'placeholder' => '',
                                    ]) !!}
                                    {!! $errors->first('als_tutor_id', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div
                                class="form-group row required {{ $errors->has('referral_date') ? 'has-error' : '' }}">
                                {!! Form::label('referral_date', 'Referral Date', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::date(
                                        'referral_date',
                                        isset($alsAssessment) && !is_null($alsAssessment->referral_date) ? $alsAssessment->referral_date->format('Y-m-d') : null,
                                        [
                                            'class' => 'form-control',
                                            'required',
                                        ],
                                    ) !!}
                                    {!! $errors->first('referral_date', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div
                                class="form-group row required {{ $errors->has('als_meeting_date') ? 'has-error' : '' }}">
                                {!! Form::label('als_meeting_date', 'Date of ALS meeting', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::date(
                                        'als_meeting_date',
                                        isset($alsAssessment) && !is_null($alsAssessment->als_meeting_date) ? $alsAssessment->als_meeting_date->format('Y-m-d') : null,
                                        [
                                            'class' => 'form-control',
                                            'required',
                                        ],
                                    ) !!}
                                    {!! $errors->first('als_meeting_date', '<p class="text-danger">:message</p>') !!}
                                </div>
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
</div>