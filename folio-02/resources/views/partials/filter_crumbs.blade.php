@push('after-styles')
<style>
    .filterCrumbs
    {
        cursor:pointer;
        line-height: 1.8;
    }
    
    .filterCrumb
    {
        background-color:#F5F5F5;
        border-width: 1px 1px 1px 3px;
        border-style:solid;
        border-color: #FF8500;
        font-size:8pt;
        color: #03503B;
        margin: 0px 1px 0px 1px;
        padding: 1px;
        cursor: pointer;
    }
    
    .filterCrumb:hover
    {
        background-color: #FFD9B5;
        color: black;
    }
</style>    
@endpush

<div class="widget-header widget-header-small">
    <div class="filterCrumbs" 
        onclick="$(this).closest('div.widget-box').widget_box('toggle');" 
        title="Click to show/hide filters">
    </div>
</div>

@push('after-scripts')
<script>
    var filtersHtml = '';
    $(function(){
        $("form[name=formFilters] label").each(function(){
            var lblFor = $(this).attr('for'); 
            var lblDesc = $(this).html(); 
            var formControl = $("form[name=formFilters] [name=" + lblFor + "]");
            
            if(formControl.val() != '')
            {
                if( formControl.prop('tagName') === 'INPUT' )
                {
                    filtersHtml += '<span class="filterCrumb">' + lblDesc + ': ' + formControl.val() + '</span>';
                }
                else if( formControl.prop('tagName') === 'SELECT' )
                {
                    filtersHtml += '<span class="filterCrumb">' + lblDesc + ': ' + formControl.find(":selected").text() + '</span>';
                }
            }
        });
        $("div.filterCrumbs").html(filtersHtml);
    });
</script>
@endpush