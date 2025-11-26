<p class="text-center">
    <img class="img-rounded" height="40px;" src="{{ asset('images/logos/'.App\Facades\AppConfig::get('FOLIO_LOGO_NAME')) }}" alt="Logo">
</p>
<div class="form-group row required {{ $errors->has('meeting_date') ? 'has-error' : '' }}">
    {!! Form::label('meeting_date', 'Actual Date', ['class' => 'col-sm-4 control-label']) !!}
    <div class="col-sm-4">
        {!! Form::date('meeting_date', $formData['meeting_date'] ?? now(), ['class' => 'form-control', 'required']) !!}
        {!! $errors->first('meeting_date', '<p class="text-danger">:message</p>') !!}
    </div>
</div>
<div class="form-group row required">
    {!! Form::label('performance', 'Behaviours and attitude to learning', ['class' => 'col-sm-4 control-label']) !!}
    <div class="col-sm-8 table-responsive">
        <table class="table table-bordered">
            <tr>
                <td class="success center" style="width: 30%"
                    onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
                    <p>Outstanding</p>
                    <input type="radio" value="1" name="performance" required {{ isset($formData['performance']) && $formData['performance'] == 1 ? 'checked' : '' }}>
                </td>
                <td class="info center" style="width: 30%"
                    onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
                    <p>Good</p>
                    <input type="radio" value="2" name="performance" required {{ isset($formData['performance']) && $formData['performance'] == 2 ? 'checked' : '' }}>
                </td>
                <td class="warning center" style="width: 40%"
                    onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
                    <p>Requires Improvement</p>
                    <input type="radio" value="3" name="performance" required {{ isset($formData['performance']) && $formData['performance'] == 3 ? 'checked' : '' }}>
                </td>
            </tr>
        </table>
        {!! $errors->first('performance', '<p class="text-danger">:message</p>') !!}
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-12">
        <hr>
        <h5 class="bolder center">Functional Skills</h5>
    </div>
</div>
<div class="form-group row required {{ $errors->has('fs_targets_met') ? 'has-error' : '' }}">
    {!! Form::label('fs_targets_met', 'Have your Functional Skills targets been met since last review?', [
        'class' => 'col-sm-4 control-label',
    ]) !!}
    <div class="col-sm-4 table-responsive">
        <table class="table table-bordered">
            <tr>
                <td class="center success" style="width: 30%"
                    onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
                    <p>Yes</p>
                    <input type="radio" value="Yes" name="fs_targets_met" required {{ isset($formData['fs_targets_met']) && $formData['fs_targets_met'] == 'Yes' ? 'checked' : '' }}>
                </td>
                <td class="center warning" class="center" style="width: 30%"
                    onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
                    <p>No</p>
                    <input type="radio" value="No" name="fs_targets_met" required {{ isset($formData['fs_targets_met']) && $formData['fs_targets_met'] == 'No' ? 'checked' : '' }}>
                </td>
            </tr>
        </table>
        {!! $errors->first('fs_targets_met', '<p class="text-danger">:message</p>') !!}
    </div>
</div>
<div class="form-group row required {{ $errors->has('booked_in_for_tests') ? 'has-error' : '' }}">
    {!! Form::label('booked_in_for_tests', 'Are you booked in for tests?', ['class' => 'col-sm-4 control-label']) !!}
    <div class="col-sm-4 table-responsive">
        <table class="table table-bordered">
            <tr>
                <td class="center success" style="width: 30%"
                    onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
                    <p>Yes</p>
                    <input type="radio" value="Yes" name="booked_in_for_tests" required {{ isset($formData['booked_in_for_tests']) && $formData['booked_in_for_tests'] == 'Yes' ? 'checked' : '' }}>
                </td>
                <td class="center warning" style="width: 30%"
                    onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
                    <p>No</p>
                    <input type="radio" value="No" name="booked_in_for_tests" required {{ isset($formData['booked_in_for_tests']) && $formData['booked_in_for_tests'] == 'No' ? 'checked' : '' }}>
                </td>
            </tr>
        </table>
        {!! $errors->first('booked_in_for_tests', '<p class="text-danger">:message</p>') !!}
    </div>
