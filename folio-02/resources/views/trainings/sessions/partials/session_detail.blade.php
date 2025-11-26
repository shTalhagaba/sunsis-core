<div class="row">
    <div class="col-xs-12">
        <div class="widget-box transparent {{ isset($collapse) && $collapse ? 'collapsed' : '' }}">
            <div class="widget-header">
                <h5 class="widget-title">
                    Delivery Plan Session 
                    {{ $session->session_number }} Details
                </h5>
                <div class="widget-toolbar">
                    <div class="widget-menu">
                        <a href="#" data-action="collapse">
                            <i class="ace-icon fa fa-chevron-{{ isset($collapse) && $collapse ? 'down' : 'up' }}"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name"> Dates </div>
                            <div class="info-div-value">
                                <span class="text-info">Start/Planned Date: </span> {{ optional($session->session_start_date)->format('d/m/Y') }}<br>
                                @if(isset($sessionStartDateAudits) && $sessionStartDateAudits->isNotEmpty())
                                    <span class="text-info">Previous Start/Planned Date: </span> {{ $sessionStartDateAudits->first()->old_values['session_start_date'] }}<br>
                                @endif
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Details </div>
                            <div class="info-div-value">
                                {!! nl2br(e($session->session_details_1)) !!}<hr>
                                {!! nl2br(e($session->session_details_2)) !!}            
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Criteria&nbsp;({{ $session->ksb()->count() }}) </div>
                            <div class="info-div-value">
                                @php 
                                    echo '<ul>';
                                    $hoursTotal = 0;
                                    foreach ($session->ksb as $ksb ) 
                                    {
                                        echo '<li>' . nl2br(e($ksb->pc_title)) . ' [' . $ksb->delivery_hours . ']</li>';
                                        $hoursTotal += $ksb->delivery_hours;
                                    }
                                    echo '</ul>';
                                    echo '<p class="bolder text-info">Total Hours: ' . $hoursTotal . '</p>';
                                @endphp
                            </div>
                        </div>
                        
                        @if($session->hasAssessorSigned())
                        <div class="info-div-row">
                            <div class="info-div-name"> Actual Date </div>
                            <div class="info-div-value">
                                {{ optional($session->actual_date)->format('d/m/Y') }}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Session Start Time </div>
                            <div class="info-div-value">
                                {{ isset($session->session_start_time) ? Carbon\Carbon::parse($session->session_start_time)->format('H:i') : '' }}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Session End Time </div>
                            <div class="info-div-value">
                                {{ isset($session->session_end_time) ? Carbon\Carbon::parse($session->session_end_time)->format('H:i') : '' }}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> File/Resource... </div>
                            <div class="info-div-value">
                                @if($session->media->count() > 0)
                                <div class="col-xs-12">
                                    @include('partials.model_media_items', ['mediaFiles' => $session->media, 'model' => $session])
                                </div>
                                @endif
                                
				                @if(auth()->user()->isAdmin() || auth()->user()->isAssessor() || auth()->user()->isTutor())
                                <div class="col-xs-12">
                                    @include('partials.upload_file_form', [
                                        'associatedModel' => $session, 
                                        'sectionName' => ''
                                        ])
                                </div>
				                @endif

                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Student Signed </div>
                            <div class="info-div-value">
                                @if($session->student_sign)
                                <i class="fa fa-check text-success fa-2x"></i> <br>
                                {{ $session->student_sign_date->format('d/m/Y') }}
                                @else
                                <i class="fa fa-times text-danger fa-2x"></i>
                                @endif
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Student Comments </div>
                            <div class="info-div-value">{!! nl2br(e($session->student_comments)) !!}</div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Assessor Signed </div>
                            <div class="info-div-value">
                                @if($session->assessor_sign)
                                <i class="fa fa-check text-success fa-2x"></i> <br>
                                {{ $session->assessor_sign_date->format('d/m/Y') }}
                                @else
                                <i class="fa fa-times text-danger fa-2x"></i>
                                @endif
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Assessor Comments </div>
                            <div class="info-div-value">{!! nl2br(e($session->assessor_comments)) !!}</div>
                        </div>
                        @endif                        
                    </div>
                    
                </div>
            </div>
        </div>                
    </div>
</div>