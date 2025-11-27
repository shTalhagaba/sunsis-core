<div class="widget-box {{ $location->is_legal_address == 1 ? 'widget-color-green' : '' }}">
    <div class="widget-header">
        <h4 class="widget-title">{{ $location->title }}</h4>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            {{ $location->address_line_1 }}
            {!! $location->address_line_2 != '' ? '<br>' . $location->address_line_2 : '<br>' !!}
            {!! $location->address_line_3 != '' ? '<br>' . $location->address_line_3 : '<br>' !!}
            {!! $location->address_line_4 != '' ? '<br>' . $location->address_line_4 : '<br>' !!}
            {!! $location->postcode != '' ? '<br><i class="fa fa-map-marker light-orange bigger-110"></i> ' . $location->postcode : '<br>' !!}
            {!! $location->telephone != '' ? '<br><i class="fa fa-phone light-orange bigger-110"></i> ' . $location->telephone : '<br>' !!}
            {!! $location->mobile != '' ? '<br><i class="fa fa-mobile light-orange bigger-110"></i> ' . $location->mobile : '<br>' !!}
            {!! $location->fax != '' ? '<br><i class="fa fa-fax light-orange bigger-110"></i> ' . $location->fax : '<br>' !!}
            <br><h4 class="pull-right bigger" title="Learners Count" data-rel="tooltip"><i class="fa fa-users green bigger-120"></i> {{ $location->students->count() }}</h4>

            <iframe
                style="background-color: #ffffff;"
		        src="https://maps.google.co.uk/maps?q={{ $location->postcode }}&amp;ie=UTF8&amp;hq=&amp;hnear={{ $location->postcode }},+United+Kingdom &amp;gl=uk&amp;t=m&amp;vpsrc=0&amp;z=14&amp;iwloc=A&amp;output=embed"
                width="100%" height="200">
            </iframe>
        </div>
        <div class="widget-toolbox padding-8 clearfix">
            <button type="button" class="btn btn-white btn-primary btn-round btn-sm" onclick="prepareLocationModalForEdit('{{ $location->id }}');">
                <i class="ace-icon fa fa-edit bigger-120 blue"></i><span class="bigger-110">Edit</span>
            </button>
        </div>
    </div>
</div>
