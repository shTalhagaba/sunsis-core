<div class="row">
    <div class="col-sm-12 ">
        <div class="widget-box widget-color-green">
            <div class="widget-header">
                <h4 class="smaller">ALS Review Details</h4>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="row">
                        <div class="col-sm-8">
                            <div
                                class="form-group row required {{ $errors->has('assessor') ? 'has-error' : '' }}">
                                {!! Form::label('assessor', 'Assessor', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('assessor', $assessorList, null, [
                                        'class' => 'form-control',
                                        'placeholder' => '',
                                        'required',
                                    ]) !!}
                                    {!! $errors->first('assessor', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('tutor') ? 'has-error' : '' }}">
                                {!! Form::label('tutor', 'Tutor', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('tutor', $tutorsList, null, [
                                        'class' => 'form-control',
                                        'placeholder' => '',
                                    ]) !!}
                                    {!! $errors->first('tutor', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div
                                class="form-group row required {{ $errors->has('planned_date') ? 'has-error' : '' }}">
                                {!! Form::label('planned_date', 'Planned Date', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::date(
                                        'planned_date',
                                        isset($alsReview) && !is_null($alsReview->planned_date) ? $alsReview->planned_date->format('Y-m-d') : null,
                                        [
                                            'class' => 'form-control',
                                            'required',
                                        ],
                                    ) !!}
                                    {!! $errors->first('planned_date', '<p class="text-danger">:message</p>') !!}
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