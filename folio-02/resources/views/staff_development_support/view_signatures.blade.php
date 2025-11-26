<div class="row">
    <div class="col-sm-6 col-sm-offset-3">
        <div class="info-div info-div-striped">
            <div class="info-div-row">
                <div class="info-div-name" style="width: 25%"> Staff Signature </div>
                <div class="info-div-value" style="width: 25%">
                    <span>{!! $staffDevelopmentSupport->support_to_sign ? '<i class="fa fa-check fa-lg text-success green"></i>' : '' !!}</span>
                </div>
                <div class="info-div-name" style="width: 25%"> Date </div>
                <div class="info-div-value" style="width: 25%">
                    <span>{{ $staffDevelopmentSupport->support_to_sign_date != '' ? $staffDevelopmentSupport->support_to_sign_date->format('d/m/Y H:i:s') : ''  }}</span>
                </div>
            </div>                        
            <div class="info-div-row">
                <div class="info-div-name"> Support Signature </div>
                <div class="info-div-value">
                    <span>{!! $staffDevelopmentSupport->support_from_sign ? '<i class="fa fa-check fa-lg green"></i>' : '' !!}</span>
                </div>
                <div class="info-div-name"> Date </div>
                <div class="info-div-value">
                    <span>{{ $staffDevelopmentSupport->support_from_sign_date != '' ? $staffDevelopmentSupport->support_from_sign_date->format('d/m/Y H:i:s') : ''  }}</span>
                </div>
            </div>                        
        </div>
    </div>
</div>

