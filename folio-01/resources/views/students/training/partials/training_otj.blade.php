@php
$accepted_otj_sec = \DB::table('otj')->where('tr_id', $training_record->id)->where('status', 'Accepted')->sum(\DB::raw('TIME_TO_SEC(duration)'));
$accepted_otj_sec = (is_null($accepted_otj_sec) || $accepted_otj_sec == 0) ? 1 : $accepted_otj_sec;

$awaiting_otj_sec = \DB::table('otj')->where('tr_id', $training_record->id)->where('status', 'Submitted')->sum(\DB::raw('TIME_TO_SEC(duration)'));
$awaiting_otj_sec = (is_null($awaiting_otj_sec) || $awaiting_otj_sec == 0) ? 1 : $awaiting_otj_sec;

$referred_otj_sec = \DB::table('otj')->where('tr_id', $training_record->id)->where('status', 'Referred')->sum(\DB::raw('TIME_TO_SEC(duration)'));
$referred_otj_sec = (is_null($referred_otj_sec) || $referred_otj_sec == 0) ? 1 : $referred_otj_sec;

$total_seconds = $training_record->otj_hours * 60 * 60;
$remaining = $total_seconds - $accepted_otj_sec;
$total_seconds = (is_null($total_seconds) || $total_seconds == 0) ? 1 : $total_seconds;
$accepted_otj = \App\Helpers\AppHelper::convertToHoursMins(ceil($accepted_otj_sec/60), '%02d hours %02d minutes');
$remaining_otj = \App\Helpers\AppHelper::convertToHoursMins(ceil($remaining/60), '%02d hours %02d minutes');
$awaiting_otj = \App\Helpers\AppHelper::convertToHoursMins(ceil($awaiting_otj_sec/60), '%02d hours %02d minutes');
$referred_otj = \App\Helpers\AppHelper::convertToHoursMins(ceil($referred_otj_sec/60), '%02d hours %02d minutes');

$accepted_percentage = round(($accepted_otj_sec/$total_seconds)*100);

@endphp

<div class="row">
    <div class="col-sm-12">
        <h4 class="lighter">Off-the-job Hours Details<small>
                <i class="ace-icon fa fa-angle-double-right"></i> Here you can manage your off the job hours
                entries.</small>
        </h4>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <span class="btn btn-primary btn-sm btn-round" onclick="window.location.href='{{ route('students.training.otj.create', [$student, $training_record]) }}'">
            <i class="fa fa-plus"></i> Create New OTJ Record
        </span>
    </div>
</div>

@if(!is_null($training_record->otj_hours) && $training_record->otj->count() > 0)
<div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <div class="info-div info-div-striped">
            <div class="info-div-row">
                <div class="info-div-name">Total OTJH</div>
                <div class="info-div-value">{{ $training_record->otj_hours }} hours</div>
            </div>
            <div class="info-div-row">
                <div class="info-div-name">Completed</div>
                <div class="info-div-value">{{ $accepted_otj }}</div>
            </div>
            <div class="info-div-row">
                <div class="info-div-name">Remaining</div>
                <div class="info-div-value">{{ $remaining_otj }}</div>
            </div>
            <div class="info-div-row">
                <div class="info-div-name">Awaiting Assessor Approval</div>
                <div class="info-div-value">{{ $awaiting_otj }}</div>
            </div>
            <div class="info-div-row">
                <div class="info-div-name">Referred back to learner</div>
                <div class="info-div-value">{{ $referred_otj }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        Completion
        <div class="progress">
            <div data-rel="tooltip" class="progress-bar progress-bar-success" title="Accepted"
                style="width: {{ $accepted_percentage }}%;">{{ $accepted_percentage }}%
            </div>

        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <tr>
                <th>Status</th><th>Title</th><th>Type</th><th>Date and Time</th><th>Evidence/File</th><th>Details</th>
            </tr>
            @foreach($training_record->otj AS $otj)
            <tr
                onclick="window.location.href='{{ route('students.training.otj.edit', [$student, $training_record, $otj]) }}';"
                onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};"
                onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};"
            >
                <td>
                    @if($otj->status == 'Submitted')
                    <label class="label label-primary">{{ $otj->status }}</label>
                    @elseif($otj->status == 'Accepted')
                    <label class="label label-success">{{ $otj->status }}</label>
                    @elseif($otj->status == 'Referred')
                    <label class="label label-danger">{{ $otj->status }}</label>
                    @else
                    <label class="label label-info">{{ $otj->status }}</label>
                    @endif
                </td>
                <td>
                    {{ $otj->title }}<br>
                </td>
                <td>
                    @if($otj->type != '')
                    {{ \App\Models\LookupManager::getOtjDdl($otj->type) }}
                    @endif
                </td>
                <td>
                    Date: {{ \Carbon\Carbon::parse($otj->date)->format('d/m/Y') }}<br>
                    Start Time: {{ $otj->start_time }}
                    @if(!is_null($otj->duration))
                    Duration: {{ \Carbon\Carbon::parse($otj->duration)->hour  }} hour(s) and {{ \Carbon\Carbon::parse($otj->duration)->minute  }} minute(s)
                    @endif
                </td>
                <td>
                    @if($otj->media->count() > 0)
                    <a href="{{ route('files.download',  $otj->media->first()) }}" target="_blank" style="cursor: pointer;">
                        <i class="fa fa-cloud-download"></i> {{ $otj->media->first()->file_name }}
                    </a>
                    @endif
                </td>
                <td>
                    {!! nl2br($otj->details) !!}
                </td>
            </tr>
            @endforeach
        </table>

    </div>
</div>
