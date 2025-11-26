<div class="row">
    <div class="col-xs-6">
        <div class="info-div info-div-striped">
            <div class="info-div-row">
                <div class="info-div-name"> Title </div><div class="info-div-value"><span>{{ $programme->title }}</span></div>
            </div>
            <div class="info-div-row">
                <div class="info-div-name"> Programme Type </div>
                <div class="info-div-value"><span>{{ optional($programme->programmeType)->description }}</span></div>
            </div>

                <div class="info-div-row">
                    <div class="info-div-name"> Session </div><div class="info-div-value"><span>{{ $session->session_number }}</span></div>
                </div>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="info-div info-div-striped">
            <div class="info-div-row">
                <div class="info-div-name"> Duration (months)</div><div class="info-div-value"><span>{{ $programme->duration }}</span></div>
            </div>
            <div class="info-div-row">
                <div class="info-div-name"> Status </div>
                <div class="info-div-value">
                    <span>{{ $programme->status == 1 ? 'Active' : 'Not Active' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>