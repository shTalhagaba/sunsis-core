<div class="widget-box">
    <div class="widget-header">
        <h4 class="smaller">Location Details</h4>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            {!! Form::hidden('id', null, ['id' => 'id']) !!}
            {!! Form::hidden('organisation_id', $organisation->id) !!}
            {!! Form::hidden('referer', request()->headers->get('referer')) !!}


            <div class="form-group row required {{ $errors->has('is_legal_address') ? 'has-error' : '' }}">
                {!! Form::label('is_legal_address', 'Main Location', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                <div class="col-sm-8">
                    {!! Form::select('is_legal_address', ['0' => 'No', '1' => 'Yes'], null, [
                        'class' => 'form-control col-xs-10 col-sm-5',
                        'required' => 'required',
                    ]) !!}
                    {!! $errors->first('is_legal_address', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row required {{ $errors->has('legal_name') ? 'has-error' : '' }}">
                {!! Form::label('title', 'Title', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                <div class="col-sm-8">
                    {!! Form::text('title', null, [
                        'class' => 'form-control col-xs-10 col-sm-5',
                        'required' => 'required',
                        'maxlength' => '100',
                    ]) !!}
                    {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row required {{ $errors->has('address_line_1') ? 'has-error' : '' }}">
                {!! Form::label('address_line_1', 'Address Line 1', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                <div class="col-sm-8">
                    {!! Form::text('address_line_1', null, [
                        'class' => 'form-control col-xs-10 col-sm-5',
                        'required' => 'required',
                        'maxlength' => '70',
                    ]) !!}
                    {!! $errors->first('address_line_1', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row {{ $errors->has('address_line_2') ? 'has-error' : '' }}">
                {!! Form::label('address_line_2', 'Address Line 2', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                <div class="col-sm-8">
                    {!! Form::text('address_line_2', null, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '70']) !!}
                    {!! $errors->first('address_line_2', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row {{ $errors->has('address_line_3') ? 'has-error' : '' }}">
                {!! Form::label('address_line_3', 'Address Line 3', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                <div class="col-sm-8">
                    {!! Form::text('address_line_3', null, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '70']) !!}
                    {!! $errors->first('address_line_3', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row {{ $errors->has('address_line_4') ? 'has-error' : '' }}">
                {!! Form::label('address_line_4', 'Address Line 4', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                <div class="col-sm-8">
                    {!! Form::text('address_line_4', null, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '70']) !!}
                    {!! $errors->first('address_line_4', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row required {{ $errors->has('postcode') ? 'has-error' : '' }}">
                {!! Form::label('postcode', 'Postcode', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                <div class="col-sm-8">
                    {!! Form::text('postcode', null, [
                        'class' => 'form-control col-xs-10 col-sm-5',
                        'maxlength' => '15',
                        'required' => 'required',
                    ]) !!}
                    {!! $errors->first('postcode', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row {{ $errors->has('telephone') ? 'has-error' : '' }}">
                {!! Form::label('telephone', 'Telephone', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                <div class="col-sm-8">
                    {!! Form::text('telephone', null, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '20']) !!}
                    {!! $errors->first('telephone', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row {{ $errors->has('mobile') ? 'has-error' : '' }}">
                {!! Form::label('mobile', 'Mobile', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                <div class="col-sm-8">
                    {!! Form::text('mobile', null, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '20']) !!}
                    {!! $errors->first('mobile', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row {{ $errors->has('fax') ? 'has-error' : '' }}">
                {!! Form::label('fax', 'Fax', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                <div class="col-sm-8">
                    {!! Form::text('fax', null, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '20']) !!}
                    {!! $errors->first('fax', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
        </div>
        <div class="widget-toolbox padding-8 clearfix">
            <div class="clearfix center">
                <button class="btn btn-sm btn-success btn-round" type="submit">
                    <i class="ace-icon fa fa-save bigger-110"></i>
                    Save Information
                </button>
            </div>
        </div>
    </div>
</div>
