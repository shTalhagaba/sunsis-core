<div class="info-div info-div-striped">
    <div class="info-div-row">
        <div class="info-div-name"> Title </div>
        <div class="info-div-value"><span>{{ $fsCourse->title }}</span></div>
    </div>
    <div class="info-div-row">
        <div class="info-div-name"> Type </div>
        <div class="info-div-value"><span>{{ $fsCourse->fs_type }}</span></div>
    </div>
    <div class="info-div-row">
        <div class="info-div-name"> Video Link </div>
        <div class="info-div-value">
            <span>{{ $fsCourse->video_link }}</span>
            @if (!is_null($fsCourse->video_link))
            <br><a class="btn btn-xs btn-info btn-round" href="{{ $fsCourse->video_link }}" target="_blank">Open Link</a>
            @endif
        </div>
    </div>
    <div class="info-div-row">
        <div class="info-div-name"> Details </div>
        <div class="info-div-value"><span> {!! nl2br(e($fsCourse->details)) !!}</span></div>
    </div>
    <div class="info-div-row">
        <div class="info-div-name"> Created By </div>
        <div class="info-div-value"><span>{{ optional($fsCourse->creator)->full_name }}</span></div>
    </div>
</div>
