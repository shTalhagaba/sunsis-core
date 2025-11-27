<div class="row">
    <div class="col-sm-12 ">
        <div class="widget-box widget-color-green">
            <div class="widget-header">
                <h4 class="widget-title">EQA Sample Details</h4>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="form-group row {{ $errors->has('active') ? 'has-error' : ''}}">
                        {!! Form::label('active', 'Active', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::select('active', [1 => 'Yes', 2 => 'No'], null, ['class' => 'form-control', 'required', 'id' => 'active', 'placeholder' => '']) !!}
                            {!! $errors->first('Active', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row required {{ $errors->has('title') ? 'has-error' : ''}}">
                        {!! Form::label('title', 'Title', ['class' => 'col-sm-4 control-label
                        no-padding-right']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('title', null, ['class' => 'form-control inputLimiter',
                            'required', 'maxlength' => '150']) !!}
                            {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row required {{ $errors->has('active_from') ? 'has-error' : ''}}">
                        {!! Form::label('active_from', 'Active From', ['class' => 'col-sm-4
                        control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::date('active_from', null, ['class' => 'form-control', 'required']) !!}
                            {!! $errors->first('active_from', '<p class="text-danger">:message</p>')
                            !!}
                        </div>
                    </div>
                    <div class="form-group row required {{ $errors->has('active_to') ? 'has-error' : ''}}">
                        {!! Form::label('active_to', 'Active To', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::date('active_to', null, ['class' => 'form-control', 'required']) !!}
                            {!! $errors->first('active_to', '<p class="text-danger">:message</p>')
                            !!}
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('eqa_personnels') ? 'has-error' : ''}}">
                        {!! Form::label('eqa_personnels', 'EQA Personnels', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::select('eqa_personnels[]', \App\Models\LookupManager::getEqaDDL(), $eqa_personnels, ['class' => 'form-control chosen-select', 'required', 'multiple', 'id' => 'eqa_personnel']) !!}
                            {!! $errors->first('eqa_personnels', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('tr_ids') ? 'has-error' : ''}}">
                        {!! Form::label('tr_ids', 'Training Records', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::select('tr_ids[]', $training_records_ddl, $training_records, ['class' => 'form-control chosen-select', 'required', 'multiple', 'id' => 'tr_ids']) !!}
                            {!! $errors->first('tr_ids', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="widget-toolbox padding-8 clearfix">
                    <div class="center">

                        <button class="btn btn-sm btn-round btn-success" type="submit">
                            <i class="ace-icon fa fa-save bigger-110"></i>
                            Save Sample
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

