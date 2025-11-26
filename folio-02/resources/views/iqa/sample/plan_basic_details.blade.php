<h5 class="bolder text-primary">Sample Plan Details</h5>
<div class="info-div info-div-striped">
    <div class="info-div-row">
        <div class="info-div-name"> Title </div>
        <div class="info-div-value"><span>{{ $plan->title }}</span></div>
    </div>
    <div class="info-div-row">
        <div class="info-div-name"> Type </div>
        <div class="info-div-value"><span>{{ ucwords($plan->type) }}</span></div>
    </div>
    <div class="info-div-row">
        <div class="info-div-name"> Status </div>
        <div class="info-div-value">
            <span>{!! $plan->getStatusLabel() !!}</span>
        </div>
    </div>
    <div class="info-div-row">
        <div class="info-div-name"> Complete By </div>
        <div class="info-div-value">
            <span>{{ $plan->completed_by_date->format('d/m/Y') }}</span>
            @include('iqa.sample.remaining_days', ['plan' => $plan])
        </div>
    </div>
    <div class="info-div-row">
        <div class="info-div-name"> Verifier </div>
        <div class="info-div-value"><span>{{ $plan->verifier->full_name }}</span></div>
    </div>
    <div class="info-div-row">
        <div class="info-div-name"> Programme </div>
        <div class="info-div-value"><span>{{ $plan->programme->title }}</span>
        </div>
    </div>
    <div class="info-div-row">
        <div class="info-div-name"> Qualifications </div>
        <div class="info-div-value">
            @foreach ($plan->qualifications as $qualification)
                {{ $qualification->qan }}: {{ $qualification->title }}
                <div class="space-4"></div>
            @endforeach
        </div>
    </div>
</div>
