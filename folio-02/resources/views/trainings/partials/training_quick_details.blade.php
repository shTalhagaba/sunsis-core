<div class="row">
    <div class="col-sm-6">
        <div class="widget-box transparent">
            <div class="widget-header"><h5 class="widget-title">Learner Details</h5></div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name"> Learner </div>
                            <div class="info-div-value"><span>{{ $training->student->full_name }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Email </div>
                            <div class="info-div-value">
                                <span>
                                    <i class="fa fa-envelope blue bigger-110"></i> {{ $training->student->primary_email }}
                                </span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Employer </div>
                            <div class="info-div-value"><span>{{ optional($training->employer)->legal_name }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="widget-box transparent">
            <div class="widget-header"><h5 class="widget-title">Training Details</h5></div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name"> Status </div>
                            <div class="info-div-value">
                                <small>
                                    @include('trainings.partials.tr_status_description')
                                </small>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Dates </div>
                            <div class="info-div-value">
                                <span>{{ $training->start_date->format('d/m/Y') }} - {{ $training->planned_end_date->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Programme </div>
                            <div class="info-div-value">
                                {{ $training->programme->title }} 
                                @if($showOverallPercentage)
                                <span class="label label-success">
                                    {{ $training->signedOffPercentage() }}% 
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>