<div class="widget-box widget-color-green">
    <div class="widget-header">
        <h4 class="widget-title">User Details</h4>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <div class="row">
                <div class="col-sm-6">
                   <div class="widget-box">
                      <div class="widget-header"><h4 class="smaller">Basic Details</h4></div>
                      <div class="widget-body">
                         <div class="widget-main">
                           <div class="form-group row required {{ $errors->has('user_type') ? 'has-error' : ''}}">
                                {!! Form::label('user_type', 'User Type', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('user_type', \App\Models\LookupManager::getUserTypes(), null, ['class' => 'form-control', 'required']) !!}
                                    {!! $errors->first('user_type', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('firstnames') ? 'has-error' : ''}}">
                                {!! Form::label('firstnames', 'Firstname(s)', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('firstnames', null, ['class' => 'form-control inputLimiter', 'required', 'maxlength' => '100']) !!}
                                    {!! $errors->first('firstnames', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('surname') ? 'has-error' : ''}}">
                                {!! Form::label('surname', 'Surname', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('surname', null, ['class' => 'form-control inputLimiter', 'required', 'maxlength' => '100']) !!}
                                    {!! $errors->first('surname', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('gender') ? 'has-error' : ''}}">
                                {!! Form::label('gender', 'Gender', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('gender', array_merge(\App\Models\LookupManager::getGenderDDL(), ['U' => 'Unknown']), null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('gender', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('ni') ? 'has-error' : ''}}">
                                {!! Form::label('ni', 'National Insurance', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('ni', null, ['class' => 'form-control inputLimiter', 'maxlength' => '17']) !!}
                                    {!! $errors->first('ni', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('primary_email') ? 'has-error' : ''}}">
                                {!! Form::label('primary_email', 'Primary Email', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::email('primary_email', null, ['class' => 'form-control inputLimiter', 'maxlength' => '255', 'required']) !!}
                                    {!! $errors->first('primary_email', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('secondary_email') ? 'has-error' : ''}}">
                                {!! Form::label('secondary_email', 'Secondary Email', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::email('secondary_email', null, ['class' => 'form-control inputLimiter', 'maxlength' => '255']) !!}
                                    {!! $errors->first('secondary_emaili', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('fb_id') ? 'has-error' : ''}}">
                                {!! Html::decode(Form::label('fb_id', 'Facebook ID <i class="ace-icon fa fa-facebook-square blue"></i>', ['class' => 'col-sm-4 control-label'])) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('fb_id', null, ['class' => 'form-control inputLimiter', 'maxlength' => '255']) !!}
                                    {!! $errors->first('fb_id', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('twitter_handle') ? 'has-error' : ''}}">
                             {!! Html::decode(Form::label('twitter_handle', 'Twitter Handle <i class="ace-icon fa fa-twitter-square light-blue"></i>', ['class' => 'col-sm-4 control-label', 'maxlength' => '191'])) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('twitter_handle', null, ['class' => 'form-control inputLimiter', 'maxlength' => '255']) !!}
                                    {!! $errors->first('twitter_handle', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                         </div>
                      </div>
                   </div>
                </div>

                <div class="col-sm-6">
                   <div class="widget-box">
                      <div class="widget-header"><h4 class="smaller">Application Access</h4></div>
                      <div class="widget-body">
                         <div class="widget-main">
                            @if(isset($user))
                            <div class="form-group row ">
                                {!! Form::label('email', 'Username Email', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    <code class="text-info"> {{ $user->email }}</code>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-info"><i class="fa fa-info-circle"></i> Login details will be sent to this username email. </div>
                            <div class="form-group row required {{ $errors->has('email') ? 'has-error' : ''}}">
                                {!! Form::label('email', 'Username Email', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::email('email', null, ['class' => 'form-control inputLimiter', 'maxlength' => '191', 'required']) !!}
                                    {!! $errors->first('email', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            @endif

                            <div class="form-group row required {{ $errors->has('web_access') ? 'has-error' : ''}}">
                              {!! Form::label('web_access', 'Web Access', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    <label>
                                     {!! Form::checkbox('web_access', null, isset($user)&&($user->web_access)?true:false, ['class' => 'ace ace-switch ace-switch-7']) !!}
                                        <span class="lbl"></span>
                                     </label>
                                </div>
                            </div>
                            @if(!isset($user) || (isset($user) && $user->getOriginal('user_type') == App\Models\User::TYPE_EMPLOYER_USER))
                            <div class="widget-box" {!! !isset($user) ? 'style="display: none;"' : '' !!} id="divEmployer">
                                <div class="widget-header">
                                    <h4 class="smaller">Employer and Location</h4>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div
                                            class="form-group row required {{ $errors->has('employer_location') ? 'has-error' : '' }}">
                                            {!! Form::label('employer_location', 'Employer', ['class' => 'col-sm-2 control-label']) !!}
                                            <div class="col-sm-10">
                                                {!! Form::select('employer_location', $employers, null, [
                                                    'class' => 'form-control',
                                                    'placeholder' => '',
                                                ]) !!}
                                                {!! $errors->first('employer_location', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>{{-- employer_location box --}}
                            @endif
                         </div>

                      </div>
                   </div>
                </div>
             </div>

             <div class="row">
                <div class="col-sm-6">
                   <div class="widget-box">
                      <div class="widget-header"><h4 class="smaller">Work Address</h4></div>
                      <div class="widget-body">
                         <div class="widget-main">
                            <div class="form-group row {{ $errors->has('work_address_line_1') ? 'has-error' : ''}}">
                                {!! Form::label('work_address_line_1', 'Line 1', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('work_address_line_1', $work_address->address_line_1, ['class' => 'form-control inputLimiter', 'maxlength' => '100']) !!}
                                    {!! $errors->first('work_address_line_1', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('work_address_line_2') ? 'has-error' : ''}}">
                                {!! Form::label('work_address_line_2', 'Line 2', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('work_address_line_2', $work_address->address_line_2, ['class' => 'form-control inputLimiter', 'maxlength' => '100']) !!}
                                    {!! $errors->first('work_address_line_2', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('work_address_line_3') ? 'has-error' : ''}}">
                                {!! Form::label('work_address_line_3', 'Line 3', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('work_address_line_3', $work_address->address_line_3, ['class' => 'form-control inputLimiter', 'maxlength' => '100']) !!}
                                    {!! $errors->first('work_address_line_3', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('work_address_line_4') ? 'has-error' : ''}}">
                                {!! Form::label('work_address_line_4', 'Line 4', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('work_address_line_4', $work_address->address_line_4, ['class' => 'form-control inputLimiter', 'maxlength' => '100']) !!}
                                    {!! $errors->first('work_address_line_4', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('work_postcode') ? 'has-error' : ''}}">
                                {!! Form::label('work_postcode', 'Postcode', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('work_postcode', $work_address->postcode, ['class' => 'form-control inputLimiter', 'maxlength' => '15']) !!}
                                    {!! $errors->first('work_postcode', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('work_telephone') ? 'has-error' : ''}}">
                                {!! Form::label('work_telephone', 'Telephone', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('work_telephone', $work_address->telephone, ['class' => 'form-control inputLimiter', 'maxlength' => '20']) !!}
                                    {!! $errors->first('work_telephone', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('work_mobile') ? 'has-error' : ''}}">
                                {!! Form::label('work_mobile', 'Mobile', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('work_mobile', $work_address->mobile, ['class' => 'form-control inputLimiter', 'maxlength' => '20']) !!}
                                    {!! $errors->first('work_mobile', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>

                         </div>
                      </div>
                   </div>
                </div>
                <div class="col-sm-6">
                   <div class="widget-box">
                      <div class="widget-header"><h4 class="smaller">Home Address</h4></div>
                      <div class="widget-body">
                         <div class="widget-main">
                            <div class="form-group row {{ $errors->has('home_address_line_1') ? 'has-error' : ''}}">
                                {!! Form::label('home_address_line_1', 'Line 1', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('home_address_line_1', $home_address->address_line_1, ['class' => 'form-control inputLimiter', 'maxlength' => '100']) !!}
                                    {!! $errors->first('home_address_line_1', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('home_address_line_2') ? 'has-error' : ''}}">
                                {!! Form::label('home_address_line_2', 'Line 2', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('home_address_line_2', $home_address->address_line_2, ['class' => 'form-control inputLimiter', 'maxlength' => '100']) !!}
                                    {!! $errors->first('home_address_line_2', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('home_address_line_3') ? 'has-error' : ''}}">
                                {!! Form::label('home_address_line_3', 'Line 3', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('home_address_line_3', $home_address->address_line_3, ['class' => 'form-control inputLimiter', 'maxlength' => '100']) !!}
                                    {!! $errors->first('home_address_line_3', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('home_address_line_4') ? 'has-error' : ''}}">
                                {!! Form::label('home_address_line_4', 'Line 4', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('home_address_line_4', $home_address->address_line_4, ['class' => 'form-control inputLimiter', 'maxlength' => '100']) !!}
                                    {!! $errors->first('home_address_line_4', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('home_postcode') ? 'has-error' : ''}}">
                                {!! Form::label('home_postcode', 'Postcode', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('home_postcode', $home_address->postcode, ['class' => 'form-control inputLimiter', 'maxlength' => '15']) !!}
                                    {!! $errors->first('home_postcode', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('home_telephone') ? 'has-error' : ''}}">
                                {!! Form::label('home_telephone', 'Telephone', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('home_telephone', $home_address->telephone, ['class' => 'form-control inputLimiter', 'maxlength' => '20']) !!}
                                    {!! $errors->first('home_telephone', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('home_mobile') ? 'has-error' : ''}}">
                                {!! Form::label('home_mobile', 'Mobile', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('home_mobile', $home_address->mobile, ['class' => 'form-control inputLimiter', 'maxlength' => '20']) !!}
                                    {!! $errors->first('home_mobile', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>

                         </div>
                      </div>
                   </div>
                </div>
             </div>
        </div>
        <div class="widget-toolbox padding-8 clearfix">
            <div class="center">

                <button class="btn btn-sm btn-success btn-round" type="submit">
                    <i class="ace-icon fa fa-save bigger-110"></i>
                    Save
                </button>

                &nbsp; &nbsp; &nbsp;
                <button class="btn btn-sm btn-round" type="reset">
                    <i class="ace-icon fa fa-undo bigger-110"></i>
                    Reset
                </button>

              </div>
        </div>
    </div>
</div>



