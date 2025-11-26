<div class="row">
    <div class="col-xs-12">
        <div class="space"></div>
        {!! Form::model($alsReview, [
            'method' => 'PATCH',
            'url' => route('trainings.als_reviews.update', [$training, $alsReview]),
            'class' => 'form-horizontal',
            'files' => true,
            'id' => 'frmAlsReview',
        ]) !!}
        {!! Form::hidden('update_by', $formUser) !!}

        <h4 class="bolder" style="margin: 5%">To be completed by the Coach/ALS Coach/FS Tutor</h4>
        <div class="widget-main">
            <div class="form-group row required {{ $errors->has('date_of_review') ? 'has-error' : '' }}">
                {!! Form::label('date_of_review', 'Date of Review', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                <div class="col-sm-8">
                    {!! Form::date(
                        'date_of_review',
                        isset($alsReview) && !is_null($alsReview->date_of_review) ? $alsReview->date_of_review->format('Y-m-d') : null,
                        [
                            'class' => 'form-control',
                            'required',
                        ],
                    ) !!}
                    {!! $errors->first('date_of_review', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th colspan="2">
                            What reasonable adjustments have taken place this month?
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @dd(old('adjustments')) --}}
                    @foreach ($reasonableAdjustments as $adjustment)
                        <tr>
                            <td>{{ $adjustment->description }}</td>
                            <td>
                                <input class="" type="checkbox" name="adjustments[]"
                                    value="{{ $adjustment->id }}"
                                    {{ in_array((string) $adjustment->id, old('adjustments', []))
                                        ? 'checked'
                                        : (in_array((string) $adjustment->id, $selectedReasonableAdjustments)
                                            ? 'checked'
                                            : '') }}>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2">
                            <div class="form-group {{ $errors->has('adjustment_other') ? 'has-error' : '' }}"
                                style="padding: 2%">
                                <textarea name="adjustment_other" id="adjustment_other" class="form-control" rows="3" maxlength="2000"
                                    placeholder="Enter any additional not stated above">{!! nl2br(e($alsReview->reasonable_adjustments_other_assessor)) !!}</textarea>
                                {!! $errors->first('adjustment_other', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="form-group row  {{ $errors->has('date_of_sessions') ? 'has-error' : '' }}">
                <div class="col-sm-4">
                    {!! Form::label('date_of_sessions', 'Dates of sessions/preparation etc', [
                        'class' => 'control-label',
                    ]) !!}
                    <br>
                    <span class="text-info small">
                        Insert dates of T&L, contact, research, emails etc
                    </span>
                </div>
                <div class="col-sm-8">
                    {!! Form::textarea('date_of_sessions', isset($formData->date_of_sessions) ? $formData->date_of_sessions : null, [
                        'class' => 'form-control',
                        'rows' => '5',
                        'id' => 'date_of_sessions',
                        'maxlength' => 1500,
                    ]) !!}
                    {!! $errors->first('date_of_sessions', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>

            <div
                class="form-group row  {{ $errors->has('intent_of_reasonable_adjustments') ? 'has-error' : '' }}">
                <div class="col-sm-4">
                    {!! Form::label('intent_of_reasonable_adjustments', 'Intent of reasonable adjustments', [
                        'class' => 'control-label',
                    ]) !!}

                    <br>
                    <span class="text-info small">
                        How did the additional support planned this month intend to support the learner? How
                        did
                        you intend it would support progress directly associated to the apprenticeship?
                    </span>
                </div>

                <div class="col-sm-8">
                    {!! Form::textarea('intent_of_reasonable_adjustments', isset($formData->intent_of_reasonable_adjustments) ? $formData->intent_of_reasonable_adjustments :  null,[
                        'class' => 'form-control',
                        'rows' => '5',
                        'id' => 'intent_of_reasonable_adjustments',
                        'maxlength' => 5000,
                    ]) !!}
                    {!! $errors->first('intent_of_reasonable_adjustments', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>

            <div
                class="form-group row  {{ $errors->has('implementation_of_reasonable_adjustments') ? 'has-error' : '' }}">
                <div class="col-sm-4">
                    {!! Form::label('implementation_of_reasonable_adjustments', 'Implementation of reasonable adjustments  ', [
                        'class' => 'control-label',
                    ]) !!}
                    <br>
                    <span class="text-info small">
                        How did you deliver the reasonable adjustments and additional support?
                        Detail how resources were adapted, additional time in session was used, repetition
                        to
                        support retention etc

                    </span>
                </div>
                <div class="col-sm-8">
                    {!! Form::textarea('implementation_of_reasonable_adjustments', isset($formData->implementation_of_reasonable_adjustments) ? $formData->implementation_of_reasonable_adjustments :  null,[
                        'class' => 'form-control',
                        'rows' => '5',
                        'id' => 'implementation_of_reasonable_adjustments',
                        'maxlength' => 5000,
                    ]) !!}
                    {!! $errors->first('implementation_of_reasonable_adjustments', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>

            <div
                class="form-group row  {{ $errors->has('impact_of_reasonable_adjustments') ? 'has-error' : '' }}">
                <div class="col-sm-4">
                    {!! Form::label('impact_of_reasonable_adjustments', 'Impact of reasonable adjustments ', [
                        'class' => 'control-label',
                    ]) !!}
                    <br>
                    <span class="text-info small">
                        Refer to the support plan:
                        How did the reasonable adjustments support progress this month?
                        How did the additional support impact KSBs and job roles?
                        Why did the learner need the RAs?
                        Why would the learner not make sufficient progress without the RAs?
                        How will the RAs support EPA/assessments?

                    </span>
                </div>
                <div class="col-sm-8">
                    {!! Form::textarea('impact_of_reasonable_adjustments', isset($formData->impact_of_reasonable_adjustments) ? $formData->impact_of_reasonable_adjustments :  null,[
                        'class' => 'form-control',
                        'rows' => '5',
                        'id' => 'impact_of_reasonable_adjustments',
                        'maxlength' => 5000,
                    ]) !!}
                    {!! $errors->first('impact_of_reasonable_adjustments', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>

            <h4 class="bolder" style="margin: 5%">To be completed with the learner - Please give your
                answers in detail</h4>
            <div
                class="form-group row  {{ $errors->has('what_topics_you_had_support') ? 'has-error' : '' }}">
                <div class="col-sm-4">
                    {!! Form::label(
                        'what_topics_you_had_support',
                        'What topics/KSBs have you had support with this month? (both for your diploma and/or functional skills)',
                        [
                            'class' => 'control-label',
                        ],
                    ) !!}
                    <br>
                    <span class="text-info small">
                        Name the topics you have covered this month
                    </span>
                </div>
                <div class="col-sm-8">
                    {!! Form::textarea('what_topics_you_had_support', isset($formData->what_topics_you_had_support) ? $formData->what_topics_you_had_support :  null,[
                        'class' => 'form-control',
                        'rows' => '5',
                        'id' => 'what_topics_you_had_support',
                        'maxlength' => 5000,
                    ]) !!}
                    {!! $errors->first('what_topics_you_had_support', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>

            <div class="form-group row  {{ $errors->has('what_support_you_had') ? 'has-error' : '' }}">
                <div class="col-sm-4">
                    {!! Form::label('what_support_you_had', 'What support have had to help you with the above?', [
                        'class' => 'control-label',
                    ]) !!}
                    <br>
                    <span class="text-info small">
                        Has the learner received additional sessions, extra time in sessions, adapted
                        resources,
                        various delivery methods
                    </span>
                </div>
                <div class="col-sm-8">
                    {!! Form::textarea('what_support_you_had', isset($formData->what_support_you_had) ? $formData->what_support_you_had :  null,[
                        'class' => 'form-control',
                        'rows' => '5',
                        'id' => 'what_support_you_had',
                        'maxlength' => 5000,
                    ]) !!}
                    {!! $errors->first('what_support_you_had', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>

            <div
                class="form-group row  {{ $errors->has('what_do_you_feel_positive') ? 'has-error' : '' }}">
                <div class="col-sm-4">
                    {!! Form::label(
                        'what_do_you_feel_positive',
                        'What do you feel more positive and/or confident about this month?',
                        [
                            'class' => 'control-label',
                        ],
                    ) !!}
                    <br>
                    <span class="text-info small">
                        Refer to EPA/KSBs
                    </span>
                </div>
                <div class="col-sm-8">
                    {!! Form::textarea('what_do_you_feel_positive', isset($formData->what_do_you_feel_positive) ? $formData->what_do_you_feel_positive :  null,[
                        'class' => 'form-control',
                        'rows' => '5',
                        'id' => 'what_do_you_feel_positive',
                        'maxlength' => 5000,
                    ]) !!}
                    {!! $errors->first('what_do_you_feel_positive', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>

            <div class="form-group row  {{ $errors->has('how_can_use_this_at_work') ? 'has-error' : '' }}">
                <div class="col-sm-4">
                    {!! Form::label('how_can_use_this_at_work', 'How can you use this at work?', [
                        'class' => 'control-label',
                    ]) !!}

                </div>
                <div class="col-sm-8">
                    {!! Form::textarea('how_can_use_this_at_work', isset($formData->how_can_use_this_at_work) ? $formData->how_can_use_this_at_work :  null,[
                        'class' => 'form-control',
                        'rows' => '5',
                        'id' => 'how_can_use_this_at_work',
                        'maxlength' => 5000,
                    ]) !!}
                    {!! $errors->first('how_can_use_this_at_work', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>

            <div class="form-group row  {{ $errors->has('do_you_feel_confident') ? 'has-error' : '' }}">
                <div class="col-sm-4">
                    {!! Form::label(
                        'do_you_feel_confident',
                        'Do you feel confident in the topics to support EPA/exams/assessments?  Why?',
                        [
                            'class' => 'control-label',
                        ],
                    ) !!}
                    <br>
                    <span class="text-info small">
                        Does the learner feel that the support has helped them to understand KSBs/topics
                        more?
                        Does the learner feel they have processed the information well? Does the learner
                        feel
                        that they will retain the information?
                    </span>
                </div>
                <div class="col-sm-8">
                    {!! Form::textarea('do_you_feel_confident', isset($formData->do_you_feel_confident) ? $formData->do_you_feel_confident :  null,[
                        'class' => 'form-control',
                        'rows' => '5',
                        'id' => 'do_you_feel_confident',
                        'maxlength' => 5000,
                    ]) !!}
                    {!! $errors->first('do_you_feel_confident', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>

            <div class="form-group row  {{ $errors->has('anything_not_progressing') ? 'has-error' : '' }}">
                <div class="col-sm-4">
                    {!! Form::label('anything_not_progressing', 'Is there anything you don\'t feel you are progressing with? ', [
                        'class' => 'control-label',
                    ]) !!}
                    <br>
                    <span class="text-info small">
                        What areas does the learner feel they are not progressing with?
                    </span>
                </div>
                <div class="col-sm-8">
                    {!! Form::textarea('anything_not_progressing', isset($formData->anything_not_progressing) ? $formData->anything_not_progressing :  null,[
                        'class' => 'form-control',
                        'rows' => '5',
                        'id' => 'anything_not_progressing',
                        'maxlength' => 5000,
                    ]) !!}
                    {!! $errors->first('anything_not_progressing', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>

            <div class="form-group row  {{ $errors->has('making_good_progress') ? 'has-error' : '' }}">
                <div class="col-sm-4">
                    {!! Form::label('making_good_progress', 'Do you feel like you are making good progress?', [
                        'class' => 'control-label',
                    ]) !!}
                    <br>
                    <span class="text-info small">
                        What areas is the learner making good progress with?
                    </span>
                </div>
                <div class="col-sm-8">
                    {!! Form::textarea('making_good_progress', isset($formData->making_good_progress) ? $formData->making_good_progress :  null,[
                        'class' => 'form-control',
                        'rows' => '5',
                        'id' => 'making_good_progress',
                        'maxlength' => 5000,
                    ]) !!}
                    {!! $errors->first('making_good_progress', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>

            <div class="form-group row  {{ $errors->has('confident_to_achieve') ? 'has-error' : '' }}">
                <div class="col-sm-4">
                    {!! Form::label(
                        'confident_to_achieve',
                        'Do you think you will achieve your qualification(s) by your end date?',
                        [
                            'class' => 'control-label',
                        ],
                    ) !!}
                    <br>
                    <span class="text-info small">
                        If not, how can this be supported?
                    </span>
                </div>
                <div class="col-sm-8">
                    {!! Form::textarea('confident_to_achieve', isset($formData->confident_to_achieve) ? $formData->confident_to_achieve :  null,[
                        'class' => 'form-control',
                        'rows' => '5',
                        'id' => 'confident_to_achieve',
                        'maxlength' => 5000,
                    ]) !!}
                    {!! $errors->first('confident_to_achieve', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>

            <div class="form-group row  {{ $errors->has('anything_else') ? 'has-error' : '' }}">
                <div class="col-sm-4">
                    {!! Form::label('anything_else', 'Is there any more we can do to support your progress?', [
                        'class' => 'control-label',
                    ]) !!}
                </div>
                <div class="col-sm-8">
                    {!! Form::textarea('anything_else', isset($formData->anything_else) ? $formData->anything_else :  null,[
                        'class' => 'form-control',
                        'rows' => '5',
                        'id' => 'anything_else',
                        'maxlength' => 5000,
                    ]) !!}
                    {!! $errors->first('anything_else', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>

            <br>
            <div class="control-group">
                <div class="checkbox">
                    <label>
                        <input name="{{ $formUser }}_sign" type="checkbox" value="1">
                        <span class="lbl bolder"> &nbsp; Tick this option to confirm your signature if
                            the form is fully completed.</span>
                        <div class="space-2"></div>
                        <span class="text-info small" style="margin-left: 2%">
                            &nbsp; <i class="fa fa-info-circle"></i>
                            After you tick this option and save then form will be locked for further
                            changes.
                        </span>
                    </label>
                </div>
            </div>
            {!! $errors->first('{{ $formUser }}_sign', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="widget-toolbox padding-8 clearfix">
            <div class="center">
                <button class="btn btn-sm btn-success btn-round" type="submit">
                    <i class="ace-icon fa fa-save bigger-110"></i>Save Information
                </button>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
</div>
