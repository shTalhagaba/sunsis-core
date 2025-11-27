@extends('layouts.master')

@section('title', 'Review Details')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />

@endsection

@section('breadcrumbs')

@endsection

@section('page-content')
<div class="page-header">
   <h1>
      Complete Review Details
   </h1>
</div><!-- /.page-header -->
<div class="row">
   <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="well well-sm">
            <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('students.training.show', [$student, $training_record]) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
            </button>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="widget-box transparent">
                    <div class="widget-header"><h5 class="widget-title">Learner & Training Details</h5></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="info-div info-div-striped">
                                <div class="info-div-row">
                                    <div class="info-div-name"> Learner </div>
                                    <div class="info-div-value"><span>{{ $student->full_name }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Training Dates </div>
                                    <div class="info-div-value"><span>{{ $training_record->start_date }} - {{ $training_record->planned_end_date }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Training Programme </div>
                                    <div class="info-div-value"><span>{{ $training_record->programme->title }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="widget-box transparent">
                    <div class="widget-header"><h5 class="widget-title">Review Details</h5></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="info-div info-div-striped">
                                <div class="info-div-row">
                                    <div class="info-div-name"> Date and Time </div>
                                    <div class="info-div-value">
                                        {{ \Carbon\Carbon::parse($review->due_date)->format('d/m/Y') }}<br>
                                        {{ \Carbon\Carbon::parse($review->start_time)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($review->end_time)->format('H:i') }}
                                    </div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Assessor </div>
                                    <div class="info-div-value">
                                        {{ \DB::table('users')->where('id', $review->assessor)->select( \DB::raw("CONCAT(firstnames, ' ', surname) AS full_name")  )->first()->full_name ?? '' }}
                                    </div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Portfolio </div>
                                    <div class="info-div-value">
                                        {{ $review->portfolio->title ?? '' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @include('partials.session_message')

        @include('partials.session_error')

        <div id="user-profile-3" class="user-profile row">

            <div class="col-sm-12">
                <div class="widget-box widget-color-blue collapsed">
                    <div class="widget-header">
                        <h5 class="widget-title">Details - Learner</h5>
                        <div class="widget-toolbar">
                            <a href="#" data-action="collapse">
                                <i class="ace-icon fa fa-chevron-down"></i>
                            </a>
                        </div>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="info-div info-div-striped">
                                <div class="info-div-row">
                                    <div class="info-div-name"> Equality and Diversity - what have you learnt and discussed since last visit? </div>
                                    <div class="info-div-value">{{ nl2br($review_form->l_equ_div) }}</div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> What training, coaching or learning have you been involved in since last visit? </div>
                                    <div class="info-div-value">{{ nl2br($review_form->l_training) }}</div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> What are your future aspirations (Short/ long term)? </div>
                                    <div class="info-div-value">{{ nl2br($review_form->l_future_asp) }}</div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> What support are you receiving for your qualification? </div>
                                    <div class="info-div-value">{{ nl2br($review_form->l_support) }}</div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Learner Feedback </div>
                                    <div class="info-div-value">{{ nl2br($review_form->l_feedback) }}</div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Do you feel safe at work? </div>
                                    <div class="info-div-value">{!! $review_form->l_feel_safe == 'Y' ? 'Yes' : '' !!}</div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Have you had any accidents or incidents at work since our last meeting? </div>
                                    <div class="info-div-value">{!! $review_form->l_had_acc == 'Y' ? 'Yes' : '' !!}</div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Have there been any changes since our last meeting? e.g. places of work, duties, duty manager, change of address/ name </div>
                                    <div class="info-div-value">{!! $review_form->l_hav_changes == 'Y' ? 'Yes' : '' !!}</div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Do you have any health issues or other things which may affect your assessments? </div>
                                    <div class="info-div-value">{!! $review_form->l_have_health_issues == 'Y' ? 'Yes' : '' !!}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                {!! Form::model($review_form, [
                'method' => 'PATCH',
                'url' => route('students.training.reviews.save_review_form', [$student, $training_record, $review]),
                'class' => 'form-horizontal',
                'role' => 'form',
                'name' => 'frmReviewForm',
                'id' => 'frmReviewForm',
                ])
             !!}
                <div class="widget-box widget-color-green">
                    <div class="widget-header"><h5 class="widget-title">Provide Details</h5></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group row {{ $errors->has('a_record_of_comm') ? 'has-error' : ''}}">
                                        {!! Form::label('a_record_of_comm', 'Record of any communications between visits (e.g. calls, emails or text support)', ['class' => 'col-sm-12']) !!}
                                        <div class="col-sm-12">
                                            {!! Form::textarea('a_record_of_comm', null, ['class' => 'form-control inputLimiter', 'rows' => '5', 'id' => 'details', 'maxlength' => 800]) !!}
                                            {!! $errors->first('a_record_of_comm', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('a_qual_last_visit') ? 'has-error' : ''}}">
                                        {!! Form::label('a_qual_last_visit', 'Review of work to be completed since last visit', ['class' => 'col-sm-12']) !!}
                                        <div class="col-sm-12">
                                            {!! Form::textarea('a_qual_last_visit', null, ['class' => 'form-control inputLimiter', 'rows' => '5', 'id' => 'details', 'maxlength' => 800]) !!}
                                            {!! $errors->first('a_qual_last_visit', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('a_qual_today') ? 'has-error' : ''}}">
                                        {!! Form::label('a_qual_today', 'Record of assessment activities (as planned on last visit) undertaken today', ['class' => 'col-sm-12']) !!}
                                        <div class="col-sm-12">
                                            {!! Form::textarea('a_qual_today', null, ['class' => 'form-control inputLimiter', 'rows' => '5', 'id' => 'details', 'maxlength' => 800]) !!}
                                            {!! $errors->first('a_qual_today', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('a_qual_next_visit') ? 'has-error' : ''}}">
                                        {!! Form::label('a_qual_next_visit', 'Work for YOU to complete by next visit', ['class' => 'col-sm-12']) !!}
                                        <div class="col-sm-12">
                                            {!! Form::textarea('a_qual_next_visit', null, ['class' => 'form-control inputLimiter', 'rows' => '5', 'id' => 'details', 'maxlength' => 800]) !!}
                                            {!! $errors->first('a_qual_next_visit', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('a_qual_act_next_visit') ? 'has-error' : ''}}">
                                        {!! Form::label('a_qual_act_next_visit', 'Activities to take place at next visit', ['class' => 'col-sm-12']) !!}
                                        <div class="col-sm-12">
                                            {!! Form::textarea('a_qual_act_next_visit', null, ['class' => 'form-control inputLimiter', 'rows' => '5', 'id' => 'details', 'maxlength' => 800]) !!}
                                            {!! $errors->first('a_qual_act_next_visit', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('a_chngs_to_notify') ? 'has-error' : ''}}">
                                        {!! Form::label('a_chngs_to_notify', 'Changes to be notified to Admin Team. e.g. put on break, back from break, address, job role', ['class' => 'col-sm-12']) !!}
                                        <div class="col-sm-12">
                                            {!! Form::textarea('a_chngs_to_notify', null, ['class' => 'form-control inputLimiter', 'rows' => '5', 'id' => 'details', 'maxlength' => 800]) !!}
                                            {!! $errors->first('a_chngs_to_notify', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row {{ $errors->has('a_feedback') ? 'has-error' : ''}}">
                                        {!! Form::label('a_feedback', 'Assessor Feedback', ['class' => 'col-sm-12']) !!}
                                        <div class="col-sm-12">
                                            {!! Form::textarea('a_feedback', null, ['class' => 'form-control inputLimiter', 'rows' => '10', 'id' => 'details', 'maxlength' => 800]) !!}
                                            {!! $errors->first('a_feedback', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group table-responsive">
                                        <div class="widget-box widget-color-green">
                                            <div class="widget-header"><h5 class="widget-title">Duty Manager Checklist</h5></div>
                                            <div class="widget-body">
                                                <div class="widget-main">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <th>Is your apprentice</th><th>Yes / No</th>
                                                        </tr>
                                                        <tr>
                                                            <td>Punctual</td>
                                                            <td>
                                                                <label class="inline">
                                                                    <input name="m_punc" type="checkbox" class="ace ace-switch ace-switch-5" value="Y" {!! $review_form->m_punc == 'Y' ? 'checked' : '' !!}>
                                                                    <span class="lbl middle"></span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Well presented</td>
                                                            <td>
                                                                <label class="inline">
                                                                    <input name="m_well_pres" type="checkbox" class="ace ace-switch ace-switch-5" value="Y" {!! $review_form->m_well_pres == 'Y' ? 'checked' : '' !!}>
                                                                    <span class="lbl middle"></span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Respectful to others</td>
                                                            <td>
                                                                <label class="inline">
                                                                    <input name="m_respectful" type="checkbox" class="ace ace-switch ace-switch-5" value="Y" {!! $review_form->m_respectful == 'Y' ? 'checked' : '' !!}>
                                                                    <span class="lbl middle"></span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>A team player</td>
                                                            <td>
                                                                <label class="inline">
                                                                    <input name="m_team_player" type="checkbox" class="ace ace-switch ace-switch-5" value="Y" {!! $review_form->m_team_player == 'Y' ? 'checked' : '' !!}>
                                                                    <span class="lbl middle"></span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Showing personal development</td>
                                                            <td>
                                                                <label class="inline">
                                                                    <input name="m_show_pd" type="checkbox" class="ace ace-switch ace-switch-5" value="Y" {!! $review_form->m_show_pd == 'Y' ? 'checked' : '' !!}>
                                                                    <span class="lbl middle"></span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Demonstrating new skills</td>
                                                            <td>
                                                                <label class="inline">
                                                                    <input name="m_demo_skills" type="checkbox" class="ace ace-switch ace-switch-5" value="Y" {!! $review_form->m_demo_skills == 'Y' ? 'checked' : '' !!}>
                                                                    <span class="lbl middle"></span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Showing an increase in confidence</td>
                                                            <td>
                                                                <label class="inline">
                                                                    <input name="m_show_conf" type="checkbox" class="ace ace-switch ace-switch-5" value="Y" {!! $review_form->m_show_conf == 'Y' ? 'checked' : '' !!}>
                                                                    <span class="lbl middle"></span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <th>Do they have</th><th>Yes / No</th>
                                                        </tr>
                                                        <tr>
                                                            <td>Good attendance record</td>
                                                            <td>
                                                                <label class="inline">
                                                                    <input name="m_attendance" type="checkbox" class="ace ace-switch ace-switch-5" value="Y" {!! $review_form->m_attendance == 'Y' ? 'checked' : '' !!}>
                                                                    <span class="lbl middle"></span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Creativity and imagination</td>
                                                            <td>
                                                                <label class="inline">
                                                                    <input name="m_creative" type="checkbox" class="ace ace-switch ace-switch-5" value="Y" {!! $review_form->m_creative == 'Y' ? 'checked' : '' !!}>
                                                                    <span class="lbl middle"></span>
                                                                </label>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('a_sign') ? 'has-error' : ''}}">
                                        {!! Form::label('l_sign', 'Click the checkbox to confirm', ['class' => 'col-sm-4']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::checkbox('a_sign', '1') !!}
                                            {!! $errors->first('a_sign', '<p class="text-danger">:message</p>') !!}
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
                {!! Form::close() !!}
            </div><!-- /.span -->
         </div><!-- /.user-profile -->


      <!-- PAGE CONTENT ENDS -->
   </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/jquery.inputlimiter.min.js') }}"></script>

@endsection

@section('page-inline-scripts')

<script type="text/javascript">
    $(function(){
        $('.inputLimiter').inputlimiter();
    });
</script>

@endsection

