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
                    <div class="widget-header"><h5 class="widget-title">Review Details</h5></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="info-div info-div-striped">
                                <div class="info-div-row">
                                    <div class="info-div-name"> Date and Time </div>
                                    <div class="info-div-value">
                                        {{ \Carbon\Carbon::parse($review->due_date)->format('d/m/Y') }} &nbsp;
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

        </div>


        @include('partials.session_message')

        @include('partials.session_error')

        <div id="user-profile-3" class="user-profile row">

            <div class="col-sm-12">
                <div class="widget-box widget-color-blue collapsed">
                    <div class="widget-header">
                        <h5 class="widget-title">Details - Assessor & Duty Manager</h5>
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
                                    <div class="info-div-name"> Record of any communications between visits (e.g. calls, emails or text support) </div>
                                    <div class="info-div-value">{{ nl2br($review_form->a_record_of_comm) }}</div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Review of work to be completed since last visit </div>
                                    <div class="info-div-value">{{ nl2br($review_form->a_qual_last_visit) }}</div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Record of assessment activities (as planned on last visit) undertaken today </div>
                                    <div class="info-div-value">{{ nl2br($review_form->a_qual_today) }}</div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Work for assessor to complete by next visit </div>
                                    <div class="info-div-value">{{ nl2br($review_form->a_qual_next_visit) }}</div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Activities to take place at next visit </div>
                                    <div class="info-div-value">{{ nl2br($review_form->a_qual_act_next_visit) }}</div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Changes to be notified to Admin Team. e.g. put on break, back from break, address, job role </div>
                                    <div class="info-div-value">{{ nl2br($review_form->a_chngs_to_notify) }}</div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Assessor Feedback </div>
                                    <div class="info-div-value">{{ nl2br($review_form->a_feedback) }}</div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Duty Manager Checklist </div>
                                    <div class="info-div-value">
                                        <div class="info-div info-div-striped">
                                            <div class="info-div-row">
                                                <div class="info-div-name"> Is your apprentice punctual</div>
                                                <div class="info-div-value">{!! $review_form->m_punc == 'Y' ? 'Yes' : '' !!}</div>
                                            </div>
                                            <div class="info-div-row">
                                                <div class="info-div-name"> Is your apprentice Well presented</div>
                                                <div class="info-div-value">{!! $review_form->m_well_pres == 'Y' ? 'Yes' : '' !!}</div>
                                            </div>
                                            <div class="info-div-row">
                                                <div class="info-div-name"> Is your apprentice Respectful to others</div>
                                                <div class="info-div-value">{!! $review_form->m_respectful == 'Y' ? 'Yes' : '' !!}</div>
                                            </div>
                                            <div class="info-div-row">
                                                <div class="info-div-name"> Is your apprentice A team player</div>
                                                <div class="info-div-value">{!! $review_form->m_team_player == 'Y' ? 'Yes' : '' !!}</div>
                                            </div>
                                            <div class="info-div-row">
                                                <div class="info-div-name"> Is your apprentice Showing personal development</div>
                                                <div class="info-div-value">{!! $review_form->m_show_pd == 'Y' ? 'Yes' : '' !!}</div>
                                            </div>
                                            <div class="info-div-row">
                                                <div class="info-div-name"> Is your apprentice Demonstrating new skills</div>
                                                <div class="info-div-value">{!! $review_form->m_demo_skills == 'Y' ? 'Yes' : '' !!}</div>
                                            </div>
                                            <div class="info-div-row">
                                                <div class="info-div-name"> Is your apprentice Showing an increase in confidence</div>
                                                <div class="info-div-value">{!! $review_form->m_show_conf == 'Y' ? 'Yes' : '' !!}</div>
                                            </div>
                                            <div class="info-div-row">
                                                <div class="info-div-name"> Is your apprentice have Good attendance record</div>
                                                <div class="info-div-value">{!! $review_form->m_attendance == 'Y' ? 'Yes' : '' !!}</div>
                                            </div>
                                            <div class="info-div-row">
                                                <div class="info-div-name"> Is your apprentice have Creativity and imagination</div>
                                                <div class="info-div-value">{!! $review_form->m_creative == 'Y' ? 'Yes' : '' !!}</div>
                                            </div>
                                        </div>
                                    </div>
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
                                    <div class="form-group row {{ $errors->has('l_equ_div') ? 'has-error' : ''}}">
                                        {!! Form::label('l_equ_div', 'Equality and Diversity - what have you learnt and discussed since last visit?', ['class' => 'col-sm-12']) !!}
                                        <div class="col-sm-12">
                                            {!! Form::textarea('l_equ_div', null, ['class' => 'form-control inputLimiter', 'rows' => '5', 'id' => 'details', 'maxlength' => 800]) !!}
                                            {!! $errors->first('l_equ_div', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('l_training') ? 'has-error' : ''}}">
                                        {!! Form::label('l_training', 'What training, coaching or learning have you been involved in since last visit?', ['class' => 'col-sm-12']) !!}
                                        <div class="col-sm-12">
                                            {!! Form::textarea('l_training', null, ['class' => 'form-control inputLimiter', 'rows' => '5', 'id' => 'details', 'maxlength' => 800]) !!}
                                            {!! $errors->first('l_training', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('l_future_asp') ? 'has-error' : ''}}">
                                        {!! Form::label('l_future_asp', 'What are your future aspirations (Short/ long term)?', ['class' => 'col-sm-12']) !!}
                                        <div class="col-sm-12">
                                            {!! Form::textarea('l_future_asp', null, ['class' => 'form-control inputLimiter', 'rows' => '5', 'id' => 'details', 'maxlength' => 800]) !!}
                                            {!! $errors->first('l_future_asp', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('l_support') ? 'has-error' : ''}}">
                                        {!! Form::label('l_support', 'What support are you receiving for your qualification?', ['class' => 'col-sm-12']) !!}
                                        <div class="col-sm-12">
                                            {!! Form::textarea('l_support', null, ['class' => 'form-control inputLimiter', 'rows' => '5', 'id' => 'details', 'maxlength' => 800]) !!}
                                            {!! $errors->first('l_support', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group row {{ $errors->has('l_feedback') ? 'has-error' : ''}}">
                                        {!! Form::label('l_feedback', 'Learner Feedback', ['class' => 'col-sm-12']) !!}
                                        <div class="col-sm-12">
                                            {!! Form::textarea('l_feedback', null, ['class' => 'form-control inputLimiter', 'rows' => '10', 'id' => 'details', 'maxlength' => 800]) !!}
                                            {!! $errors->first('l_feedback', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group table-responsive">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>Do you feel safe at work?</td>
                                                <td>
                                                    <label class="inline">
                                                        <input name="l_feel_safe" type="checkbox" class="ace ace-switch ace-switch-5" value="Y" {!! $review_form->l_feel_safe == 'Y' ? 'checked' : '' !!}>
                                                        <span class="lbl middle"></span>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Have you had any accidents or incidents at work since our last meeting?</td>
                                                <td>
                                                    <label class="inline">
                                                        <input name="l_had_acc" type="checkbox" class="ace ace-switch ace-switch-5" value="Y" {!! $review_form->l_had_acc == 'Y' ? 'checked' : '' !!}>
                                                        <span class="lbl middle"></span>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Have there been any changes since our last meeting? e.g. places of work, duties, duty manager, change of address/ name</td>
                                                <td>
                                                    <label class="inline">
                                                        <input name="l_hav_changes" type="checkbox" class="ace ace-switch ace-switch-5" value="Y" {!! $review_form->l_hav_changes == 'Y' ? 'checked' : '' !!}>
                                                        <span class="lbl middle"></span>
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Do you have any health issues or other things which may affect your assessments?</td>
                                                <td>
                                                    <label class="inline">
                                                        <input name="l_have_health_issues" type="checkbox" class="ace ace-switch ace-switch-5" value="Y" {!! $review_form->l_have_health_issues == 'Y' ? 'checked' : '' !!}>
                                                        <span class="lbl middle"></span>
                                                    </label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="form-group row {{ $errors->has('l_sign') ? 'has-error' : ''}}">
                                        {!! Form::label('l_sign', 'Click the checkbox to confirm', ['class' => 'col-sm-4']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::checkbox('l_sign', '1') !!}
                                            {!! $errors->first('l_sign', '<p class="text-danger">:message</p>') !!}
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

