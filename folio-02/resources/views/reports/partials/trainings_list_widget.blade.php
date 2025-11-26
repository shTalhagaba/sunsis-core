@php
    $trainingListId = Str::random(5);
@endphp
<div class="widget-box transparent ui-sortable-handle collapsed">
    <div class="widget-header">
        <h5 class="widget-title " >
            {{ $widgetHeader ?? 'Number of Trainings' }}
        </h5>
        <span class="label label-xlg label-primary ">{{ $trainingsList->count() }}</span>
        <div class="widget-toolbar">
            <a class="export-lnk" title="Export view to Excel" href="#" data-table="{{ $trainingListId }}" data-table-filename="{{ $exportFileName ?? $trainingListId }}"> <i
                    class="ace-icon fa fa-file-excel-o bigger-125"></i></a> &nbsp;
                    
            <a href="#" data-action="collapse"><i
                    class="ace-icon fa fa-chevron-down bigger-125"></i></a>
        </div>
    </div>
    <div class="widget-body">
        <div class="widget-main table-responsive">
            @include('reports.partials.trainings_list', [
                'tableHeader' => $tableHeader ?? 'List of trainings', 
                'trainingsList' => $trainingsList,
                'tableId' => $trainingListId
                ])
        </div>
    </div>
</div>