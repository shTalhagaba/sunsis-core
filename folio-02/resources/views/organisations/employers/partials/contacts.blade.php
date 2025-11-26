<div class="widget-box">
    <div class="widget-body">
        <div class="widget-main">
            <h5 class="bolder">{{ $contact->title }} {{ $contact->firstnames }} {{ $contact->surname }}</h5>
            {!! $contact->job_title != '' ? $contact->job_title . '<br>' : '' !!}
            {!! $contact->department != '' ? $contact->department . '<br>' : '' !!}
            {!! '<i class="fa fa-map-marker light-orange bigger-110"></i> ' . 
                $contact->location->address_line_1 .
                ', ' .
                $contact->location->postcode !!}<br>
            {!! $contact->telephone != ''
                ? '<i class="fa fa-phone light-orange bigger-110"></i> ' . $contact->telephone . '<br>'
                : '' !!}
            {!! $contact->mobile != ''
                ? '<i class="fa fa-mobile light-orange bigger-110"></i> ' . $contact->mobile . '<br>'
                : '' !!}
            {!! $contact->email != ''
                ? '<i class="fa fa-envelope light-orange bigger-110"></i> ' . $contact->email . '<br>'
                : '' !!}
        </div>
        <div class="widget-toolbox padding-8 clearfix">
            <button type="button" class="btn btn-white btn-primary btn-round btn-sm"
                onclick="window.location.href='{{ route('organisations.contacts.edit', [$contact->organisation, $contact]) }}';">
                <i class="ace-icon fa fa-edit bigger-120 blue"></i><span class="bigger-110">Edit</span>
            </button>
        </div>
    </div>
</div>
