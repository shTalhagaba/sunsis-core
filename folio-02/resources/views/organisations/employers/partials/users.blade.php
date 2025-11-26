<div class="widget-box">
    <div class="widget-body">
        <div class="widget-main">
            <h5 class="bolder">
                @can('read-system-user')
                <a href="{{ route('users.show', $systemUser) }}">{{ $systemUser->full_name }}</a>
                @else
                {{ $systemUser->full_name }}
                @endcan
            </h5>
            {!! '<i class="fa fa-map-marker light-orange bigger-110"></i> ' .
                $systemUser->location->address_line_1 .
                ', ' .
                $systemUser->location->postcode !!}<br>
            {!! $systemUser->telephone != ''
                ? '<i class="fa fa-phone light-orange bigger-110"></i> ' . $systemUser->telephone . '<br>'
                : '' !!}
            {!! $systemUser->mobile != ''
                ? '<i class="fa fa-mobile light-orange bigger-110"></i> ' . $systemUser->mobile . '<br>'
                : '' !!}
            {!! $systemUser->primary_email != ''
                ? '<i class="fa fa-envelope light-orange bigger-110"></i> ' . $systemUser->primary_email . '<br>'
                : '' !!}
        </div>
        <div class="widget-toolbox padding-8 clearfix">
            <button type="button" class="btn btn-white btn-primary btn-round btn-sm"
                onclick="window.location.href='{{ route('users.edit', [$systemUser]) }}';">
                <i class="ace-icon fa fa-edit bigger-120 blue"></i><span class="bigger-110">Edit</span>
            </button>
        </div>
    </div>
</div>
