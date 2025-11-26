<div class="profile-user-info profile-user-info-striped">
    <div class="profile-info-row">
        <div class="profile-info-name"> Organisation Type </div>
        <div class="profile-info-value"><span>{{ $organisation->typeDescription() }}</span></div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name"> Legal Name </div>
        <div class="profile-info-value"><span>{{ $organisation->legal_name }}</span></div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name"> Trading Name </div>
        <div class="profile-info-value"><span>{{ $organisation->trading_name }}</span></div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name"> Company Number </div>
        <div class="profile-info-value"><span>{{ $organisation->company_number }}</span></div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name"> VAT Number </div>
        <div class="profile-info-value"><span>{{ $organisation->vat_number }}</span></div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name"> Sector </div>
        <div class="profile-info-value"><span>{{ $organisation->sector }}</span></div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name"> EDRS </div>
        <div class="profile-info-value"><span>{{ $organisation->edrs }}</span></div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name"> Active </div>
        <div class="profile-info-value"><span>{{ $organisation->active == 1 ? 'Yes' : 'No' }}</span>
        </div>
    </div>
</div>