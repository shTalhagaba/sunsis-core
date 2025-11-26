
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
        @if( 
            (auth()->user()->isStudent() && $training->isEditableByStudent()) || 
            (auth()->user()->isAdmin()) ||
            (in_array(auth()->user()->id, [$training->primary_assessor, $training->secondary_assessor]))
        )
        <span class="btn btn-primary btn-sm btn-round"
            onclick="window.location.href='{{ route('trainings.otj.create', $training) }}'">
            <i class="fa fa-plus"></i> Create New OTJ Record
        </span>
        @endif 
        <div class="hr hr-12 hr-dotted"></div>
    </div>
</div>

@if (!is_null($training->otj_hours) && $training->otj->count() > 0)
    <div class="row">
        <div class="col-sm-12">
            @include('trainings.partials.otj_progress_bar', ['otjHours' => $training->otj_hours])
        </div>
    </div>
@endif

<div class="row"> 
    <div class="col-sm-12">
                <div class="widget-box transparent ui-sortable-handle">
                    <div class="widget-header widget-header-small">
                        <h5 class="widget-title smaller">Search Filters</h5>
                        <div class="widget-toolbar">
                            <a title="Export view to Excel" href="{{ route('reports.otjh.export', $training) }}">
                                <i class="ace-icon fa fa-file-excel-o bigger-125"></i>
                            </a> &nbsp;
                            <a href="#" data-action="collapse"><i
                                    class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                        </div>
                    </div>
                    @include('partials.filter_crumbs')
                    <div class="widget-body">
                        <div class="widget-main">
                            <small> @include('trainings.otj_filter', ['otj_filters' => $otj_filters])</small>
                        </div>
                    </div>
                </div>

        @if ( $filteredOtj->count() > 0 )
            <h4 class="bigger blue text-center">{{ $filteredOtj->count() }}
                {{ \Str::plural('OTJH Entry', $filteredOtj->count()) }}</h4>
        @endif
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th>Status</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th style="width: 15%">Date and Time</th>
                    <th>Details</th>
                </tr>
                @foreach ($filteredOtj as $otj)
                    <tr onclick="window.location.href='{{ route('trainings.otj.show', [$training, $otj]) }}';"
                        onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};"
                        onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};"
                        class="{!! $otj->is_otj == 1 ? 'text-success' : 'text-info' !!}"
                        >
                        <td>
                            @if ($otj->status == 'Submitted')
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
                            @if ($otj->type != '')
                                {{ \App\Models\LookupManager::getOtjDdl($otj->type) }}
                            @endif
                        </td>
                        <td>
                            Date: {{ \Carbon\Carbon::parse($otj->date)->format('d/m/Y') }}<br>
                            Start Time: {{ Carbon\Carbon::parse($otj->start_time)->format('H:i') }}
                            @if (!is_null($otj->duration))
                                <br>Duration: {{ App\Helpers\AppHelper::formatMysqlTimeToHoursAndMinutes($otj->duration) }}
                            @endif
                        </td>
                        <td>
                            {!! nl2br(e($otj->details)) !!}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
