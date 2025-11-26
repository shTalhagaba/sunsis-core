@if (auth()->user()->isAdmin() || auth()->user()->isTutor() || 
        in_array(auth()->user()->id, [$training->primaryAssessor->id, optional($training->secondaryAssessor)->id, $training->tutor]))
    <button type="button" class="btn btn-primary btn-sm btn-round"
            onclick="window.location.href='{{ route('trainings.sessions.create', $training) }}'">
        <i class="fa fa-edit"></i> Add Session
    </button>

    <span class="btn btn-primary btn-sm btn-round"
          @if ($training->programme->sessions->count() > 0)
              onclick="refreshDeliveryPlanSessionsFromProgramme();"
          @else
              disabled
          title="No sessions found in the programme. Please add sessions to the programme first."
        @endif 
        >
        <i class="fa fa-refresh"></i> Refresh from Programme
    </span>
    <div class="hr hr-12 hr-dotted"></div>
@endif

@if ($training->sessions->count() > 0)
    <h4 class="bigger blue text-center">{{ $training->sessions->count() }}
        {{ \Str::plural('Session', $training->sessions->count()) }}</h4>
@endif

@foreach($training->sessions as $index => $session)
    <div class="widget-box transparent {{ $index === 0 ? '' : 'collapsed' }}" id="widget-box-session-{{ $session->id }}">
        <div class="widget-header">
            <h4 class="widget-title smaller">
                Session# {{ $session->session_number }},

                @if($session->session_start_date)
                    Plan Date: {{ optional($session->session_start_date)->format('M Y') }},
                @endif
                
                @if($session->actual_date)
                    Actual Date: {{ optional($session->actual_date)->format('d M, Y') }}
                @endif
            
                {{-- Learner Sign Status --}}
                @if ($session->hasLearnerSigned())
                    <span class="label label-success arrowed-in arrowed-in-right">Signed by Learner</span>
                @else
                    <span class="label label-warning">Pending - Learner Sign</span>
                @endif

                {{-- Assessor Sign Status --}}
                @if ($session->hasAssessorSigned())
                    <span class="label label-success arrowed-in arrowed-in-right">
                        Signed by Assessor {{ ucwords(str_replace('_', ' ', $session->assessor_type)) }}
                    </span>
                @else
                    <span class="label label-warning">Pending - Assessor Sign</span>
                @endif
            </h4>
            <div class="widget-toolbar">
                <a href="#" data-action="collapse">
                    <i class="ace-icon fa {{ $index === 0 ? 'fa-chevron-up' : 'fa-chevron-down' }}"></i>
                </a>
            </div>
        </div>

    <div class="widget-body" style="display: none;">
        <div class="widget-main">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td align="center">
                            <span class="btn btn-xs btn-primary btn-round btn-white"
                                onclick="window.location.href='{{ route('trainings.sessions.show', [$training, $session]) }}'">
                                <i class="fa fa-folder-open"></i> View Details 
                                @if(auth()->user()->isAssessor() && $session->tasks()->submitted()->count() > 0)
                                    <span class="badge badge-warning">{{ $session->tasks()->submitted()->count() }}</span>
                                @endif
                            </span>
                        </td>
                        <td style="width: 40%;">
                            <p class="text-bold text-center">{{ $session->session_number }}</p>
                            <hr>
                            {!! nl2br(e($session->session_details_1)) !!}
                            <hr>
                            {!! nl2br(e($session->session_details_2)) !!}
                        </td>
                        <td style="width: 40%;">
                            <ul>
                                @php  $hoursTotal = 0; @endphp
                                @foreach($session->ksb as $ksb)
                                    @php
                                        $hoursTotal += $ksb->delivery_hours;
                                        $reference = $ksb->pc->reference ?? '';
                                    @endphp
                                    <li>
                                        {{ $reference ? '['.$reference.']' : '' }}
                                        {!! nl2br(e($ksb->pc_title)) !!} [{{ $ksb->delivery_hours }}]
                                    </li>
                                @endforeach
                            </ul>
                            <p class="bolder text-info">Total Hours: {{ $hoursTotal }}</p>
                        </td>
                        <td>
                            <span class="text-info">Total Tasks: </span>
                            {{  $session->tasks->count() }}
                            <div class="space-6"></div>
                            <span class="text-warning orange">Pending Tasks: </span>
                            {{  $session->tasks()->pending()->count() }}
                            <div class="space-6"></div>
                            <span class="text-success">Completed Tasks: </span>
                            {{  $session->tasks()->completed()->count() }}
                            <div class="space-6"></div>
                            @if($session->session_type)
                                <span class="text-info">Visit Type: </span>
                                {{ str_replace(' ', '-', ucwords(str_replace('_', ' ', $session->session_type))) }}
                                <div class="space-6"></div>
                            @endif
                            <span class="text-info">Plan Month: </span>
                            {{ optional($session->session_start_date)->format('M Y') }}
                            <div class="space-6"></div>
                            <span class="text-info">Actual Date: </span>
                            {{ optional($session->actual_date)->format('d M, Y') }}
                            <div class="space-6"></div>
                            @if ($session->revised_date)
                                <span class="text-info">Revised Date: </span>
                                {{ optional($session->revised_date)->format('d M, Y') }}

                            @endif

                            <div class="space-6"></div>
                            @if ($session->hasLearnerSigned())
                                <span class="label label-success arrowed-in arrowed-in-right">Signed by Learner</span>
                            @else
                                <span class="label label-warning">Pending - Learner Sign</span>
                            @endif
                            <div class="space-6"></div>
                            @if ($session->hasAssessorSigned())
                                <span class="label label-success arrowed-in arrowed-in-right">Signed by Assessor {{ auth()->user()->assessor_type }}</span>
                            @else
                                <span class="label label-warning">Pending - Assessor Sign</span>
                            @endif

                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endforeach
