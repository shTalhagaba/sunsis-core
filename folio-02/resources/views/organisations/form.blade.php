<div class="widget-box widget-color-green">
    <div class="widget-header">
        <h4 class="widget-title">Employer Form</h4>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <div class="row">
                <div class="col-sm-6">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="smaller">Employer Details</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                {!! Form::hidden('org_type', $orgType) !!}
                                <div class="form-group row required {{ $errors->has('active') ? 'has-error' : '' }}">
                                    {!! Form::label('active', 'Status', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('active', [1 => 'Active', 0 => 'Not Active'], null, ['class' => 'form-control', 'required']) !!}
                                        {!! $errors->first('active', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div
                                    class="form-group row required {{ $errors->has('legal_name') ? 'has-error' : '' }}">
                                    {!! Form::label('legal_name', 'Legal Name', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('legal_name', null, [
                                            'class' => 'form-control col-xs-10 col-sm-5 inputLimiter',
                                            'required',
                                            'maxlength' => '100',
                                        ]) !!}
                                        {!! $errors->first('legal_name', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div
                                    class="form-group row required {{ $errors->has('trading_name') ? 'has-error' : '' }}">
                                    {!! Form::label('trading_name', 'Trading Name', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('trading_name', null, [
                                            'class' => 'form-control col-xs-10 col-sm-5 inputLimiter',
                                            'required',
                                            'maxlength' => '100',
                                        ]) !!}
                                        {!! $errors->first('trading_name', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('company_number') ? 'has-error' : '' }}">
                                    {!! Form::label('company_number', 'Company Number', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('company_number', null, [
                                            'class' => 'form-control col-xs-10 col-sm-5 inputLimiter',
                                            'maxlength' => '12',
                                        ]) !!}
                                        {!! $errors->first('company_number', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('vat_number') ? 'has-error' : '' }}">
                                    {!! Form::label('vat_number', 'VAT Number', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('vat_number', null, [
                                            'class' => 'form-control col-xs-10 col-sm-5 inputLimiter',
                                            'maxlength' => '12',
                                        ]) !!}
                                        {!! $errors->first('vat_number', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('edrs') ? 'has-error' : '' }}">
                                    {!! Form::label('edrs', 'EDRS', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('edrs', null, ['class' => 'form-control col-xs-10 col-sm-5 inputLimiter', 'maxlength' => '12']) !!}
                                        {!! $errors->first('edrs', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('sector') ? 'has-error' : '' }}">
                                    {!! Form::label('sector', 'Sector', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('sector', $sectors, null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('sector', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="smaller">Main Location Details</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="form-group row required {{ $errors->has('title') ? 'has-error' : '' }}">
                                    {!! Form::label('title', 'Location Title', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('title', $main_location->title, [
                                            'class' => 'form-control inputLimiter',
                                            'required',
                                            'maxlength' => 50,
                                        ]) !!}
                                        {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div
                                    class="form-group row required {{ $errors->has('address_line_1') ? 'has-error' : '' }}">
                                    {!! Form::label('address_line_1', 'Line 1', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('address_line_1', $main_location->address_line_1, [
                                            'class' => 'form-control inputLimiter',
                                            'required',
                                            'maxlength' => 70,
                                        ]) !!}
                                        {!! $errors->first('address_line_1', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('address_line_2') ? 'has-error' : '' }}">
                                    {!! Form::label('address_line_2', 'Line 2', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('address_line_2', $main_location->address_line_2, [
                                            'class' => 'form-control inputLimiter',
                                            'maxlength' => 70,
                                        ]) !!}
                                        {!! $errors->first('address_line_2', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('address_line_3') ? 'has-error' : '' }}">
                                    {!! Form::label('address_line_3', 'Line 3', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('address_line_3', $main_location->address_line_3, [
                                            'class' => 'form-control inputLimiter',
                                            'maxlength' => 70,
                                        ]) !!}
                                        {!! $errors->first('address_line_3', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('address_line_4') ? 'has-error' : '' }}">
                                    {!! Form::label('address_line_4', 'Line 4', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('address_line_4', $main_location->address_line_4, [
                                            'class' => 'form-control inputLimiter',
                                            'maxlength' => 70,
                                        ]) !!}
                                        {!! $errors->first('address_line_4', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row required {{ $errors->has('postcode') ? 'has-error' : '' }}">
                                    {!! Form::label('postcode', 'Postcode', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('postcode', $main_location->postcode, [
                                            'class' => 'form-control inputLimiter',
                                            'required',
                                            'maxlength' => 15,
                                        ]) !!}
                                        {!! $errors->first('postcode', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('telephone') ? 'has-error' : '' }}">
                                    {!! Form::label('telephone', 'Telephone', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('telephone', $main_location->telephone, [
                                            'class' => 'form-control inputLimiter',
                                            'maxlength' => 20,
                                        ]) !!}
                                        {!! $errors->first('telephone', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row required {{ $errors->has('mobile') ? 'has-error' : '' }}">
                                    {!! Form::label('mobile', 'Mobile', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('mobile', $main_location->mobile, ['class' => 'form-control inputLimiter', 'required', 'maxlength' => 20]) !!}
                                        {!! $errors->first('mobile', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>

                               <div class="form-group row required {{ $errors->has('email') ? 'has-error' : '' }}">
                                    {!! Form::label('email', 'Email', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::email('email', $main_location->email ?? null, [
                                            'class' => 'form-control',
                                            'required',
                                            'maxlength' => 50,
                                        ]) !!}
                                        {!! $errors->first('email', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('fax') ? 'has-error' : '' }}">
                                    {!! Form::label('fax', 'Fax', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('fax', $main_location->fax, ['class' => 'form-control inputLimiter', 'maxlength' => 20]) !!}
                                        {!! $errors->first('fax', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
