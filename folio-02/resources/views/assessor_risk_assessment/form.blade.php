<div class="row">
    <div class="col-sm-12 ">
        <div class="widget-box widget-color-green">
            <div class="widget-header">
                <h4 class="smaller">Assessor Risk Assessment Basic Details</h4>
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
                            <div
                                class="form-group row required {{ $errors->has('date_of_observation') ? 'has-error' : '' }}">
                                {!! Form::label('date_of_observation', 'Date of Observation', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::date(
                                        'date_of_observation',
                                        isset($riskAssessment) && !is_null($riskAssessment->date_of_observation) ? $riskAssessment->date_of_observation->format('Y-m-d') : now()->format('Y-m-d'),
                                        [
                                            'class' => 'form-control',
                                            'required',
                                        ],
                                    ) !!}
                                    {!! $errors->first('date_of_observation', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div
                                class="form-group row {{ $errors->has('date_of_last_observation') ? 'has-error' : '' }}">
                                {!! Form::label('date_of_last_observation', 'Date of Last Observation', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::date(
                                        'date_of_last_observation',
                                        isset($riskAssessment) && !is_null($riskAssessment->date_of_last_observation) ? $riskAssessment->date_of_last_observation->format('Y-m-d') : null,
                                        [
                                            'class' => 'form-control',
                                        ],
                                    ) !!}
                                    {!! $errors->first('date_of_last_observation', '<p class="text-danger">:message</p>') !!}
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