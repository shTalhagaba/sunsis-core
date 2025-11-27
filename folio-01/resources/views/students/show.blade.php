@extends('layouts.master')

@section('title', 'Student')

@section('page-plugin-styles')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection



@section('page-content')
<div class="page-header"><h1>Student Detail</h1></div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
            <div class="col-sm-12">
                <div class="well well-sm">
		    @can('menu-students')	
                    <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('students.index') }}'">
                        <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
                    </button>
		    @endcan
                    @can('edit-student')
                    <button class="btn btn-sm btn-white btn-primary btn-bold btn-round" type="button" onclick="window.location.href='{{ route('students.edit', $student) }}'">
                        <i class="ace-icon fa fa-edit bigger-120 blue"></i> Edit Student
                    </button>
                    @endcan
                    @can('manage-access')
                    <button class="btn btn-sm btn-white btn-primary btn-bold btn-round" type="button" onclick="window.location.href='{{ route('students.manage-access', $student->id) }}'">
                        <i class="ace-icon fa fa-key bigger-120 blue"></i> Manage Access
                    </button>
                    @endcan
                    @can('enrol-students')
                    <button class="btn btn-sm btn-white btn-primary btn-bold btn-round" type="button" onclick="enrolLearner();">
                        <i class="ace-icon fa fa-graduation-cap bigger-120 blue"></i> Enrol
                    </button>
                    @endcan
                    @can('delete-student')
                    {!! Form::open(['method' => 'DELETE', 'url' => route('students.destroy', [$student]), 'style' => 'display: inline;', 'class' => 'form-inline', 'id' => 'frmDeleteStudent' ]) !!}
                        {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-120 orange"></i> Delete', ['class' => 'btn btn-sm btn-white btn-danger btn-bold btn-round btnDeleteStudent', 'type' => 'submit', 'style' => 'display: inline']) !!}
                    {!! Form::close() !!}
                    @endcan
                </div>
            </div>
        </div>

        @include('partials.session_message')

        <div class="row">

            <div class="col-sm-4">
                <div class="center">
                    <img class="img-responsive img-thumbnail" width="150px;" height="150px;" alt="{{ $student->firstnames}}'s Avatar" id="avatar2" src="{{ asset($avatar_url) }}" />
                    <div class="space-4"></div>
                    @if ($student->isOnline())
                    <label class="label label-success">Online</label>
                    @else
                    <label class="label label-default">Offline</label>
                    @endif
                    <div class="space-4"></div>
                </div>
                <div class="info-div info-div-striped">
                    <div class="info-div-row">
                        <div class="info-div-name"> Firstname(s) </div>
                        <div class="info-div-value"><span>{{ $student->firstnames }}</span></div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> Surname </div>
                        <div class="info-div-value"><span>{{ $student->surname }}</span></div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> Gender </div>
                        <div class="info-div-value"><span>{{ $student->gender }}</span></div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> Date of Birth </div>
                        <div class="info-div-value"><span>{{ $student->date_of_birth }}</span></div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> Ethnicity </div>
                        <div class="info-div-value"><span>{{ $student->ethnicity }}</span></div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> National Insurance </div>
                        <div class="info-div-value"><span>{{ $student->ni }}</span></div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> ULN </div>
                        <div class="info-div-value"><span>{{ $student->uln }}</span></div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> Primary Email </div>
                        <div class="info-div-value"><span>{{ $student->primary_email }}</span></div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> Secondary Email </div>
                        <div class="info-div-value"><span>{{ $student->secondry_email }}</span></div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> Employer </div>
                        <div class="info-div-value"><span>{{ $student->employer->legal_name }}</span>
                        </div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> Home Address </div>
                        <div class="info-div-value">
                            {!! $home_address->address_line_1 != '' ? '<span>' .
                                $home_address->address_line_1 . '</span><br>' : '' !!}
                            {!! $home_address->address_line_2 != '' ? '<span>' .
                                $home_address->address_line_2 . '</span><br>' : '' !!}
                            {!! $home_address->address_line_3 != '' ? '<span>' .
                                $home_address->address_line_3 . '</span><br>' : '' !!}
                            {!! $home_address->address_line_4 != '' ? '<span>' .
                                $home_address->address_line_4 . '</span><br>' : '' !!}
                            {!! $home_address->postcode != '' ? '<i
                                class="fa fa-map-marker light-orange bigger-110"></i> <span>' .
                                $home_address->postcode . '</span><br>' : '' !!}
                            {!! $home_address->telephone != '' ? '<i
                                class="fa fa-phone light-orange bigger-110"></i> <span>' .
                                $home_address->telephone . '</span><br>' : '' !!}
                            {!! $home_address->mobile != '' ? '<i
                                class="fa fa-mobile light-orange bigger-110"></i> <span>' .
                                $home_address->mobile . '</span><br>' : '' !!}
                        </div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> Work Address </div>
                        <div class="info-div-value">
                            {!! $work_address->address_line_1 != '' ? '<span>' .
                                $work_address->address_line_1 . '</span><br>' : '' !!}
                            {!! $work_address->address_line_2 != '' ? '<span>' .
                                $work_address->address_line_2 . '</span><br>' : '' !!}
                            {!! $work_address->address_line_3 != '' ? '<span>' .
                                $work_address->address_line_3 . '</span><br>' : '' !!}
                            {!! $work_address->address_line_4 != '' ? '<span>' .
                                $work_address->address_line_4 . '</span><br>' : '' !!}
                            {!! $work_address->postcode != '' ? '<i
                                class="fa fa-map-marker light-orange bigger-110"></i> <span>' .
                                $work_address->postcode . '</span><br>' : '' !!}
                            {!! $work_address->telephone != '' ? '<i
                                class="fa fa-phone light-orange bigger-110"></i> <span>' .
                                $work_address->telephone . '</span><br>' : '' !!}
                            {!! $work_address->mobile != '' ? '<i
                                class="fa fa-mobile light-orange bigger-110"></i> <span>' .
                                $work_address->mobile . '</span><br>' : '' !!}
                        </div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"></div>
                        <div class="info-div-value">
                            @if($student->fb_id != '')
                            <a target="_blank" href="https://www.facebook.com/{{ $student->fb_id }}"><i
                                    class="middle ace-icon fa fa-facebook-square bigger-150 blue"></i></a>
                            @endif
                            &nbsp;&nbsp;&nbsp;
                            @if($student->twitter_handle != '')
                            <a target="_blank" href="https://twitter.com/{{ $student->twitter_handle }}"><i
                                    class="middle ace-icon fa fa-twitter-square bigger-150 light-blue"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-8">
                <div class="col-sm-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h5 class="widget-title">Access & Login</h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="info-div info-div-striped">
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Username </div>
                                        <div class="info-div-value"><span>{{ $student->email }}</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Web Access </div>
                                        <div class="info-div-value">
                                            <span>{!! $student->web_access == '1' ? '<span
                                                    class="label label-success">Enabled</span>' : '<span
                                                    class="label label-danger">Disabled</span>' !!}</span>
                                        </div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Last Login </div>
                                        <div class="info-div-value">
                                            @if ($student->authentications()->count() > 0)
                                            <span>{{ $student->lastLoginAt() }} from {{ $student->lastLoginIp() }}</span>
                                            @else
                                            <i>Not yet logged in to the system</i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="widget-box widget-color-green" id="widget-box-trs">
                        <div class="widget-header">
                            <h5 class="widget-title">Training Records <span class="badge badge-info">{{ $student->training_records->count() }}</span></h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                @foreach($trs AS $training_record)
                                <div class="table-responsive">
                                    <div class="widget-box" id="widget-box-tr">
                                        <div class="widget-header">
                                            <h5 class="widget-title smaller">
                                                {{ $training_record->system_ref }} | {{ $training_record->start_date }} - {{ $training_record->planned_end_date }} |
                                                <span class="label label-sm label-info arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span>
                                            </h5>
                                            <div class="widget-toolbar">
                                                <a href="{{ route('students.training.show', [$student, $training_record]) }}"title="Open this training record">
                                                    <i class="ace-icon fa fa-folder-open fa-lg"></i>
                                                </a>
                                                <span class="label label-success"> {{ $training_record->signedOffPercentage() }}% </span>
                                                <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-up"></i></a>
                                            </div>
                                        </div>

                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <strong>Employer:</strong>
                                                        @php $loc = $training_record->location @endphp
                                                        {{ $training_record->employer->legal_name }} |
                                                        {!! $loc->postcode != '' ? '<i class="fa fa-map-marker light-orange bigger-110"></i>
                                                        <span>' . $loc->postcode . '</span><br>' : '' !!}
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-bordered table-hover">
                                                                <thead>
                                                                    <tr><th>Learning Aim</th><th>Progress</th></tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($training_record->portfolios AS $portfolio)
                                                                    <tr>
                                                                        <td>
                                                                            {{ $portfolio->qan }}<br>{{ $portfolio->title }}<br>
                                                                            @include('students.training.partials.entity_progress_bar', ['entity' => $portfolio])
                                                                        </td>
                                                                        <td>
                                                                            <div class="easy-pie-chart percentage"
                                                                                data-percent="{{ $portfolio->signedOffPCsPercentage() }}" data-color="#CA5952">
                                                                                <span class="percent">{{ $portfolio->signedOffPCsPercentage() }}</span>%
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div> {{-- table-responsive --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.easypiechart.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')

<script type="text/javascript">
    $('.easy-pie-chart.percentage').each(function(){
    var barColor = '#50C878';
    var trackColor = '#E2E2E2';
    var size = parseInt($(this).data('size')) || 72;
    $(this).easyPieChart({
      barColor: barColor,
      trackColor: trackColor,
      scaleColor: false,
      lineCap: 'butt',
      lineWidth: parseInt(size/10),
      animate:false,
      size: size
    }).css('color', barColor);
  });

  $( ".btnDeleteStudent" ).on('click', function(e) {
    e.preventDefault();
    alert("This action is currently unavailble for you, please contact Perspective (UK) Ltd.");
    return;
    var form = this.closest('form');

    $.confirm({
        title: 'Confirm!',
        content: 'This action is irreversible, are you sure you want to continue?',
        icon: 'fa fa-question-circle',
        animation: 'scale',
        closeAnimation: 'scale',
        theme: 'supervan',
        opacity: 0.5,
        buttons: {
            'confirm': {
                text: 'Yes',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize()
                    }).done(function(response, textStatus) {
                        console.log(response);
                        console.log(textStatus);
                        $.alert({
                            title: (textStatus == 'success' && response.success)  ? 'Success' : 'Error',
                            content: response.message,
                            type: (textStatus == 'success' && response.success)  ? 'green' : 'red',
                            icon: (textStatus == 'success' && response.success)  ? 'fa fa-check' : 'fa fa-warning',
                            buttons: {
                                'OK': {
                                    action: function(){
                                        if(textStatus == 'success' && response.success)
                                            window.location.href = '{{ route("students.index") }}';
                                        else
                                            window.location.reload();
                                    }
                                }
                            }
                        });
                    }).fail(function(jqXHR, textStatus, errorThrown){
                        $.alert({
                            title: 'Encountered an error!',
                            content: textStatus + ': '+ errorThrown ,
                            icon: 'fa fa-warning',
                            theme: 'supervan',
                            type: 'red'
                        });
                    });
                }
            },
            cancel: function () {
            }
        }
    });
});


function enrolLearner()
{
    @php
    if(!$allow_enrol){
        echo "return alert('You have exceeded the number of licenses purchased, please contact Perspective (UK) Ltd. to enrol more learners.');";
    }
    else {
        echo "window.location.href='" . route('students.singleEnrolment.step1.show', $student->id) . "';";
    }
    @endphp

}

</script>

@endsection