</div>
<div class="form-group row required {{ $errors->has('fs_smart_targets_achieved') ? 'has-error' : '' }}">
    {!! Form::label(
        'fs_smart_targets_achieved',
        'Has current delivery pathway been reviewed, have you achieved the SMART targets set previously and discussed targets moving forward for next review?',
        ['class' => 'col-sm-4 control-label'],
    ) !!}
    <div class="col-sm-4 table-responsive">
        <table class="table table-bordered">
            <tr>
                <td class="center success" style="width: 30%"
                    onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
                    <p>Yes</p>
                    <input type="radio" value="Yes" name="fs_smart_targets_achieved" required {{ isset($formData['fs_smart_targets_achieved']) && $formData['fs_smart_targets_achieved'] == 'Yes' ? 'checked' : '' }}>
                </td>
                <td class="center warning" style="width: 30%"
                    onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
                    <p>No</p>
                    <input type="radio" value="No" name="fs_smart_targets_achieved" required {{ isset($formData['fs_smart_targets_achieved']) && $formData['fs_smart_targets_achieved'] == 'No' ? 'checked' : '' }}>
                </td>
            </tr>
        </table>
        {!! $errors->first('fs_smart_targets_achieved', '<p class="text-danger">:message</p>') !!}
    </div>
</div>
<div class="form-group row required{{ $errors->has('target_details') ? 'has-error' : '' }}">
    {!! Form::label('target_details', 'Action/Update on Target', [
        'class' => 'col-sm-4 control-label
                                                                                no-padding-right',
    ]) !!}
    <div class="col-sm-8">
        {!! Form::textarea('target_details', $formData['target_details'] ?? null, [
            'class' => 'form-control',
            'placeholder' => 'Action/Update on target',
            'maxlength' => '5000',
        ]) !!}
        {!! $errors->first('target_details', '<p class="text-danger">:message</p>') !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-12">
        <hr>
        <h5 class="bolder center">Off the Job (OTJ) Plan</h5>
    </div>
</div>
<div class="form-group row required {{ $errors->has('otj_on_target') ? 'has-error' : '' }}">
    {!! Form::label('otj_on_target', 'Are your OTJ hours on target?', ['class' => 'col-sm-4 control-label']) !!}
    <div class="col-sm-4 table-responsive">
        <table class="table table-bordered">
            <tr>
                <td class="center success" style="width: 30%"
                    onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
                    <p>Yes</p>
                    <input type="radio" value="Yes" name="otj_on_target" required {{ isset($formData['otj_on_target']) && $formData['otj_on_target'] == 'Yes' ? 'checked' : '' }}>
                </td>
                <td class="center warning" style="width: 30%"
                    onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
                    <p>No</p>
                    <input type="radio" value="No" name="otj_on_target" required {{ isset($formData['otj_on_target']) && $formData['otj_on_target'] == 'No' ? 'checked' : '' }}>
                </td>
            </tr>
        </table>
        {!! $errors->first('otj_on_target', '<p class="text-danger">:message</p>') !!}
    </div>
</div>
<div class="form-group row required {{ $errors->has('added_timesheet') ? 'has-error' : '' }}">
    {!! Form::label(
        'added_timesheet',
        '(including standardised safeguarding and Prevent training) Have you added to your timesheet this week?',
        ['class' => 'col-sm-4 control-label'],
    ) !!}
    <div class="col-sm-4 table-responsive">
        <table class="table table-bordered">
            <tr>
                <td class="center success" style="width: 30%"
                    onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
                    <p>Yes</p>
                    <input type="radio" value="Yes" name="added_timesheet" required {{ isset($formData['added_timesheet']) && $formData['added_timesheet'] == 'Yes' ? 'checked' : '' }}>
                </td>
                <td class="center warning" style="width: 30%"
                    onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
                    <p>No</p>
                    <input type="radio" value="No" name="added_timesheet" required {{ isset($formData['added_timesheet']) && $formData['added_timesheet'] == 'No' ? 'checked' : '' }}>
                </td>
            </tr>
        </table>
        {!! $errors->first('added_timesheet', '<p class="text-danger">:message</p>') !!}
    </div>
</div>
<div class="form-group row required{{ $errors->has('otj_plan_details') ? 'has-error' : '' }}">
    {!! Form::label('otj_plan_details', 'Action/Update against Plan', [
        'class' => 'col-sm-4 control-label
                                                                                no-padding-right',
    ]) !!}
    <div class="col-sm-8">
        {!! Form::textarea('otj_plan_details', $formData['otj_plan_details'] ?? null, [
            'class' => 'form-control',
            'placeholder' => 'Action/Update against Plan',
            'maxlength' => '5000',
        ]) !!}
        {!! $errors->first('otj_plan_details', '<p class="text-danger">:message</p>') !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-12">
        <hr>
        <h5 class="bolder center">Main Aim</h5>
    </div>
</div>
<div class="form-group row required {{ $errors->has('on_target_towards_main_aim') ? 'has-error' : '' }}">
    {!! Form::label('on_target_towards_main_aim', 'Are you on target towards your main aim standard?', [
        'class' => 'col-sm-4 control-label',
    ]) !!}
    <div class="col-sm-4 table-responsive">
        <table class="table table-bordered">
            <tr>
                <td class="center success" style="width: 30%"
                    onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
                    <p>Yes</p>
                    <input type="radio" value="Yes" name="on_target_towards_main_aim" required {{ isset($formData['on_target_towards_main_aim']) && $formData['on_target_towards_main_aim'] == 'Yes' ? 'checked' : '' }}>
                </td>
                <td class="center warning" style="width: 30%"
                    onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
                    <p>No</p>
                    <input type="radio" value="No" name="on_target_towards_main_aim" required {{ isset($formData['on_target_towards_main_aim']) && $formData['on_target_towards_main_aim'] == 'No' ? 'checked' : '' }}>
                </td>
            </tr>
        </table>
        {!! $errors->first('on_target_towards_main_aim', '<p class="text-danger">:message</p>') !!}
    </div>
</div>
<div class="form-group row required {{ $errors->has('main_aim_previous_targets_achieved') ? 'has-error' : '' }}">
    {!! Form::label('main_aim_previous_targets_achieved', 'Have you met your targets set at your previous review?', [
        'class' => 'col-sm-4 control-label',
    ]) !!}
    <div class="col-sm-4 table-responsive">
        <table class="table table-bordered">
            <tr>
                <td class="center success" style="width: 30%"
                    onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
                    <p>Yes</p>
                    <input type="radio" value="Yes" name="main_aim_previous_targets_achieved" required {{ isset($formData['main_aim_previous_targets_achieved']) && $formData['main_aim_previous_targets_achieved'] == 'Yes' ? 'checked' : '' }}>
                </td>
                <td class="center warning" style="width: 30%"
                    onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
                    <p>No</p>
                    <input type="radio" value="No" name="main_aim_previous_targets_achieved" required {{ isset($formData['main_aim_previous_targets_achieved']) && $formData['main_aim_previous_targets_achieved'] == 'No' ? 'checked' : '' }}>
                </td>
            </tr>
        </table>
        {!! $errors->first('main_aim_previous_targets_achieved', '<p class="text-danger">:message</p>') !!}
    </div>
</div>
<div class="form-group row required{{ $errors->has('smart_targets_details') ? 'has-error' : '' }}">
    {!! Form::label('smart_targets_details', 'What are your SMART targets moving forward?', [
        'class' => 'col-sm-4 control-label no-padding-right',
    ]) !!}
    <div class="col-sm-8">
        {!! Form::textarea('smart_targets_details', $formData['smart_targets_details'] ?? null, [
            'class' => 'form-control',
            'placeholder' =>
                'Action/update against target. If more than 20% behind, please provide SMART goals on how you will make up the required activity.',
            'maxlength' => '5000',
        ]) !!}
        {!! $errors->first('smart_targets_details', '<p class="text-danger">:message</p>') !!}
    </div>
</div>
<div class="form-group row required {{ $errors->has('ksb_developed') ? 'has-error' : '' }}">
    {!! Form::label('ksb_developed', 'Have you developed Knowledge, skills and behaviours since last review?', [
        'class' => 'col-sm-4 control-label',
    ]) !!}
    <div class="col-sm-4 table-responsive">
        <table class="table table-bordered">
            <tr>
                <td class="center success" style="width: 30%"
                    onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
                    <p>Yes</p>
                    <input type="radio" value="Yes" name="ksb_developed" required {{ isset($formData['ksb_developed']) && $formData['ksb_developed'] == 'Yes' ? 'checked' : '' }}>
                </td>
                <td class="center warning" style="width: 30%"
                    onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
                    <p>No</p>
                    <input type="radio" value="No" name="ksb_developed" required {{ isset($formData['ksb_developed']) && $formData['ksb_developed'] == 'No' ? 'checked' : '' }}>
                </td>
            </tr>
        </table>
        {!! $errors->first('ksb_developed', '<p class="text-danger">:message</p>') !!}
    </div>
</div>
<div class="form-group row required{{ $errors->has('future_ksb_plan') ? 'has-error' : '' }}">
    {!! Form::label('future_ksb_plan', 'How will you develop these before the next review?', [
        'class' => 'col-sm-4 control-label
                                                                                no-padding-right',
    ]) !!}
    <div class="col-sm-8">
        {!! Form::textarea('future_ksb_plan', $formData['future_ksb_plan'] ?? null, ['class' => 'form-control', 'maxlength' => '5000']) !!}
        {!! $errors->first('future_ksb_plan', '<p class="text-danger">:message</p>') !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-12">
        <hr>
        <h5 class="bolder center">End Point Assessment</h5>
    </div>
</div>
<div class="form-group row required{{ $errors->has('epa_details') ? 'has-error' : '' }}">
    {!! Form::label(
        'epa_details',
        'What progress has been made towards EPA preparation since last review? What SMART targets towards EPA are set?',
        [
            'class' => 'col-sm-4 control-label
                                                                                no-padding-right',
        ],
    ) !!}
    <div class="col-sm-8">
        {!! Form::textarea('epa_details', $formData['epa_details'] ?? null, [
            'class' => 'form-control',
            'placeholder' =>
                'What progress has been made towards EPA preparation since last review? What SMART targets towards EPA are set?',
            'maxlength' => '5000',
        ]) !!}
        {!! $errors->first('epa_details', '<p class="text-danger">:message</p>') !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-12">
        <hr>
        <h5 class="bolder center">Vocational/Enhancement Workshops</h5>
    </div>
</div>
<div class="form-group row required{{ $errors->has('workshops') ? 'has-error' : '' }}">
    {!! Form::label(
        'workshops',
        'Have you attended any vocational/enhancement workshops since your last review?',
        ['class' => 'col-sm-4 control-label no-padding-right',],
    ) !!}
    <div class="col-sm-8">
        {!! Form::textarea('workshops', $formData['workshops'] ?? null, [
            'class' => 'form-control',
            'placeholder' =>
                'Provide details about any vocation/enhancement workshops you attendend since your last review. If there is any booked before next review provide details.',
            'maxlength' => '5000',
        ]) !!}
        {!! $errors->first('workshops', '<p class="text-danger">:message</p>') !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-12">
        <hr>
        <h5 class="bolder center">Wellbeing</h5>
    </div>
</div>
<div class="form-group row required{{ $errors->has('wellbeing') ? 'has-error' : '' }}">
    {!! Form::label(
        'wellbeing',
        'How well are you developing and progressing through your programme? (general wellbeing to be discussed).',
        [
            'class' => 'col-sm-4 control-label no-padding-right',
        ],
    ) !!}
    <div class="col-sm-8">
        {!! Form::textarea('wellbeing', $formData['wellbeing'] ?? null, [
            'class' => 'form-control',
            'placeholder' =>
                'How well are you developing and progressing through your programme? (general wellbeing to be discussed).',
            'maxlength' => '5000',
        ]) !!}
        {!! $errors->first('wellbeing', '<p class="text-danger">:message</p>') !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-12">
        <hr>
        <div class="center">
            <label for="rate_confidence" class="control-label">
                On a scale of one to ten how motivated and confident are you to achieve your
                programme within the timeframe set?
            </label>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td class="center"><i class="fa fa-thumbs-o-down fa-lg"></i></td>
                        @php
                            $bgColors = ['eaf2ef', 'd7e8e0', 'c3ddd8', 'afd2cf', '9bc8c7', '87bdbc', '73b3b2', '5fa9aa', '4b9fa1', '389495',];
                        @endphp
                        @foreach (range(1, 10) as $i)
                            <td class="center" style="background-color: #{{ $bgColors[$loop->index] }}"
                                onclick="cell_onclick(this, arguments.length > 0 ? arguments[0] : window.event);">
                                <p>{{ $loop->iteration }}</p>
                                <input type="radio" value="{{ $i }}" name="rate_confidence" required {{ isset($formData['rate_confidence']) && $formData['rate_confidence'] == $i ? 'checked' : '' }}>
                            </td>
                        @endforeach
                        <td class="center"><i class="fa fa-thumbs-o-up fa-lg"></i></td>
                    </tr>
                </table>
                {!! $errors->first('rate_confidence', '<p class="text-danger">:message</p>') !!}
            </div>
        </div>
    </div>
</div>
<div class="form-group row required{{ $errors->has('help_to_achieve_on_time') ? 'has-error' : '' }}">
    {!! Form::label('help_to_achieve_on_time', 'Comments on how we can help/support you to complete on time.', [
        'class' => 'col-sm-4 control-label no-padding-right',
    ]) !!}
    <div class="col-sm-8">
        {!! Form::textarea('help_to_achieve_on_time', $formData['help_to_achieve_on_time'] ?? null, [
            'class' => 'form-control',
            'placeholder' => 'Comments on how we can help/support you to complete on time.
                                                                                                    ',
            'maxlength' => '5000',
        ]) !!}
        {!! $errors->first('help_to_achieve_on_time', '<p class="text-danger">:message</p>') !!}
    </div>
</div>

<div class="form-group row">
    <div class="col-sm-12">
        <hr>
        <h5 class="bolder center">Progression Pathway</h5>
    </div>
</div>
<div class="form-group row required{{ $errors->has('short_goals') ? 'has-error' : '' }}">
    {!! Form::label(
        'short_goals',
        'Short Term Goals',
        ['class' => 'col-sm-4 control-label no-padding-right',],
    ) !!}
    <div class="col-sm-8">
        {!! Form::textarea('short_goals', $formData['short_goals'] ?? null, [
            'class' => 'form-control',
            'placeholder' =>
            'Have you got any short term goals to what you will do next?',
            'maxlength' => '5000',
        ]) !!}
        {!! $errors->first('short_goals', '<p class="text-danger">:message</p>') !!}
    </div>
</div>
<div class="form-group row required{{ $errors->has('medium_goals') ? 'has-error' : '' }}">
    {!! Form::label(
        'medium_goals',
        'Medium Term Goals',
        ['class' => 'col-sm-4 control-label no-padding-right',],
    ) !!}
    <div class="col-sm-8">
        {!! Form::textarea('medium_goals', $formData['medium_goals'] ?? null, [
            'class' => 'form-control',
            'placeholder' =>
            'Have you got any medium term goals to what you will do next? Where would you like to see yourself in 2 years time?',
            'maxlength' => '5000',
        ]) !!}
        {!! $errors->first('medium_goals', '<p class="text-danger">:message</p>') !!}
    </div>
</div>
<div class="form-group row required{{ $errors->has('long_goals') ? 'has-error' : '' }}">
    {!! Form::label(
        'long_goals',
        'Long Term Goals',
        ['class' => 'col-sm-4 control-label no-padding-right',],
    ) !!}
    <div class="col-sm-8">
        {!! Form::textarea('long_goals', $formData['long_goals'] ?? null, [
            'class' => 'form-control',
            'placeholder' =>
            'Have you got any long term goals to what you will do next? Where would you like to see yourself in 2 years time? Is there a permanent/promotional job role at your current workplace?',
            'maxlength' => '5000',
        ]) !!}
        {!! $errors->first('long_goals', '<p class="text-danger">:message</p>') !!}
    </div>
</div>
<div class="form-group row required{{ $errors->has('signposting') ? 'has-error' : '' }}">
    {!! Form::label(
        'signposting',
        'Signposting for career goals/ambitions (add links)',
        ['class' => 'col-sm-4 control-label no-padding-right',],
    ) !!}
    <div class="col-sm-8">
        {!! Form::textarea('signposting', $formData['signposting'] ?? null, [
            'class' => 'form-control',
            'maxlength' => '5000',
        ]) !!}
        {!! $errors->first('signposting', '<p class="text-danger">:message</p>') !!}
    </div>
</div>

<div class="form-group row required{{ $errors->has('safeguarding_issues') ? 'has-error' : '' }}">
    {!! Form::label(
        'safeguarding_issues',
        'Are you concerned or have experienced any safeguarding/Prevent, health and safety, equality & diversity issues since your last review?',
        [
            'class' => 'col-sm-4 control-label no-padding-right',
        ],
    ) !!}
    <div class="col-sm-8">
        {!! Form::textarea('safeguarding_issues', $formData['safeguarding_issues'] ?? null, [
            'class' => 'form-control',
            'placeholder' => 'Update/actions required. Discuss the above topics if no concerns.',
            'maxlength' => '5000',
        ]) !!}
        {!! $errors->first('safeguarding_issues', '<p class="text-danger">:message</p>') !!}
    </div>
</div>


@section('page-inline-scripts')
    <script>
        function cell_onclick(td, event) {
            var radio = td.getElementsByTagName("input")[0];
            radio.checked = true;
        }
    </script>
@endsection
