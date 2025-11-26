<div>
    {!! optional($address)->address_line_1 != '' ? '<span>' . optional($address)->address_line_1 . '</span><br>' : '' !!}
    {!! optional($address)->address_line_2 != '' ? '<span>' . optional($address)->address_line_2 . '</span><br>' : '' !!}
    {!! optional($address)->address_line_3 != '' ? '<span>' . optional($address)->address_line_3 . '</span><br>' : '' !!}
    {!! optional($address)->address_line_4 != '' ? '<span>' . optional($address)->address_line_4 . '</span><br>' : '' !!}
    {!! optional($address)->postcode != '' ? '<i class="fa fa-map-marker light-orange bigger-110"></i> <span>' . optional($address)->postcode . '</span><br>' : '' !!}
    {!! optional($address)->telephone != '' ? '<i class="fa fa-phone light-orange bigger-110"></i> <span>' . optional($address)->telephone . '</span><br>' : '' !!}
    {!! optional($address)->mobile != '' ? '<i class="fa fa-mobile light-orange bigger-110"></i> <span>' . optional($address)->mobile . '</span><br>' : '' !!}
    {!! optional($address)->email != '' 
    ? '<i class="fa fa-envelope light-orange" style="font-size:80%;"></i> <span>' . e(optional($address)->email) . '</span><br>' 
    : '' 
    !!}
</div>