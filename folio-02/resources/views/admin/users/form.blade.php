<div class="widget-box widget-color-green">
    <div class="widget-header">
        <h4 class="widget-title">User Details</h4>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <div class="row">
                <div class="col-md-6">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="smaller">Basic Details</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                @if (isset($user))
                                    {!! Form::hidden('user_type', $user->user_type, ['id' => 'user_type']) !!}
                                @else
                                    <div
                                        class="form-group row required {{ $errors->has('user_type') ? 'has-error' : '' }}">
                                        {!! Form::label('user_type', 'User Type', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('user_type', \App\Models\LookupManager::getUserTypes(), null, [
                                                'class' => 'form-control',
                                                'required',
                                                'id' => 'user_type'
                                            ]) !!}
                                            {!! $errors->first('user_type', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                @endif

                                <div id="assessor-type-wrapper" class="form-group row" style="display:none;">
                                    {!! Form::label('assessor_type', 'Assessor Type', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('assessor_type', [
                                            'progress_coach' => 'Progress Coach',
                                            'tutor' => 'Tutor'
                                        ], null, [
                                            'class' => 'form-control',
                                        ]) !!}
                                        {!! $errors->first('assessor_type', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div
                                    class="form-group row required {{ $errors->has('firstnames') ? 'has-error' : '' }}">
                                    {!! Form::label('firstnames', 'Firstname(s)', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('firstnames', null, ['class' => 'form-control ', 'required', 'maxlength' => '50']) !!}
                                        {!! $errors->first('firstnames', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row required {{ $errors->has('surname') ? 'has-error' : '' }}">
                                    {!! Form::label('surname', 'Surname', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('surname', null, ['class' => 'form-control ', 'required', 'maxlength' => '50']) !!}
                                        {!! $errors->first('surname', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                @php
                                    $genderValue = old('gender', $user->gender ?? null);
                                    $predefinedGenders = ['M', 'F', 'NB', 'U'];
                                    $selectedGender = in_array($genderValue, $predefinedGenders) ? $genderValue : 'SELF';
                                    $selfDescribeValue = in_array($genderValue, $predefinedGenders) ? null : $genderValue;
                                @endphp
                               <div class="form-group row {{ $errors->has('gender') ? 'has-error' : '' }}">
                                    {!! Form::label('gender', 'Gender', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('gender', \App\Models\LookupManager::getGenderDDL(), $genderValue ? $selectedGender : null, [
                                            'class' => 'form-control',
                                            'id' => 'gender-select'
                                        ]) !!}

                                         <div id="self-describe-wrapper" style="margin-top:8px; {{ $selectedGender === 'SELF' ? '' : 'display:none;' }}">
                                            {!! Form::text('gender_self_describe', $selfDescribeValue, [
                                                'class' => 'form-control',
                                                'placeholder' => 'Please specify',
                                                'maxlength' => 40,
                                            ]) !!}
                                        </div>

                                        {!! $errors->first('gender', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div
                                    class="form-group row required {{ $errors->has('primary_email') ? 'has-error' : '' }}">
                                    {!! Form::label('primary_email', 'Primary Email', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::email('primary_email', null, [
                                            'class' => 'form-control ',
                                            'maxlength' => '191',
                                            'required',
                                        ]) !!}
                                        {!! $errors->first('primary_email', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('secondary_email') ? 'has-error' : '' }}">
                                    {!! Form::label('secondary_email', 'Secondary Email', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::email('secondary_email', null, ['class' => 'form-control ', 'maxlength' => '191']) !!}
                                        {!! $errors->first('secondary_emaili', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('fb_id') ? 'has-error' : '' }}">
                                    {!! Html::decode(
                                        Form::label('fb_id', 'Facebook ID <i class="ace-icon fa fa-facebook-square blue"></i>', [
                                            'class' => 'col-sm-4 control-label',
                                        ]),
                                    ) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('fb_id', null, ['class' => 'form-control ', 'maxlength' => '255']) !!}
                                        {!! $errors->first('fb_id', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('twitter_handle') ? 'has-error' : '' }}">
                                    {!! Html::decode(
                                        Form::label('twitter_handle', 'Twitter Handle <i class="ace-icon fa fa-twitter-square light-blue"></i>', [
                                            'class' => 'col-sm-4 control-label',
                                            'maxlength' => '191',
                                        ]),
                                    ) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('twitter_handle', null, ['class' => 'form-control ', 'maxlength' => '255']) !!}
                                        {!! $errors->first('twitter_handle', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                @if(isset($user) && $user->isAssessor() && auth()->user()->isAdmin())
                                <div
                                    class="form-group row {{ $errors->has('rag_rating') ? 'has-error' : '' }}">
                                    {!! Form::label('rag_rating', 'RAG Rating', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('rag_rating', ['red' => 'Red', 'amber' => 'Amber', 'green' => 'Green'], null, [
                                            'class' => 'form-control',
                                            'placeholder' => '',
                                        ]) !!}
                                        {!! $errors->first('rag_rating', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="smaller">Application Access</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                @if (isset($user))
                                    <div class="form-group row ">
                                        {!! Form::label('username', 'Username', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            <h5><code class="text-info bolder"> {{ $user->username }}</code></h5>
                                        </div>
                                    </div>
                                @else
                                    <div class="form-group row required {{ $errors->has('username') ? 'has-error' : '' }}">
                                        {!! Form::label('username', 'Username', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            @include('partials.username_input_control', ['onfocus' => true, 'onkeypress' => true])
                                        </div>
                                    </div>
                                @endif

                                <div
                                    class="form-group row required {{ $errors->has('web_access') ? 'has-error' : '' }}">
                                    {!! Form::label('web_access', 'Web Access', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        <label>
                                            {!! Form::checkbox('web_access', null, isset($user) && $user->web_access ? true : false, [
                                                'class' => 'ace ace-switch ace-switch-7',
                                            ]) !!}
                                            <span class="lbl"></span><br>
                                            <span class="text-info small">
                                                <i class="fa fa-info-circle"></i> This will determine whether user is
                                                allowed to login into the system.
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                @if (!isset($user))
                                    <div
                                        class="form-group row required {{ $errors->has('send_login_details') ? 'has-error' : '' }}">
                                        {!! Form::label('send_login_details', 'Send Login Credentials', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            <label>
                                                {!! Form::checkbox('send_login_details', null, true, ['class' => 'ace ace-switch ace-switch-5']) !!}
                                                <span class="lbl"></span><br>
                                                <span class="text-info small">
                                                    <i class="fa fa-info-circle"></i> Do you want to send login
                                                    credentials email to the user.
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                    @if(!isset($user) || (isset($user) && $user->user_type == App\Models\Lookups\UserTypeLookup::TYPE_EMPLOYER_USER))
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

            <div class="row">
                <div class="col-md-6">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="smaller">Work Address</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <span class="btn btn-sm btn-round btn-info" id="btnPopulateWorkAddressFromEmployer" style="display: none;">Populate from selected employer</span>
                                <div class="space-6"></div>
                                <div
                                    class="form-group row {{ $errors->has('work_address_line_1') ? 'has-error' : '' }}">
                                    {!! Form::label('work_address_line_1', 'Line 1', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('work_address_line_1', $workAddress->address_line_1, [
                                            'class' => 'form-control ',
                                            'maxlength' => '50',
                                        ]) !!}
                                        {!! $errors->first('work_address_line_1', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('work_address_line_2') ? 'has-error' : '' }}">
                                    {!! Form::label('work_address_line_2', 'Line 2', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('work_address_line_2', $workAddress->address_line_2, [
                                            'class' => 'form-control ',
                                            'maxlength' => '50',
                                        ]) !!}
                                        {!! $errors->first('work_address_line_2', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('work_address_line_3') ? 'has-error' : '' }}">
                                    {!! Form::label('work_address_line_3', 'Line 3', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('work_address_line_3', $workAddress->address_line_3, [
                                            'class' => 'form-control ',
                                            'maxlength' => '50',
                                        ]) !!}
                                        {!! $errors->first('work_address_line_3', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('work_address_line_4') ? 'has-error' : '' }}">
                                    {!! Form::label('work_address_line_4', 'Line 4', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('work_address_line_4', $workAddress->address_line_4, [
                                            'class' => 'form-control ',
                                            'maxlength' => '50',
                                        ]) !!}
                                        {!! $errors->first('work_address_line_4', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('work_postcode') ? 'has-error' : '' }}">
                                    {!! Form::label('work_postcode', 'Postcode', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('work_postcode', $workAddress->postcode, [
                                            'class' => 'form-control ',
                                            'maxlength' => '15',
                                        ]) !!}
                                        {!! $errors->first('work_postcode', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('work_telephone') ? 'has-error' : '' }}">
                                    {!! Form::label('work_telephone', 'Telephone', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('work_telephone', $workAddress->telephone, [
                                            'class' => 'form-control ',
                                            'maxlength' => '20',
                                        ]) !!}
                                        {!! $errors->first('work_telephone', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('work_mobile') ? 'has-error' : '' }}">
                                    {!! Form::label('work_mobile', 'Mobile', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('work_mobile', $workAddress->mobile, [
                                            'class' => 'form-control ',
                                            'maxlength' => '20',
                                        ]) !!}
                                        {!! $errors->first('work_mobile', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="smaller">Home Address</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div
                                    class="form-group row {{ $errors->has('home_address_line_1') ? 'has-error' : '' }}">
                                    {!! Form::label('home_address_line_1', 'Line 1', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('home_address_line_1', $homeAddress->address_line_1, [
                                            'class' => 'form-control ',
                                            'maxlength' => '50',
                                        ]) !!}
                                        {!! $errors->first('home_address_line_1', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('home_address_line_2') ? 'has-error' : '' }}">
                                    {!! Form::label('home_address_line_2', 'Line 2', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('home_address_line_2', $homeAddress->address_line_2, [
                                            'class' => 'form-control ',
                                            'maxlength' => '50',
                                        ]) !!}
                                        {!! $errors->first('home_address_line_2', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('home_address_line_3') ? 'has-error' : '' }}">
                                    {!! Form::label('home_address_line_3', 'Line 3', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('home_address_line_3', $homeAddress->address_line_3, [
                                            'class' => 'form-control ',
                                            'maxlength' => '50',
                                        ]) !!}
                                        {!! $errors->first('home_address_line_3', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('home_address_line_4') ? 'has-error' : '' }}">
                                    {!! Form::label('home_address_line_4', 'Line 4', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('home_address_line_4', $homeAddress->address_line_4, [
                                            'class' => 'form-control ',
                                            'maxlength' => '50',
                                        ]) !!}
                                        {!! $errors->first('home_address_line_4', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('home_postcode') ? 'has-error' : '' }}">
                                    {!! Form::label('home_postcode', 'Postcode', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('home_postcode', $homeAddress->postcode, [
                                            'class' => 'form-control ',
                                            'maxlength' => '15',
                                        ]) !!}
                                        {!! $errors->first('home_postcode', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('home_telephone') ? 'has-error' : '' }}">
                                    {!! Form::label('home_telephone', 'Telephone', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('home_telephone', $homeAddress->telephone, [
                                            'class' => 'form-control ',
                                            'maxlength' => '20',
                                        ]) !!}
                                        {!! $errors->first('home_telephone', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('home_mobile') ? 'has-error' : '' }}">
                                    {!! Form::label('home_mobile', 'Mobile', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('home_mobile', $homeAddress->mobile, [
                                            'class' => 'form-control ',
                                            'maxlength' => '20',
                                        ]) !!}
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
                    Save Information
                </button>
            </div>
        </div>
    </div>
</div>

