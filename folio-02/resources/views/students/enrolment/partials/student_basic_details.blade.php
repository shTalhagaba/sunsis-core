<div>
    <span class="profile-picture">
        <img class="avatar img-responsive" src="{{ asset($student->avatar_url) }}"
            alt="{{ $student->firstnames }}" />
    </span>

    <div class="space-4"></div>

    <div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
        <div class="inline position-relative">
            <span class="white">{{ $student->firstnames }} {{ $student->surname }}</span>
        </div>
    </div>
</div>

<div class="hr hr16 dotted"></div>

<div class="profile-user-info">
    <div class="profile-info-row">
        <div class="profile-info-name">Primary Email:</div>
        <div class="profile-info-value"><span>{{ $student->primary_email }}</span></div>
    </div>
    @if ($student->secondary_email != '')
        <div class="profile-info-row">
            <div class="profile-info-name">Secondary Email:</div>
            <div class="profile-info-value"><span>{{ $student->secondary_email }}</span></div>
        </div>
    @endif
    <div class="profile-info-row">
        <div class="profile-info-name">ULN:</div>
        <div class="profile-info-value"><span>{{ $student->uln }}</span></div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name">NI:</div>
        <div class="profile-info-value"><span>{{ $student->ni }}</span></div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name">Telephone:</div>
        <div class="profile-info-value"><span>{{ $student->homeAddress()->telephone ?? '' }}</span>
        </div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name">Mobile:</div>
        <div class="profile-info-value"><span>{{ $student->homeAddress()->mobile ?? '' }}</span></div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name">Training Records Count:</div>
        <div class="profile-info-value"><span>{{ $student->training_records()->count() }}</span></div>
    </div>
</div>
