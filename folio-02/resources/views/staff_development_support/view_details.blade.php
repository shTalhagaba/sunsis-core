<div class="row">
    <div class="col-sm-12">
        <div class="info-div info-div-striped">
            <div class="info-div-row">
                <div class="info-div-name"> Staff Name </div>
                <div class="info-div-value" style="width: 60%">
                    {{ $staffDevelopmentSupport->supportTo->full_name }}<br>
                    <i class="fa fa-user"></i> {{ $staffDevelopmentSupport->supportTo->systemUserType->description }}
                </div>
            </div>
            <div class="info-div-row">
                <div class="info-div-name"> Support provided by </div>
                <div class="info-div-value">
                    {{ $staffDevelopmentSupport->supportFrom->full_name }}<br>
                    <i class="fa fa-user"></i> {{ $staffDevelopmentSupport->supportFrom->systemUserType->description }}
                </div>
            </div>
            <div class="info-div-row">
                <div class="info-div-name"> Support type </div>
                <div class="info-div-value">
                    {{ $staffDevelopmentSupport->supportFrom->full_name }}
                </div>
            </div>
            <div class="info-div-row">
                <div class="info-div-name"> Date </div>
                <div class="info-div-value">
                    {{ $staffDevelopmentSupport->provision_date->format('d/m/Y') }}
                </div>
            </div>
            <div class="info-div-row">
                <div class="info-div-name"> Duration </div>
                <div class="info-div-value">
                    {{ $staffDevelopmentSupport->duration }}
                </div>
            </div>
        </div>
        <div class="space-4"></div>
        <div class="info-div info-div-striped">
            <div class="info-div-row">
                <div class="info-div-name"> Describe what support/advice/training has been provided? </div>
                <div class="info-div-value" style="width: 60%">
                    {!! nl2br(e($details->q1)) !!}
                </div>
            </div>
            <div class="info-div-row">
                <div class="info-div-name"> How was this identified? </div>
                <div class="info-div-value">
                    {!! nl2br(e($details->q2)) !!}
                </div>
            </div>
            <div class="info-div-row">
                <div class="info-div-name"> If applicable describe what additional support arrangements have been agreed?  </div>
                <div class="info-div-value">
                    {!! nl2br(e($details->q3)) !!}
                </div>
            </div>
        </div>
    </div>
</div>