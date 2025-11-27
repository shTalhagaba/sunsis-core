@extends('layouts.master')

@section('title', 'Support Tickets')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />

@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('support.tickets.index') }}
@endsection

@section('page-content')
<div class="page-header">
    <h1>Tickets</h1>
</div><!-- /.page-header -->
<div class="row">

    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

      <div class="clearfix">
           <div class="pull-left tableTools-container">
              <button class="btn btn-sm btn-white btn-bold btn-primary btn-round" type="button" onclick="window.location.href='{{ route('support.tickets.create') }}'">
                <i class="ace-icon fa fa-plus bigger-120"></i> Raise a Ticket
              </button>
           </div>
      </div>

      <div class="widget-box transparent ui-sortable-handle collapsed">
          <div class="widget-header widget-header-small">
              <h5 class="widget-title smaller">Search Filters</h5>
              <div class="widget-toolbar">
                  <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down bigger-125"></i></a>
              </div>
          </div>
          <div class="widget-body">
              <div class="widget-main">
                  <small>@include('support.tickets.filter')</small>
              </div>
          </div>
      </div>

      <div class="clearfix">
           <div class="pull-right tableTools-container"></div>
      </div>
      <div class="table-header">
          List of support tickets
      </div>

      <div class="table-responsive">
       <table id="tblTickets" class="table table-striped table-bordered table-hover">
          <thead>
             <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Raised By</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Category</th>
                <th>Detail</th>
                <th>Attachments</th>
           </tr>
      </thead>
      <tbody>
        @forelse($tickets AS $ticket)
        <tr class=""
        onclick="window.location.href='{{ route('support.tickets.show', $ticket) }}';"
        onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};"
        onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};">
        <td>{{ $ticket->id }}</td>
        <td>{{ $ticket->title }}</td>
        <td>{{ $ticket->author->full_name }}</td>
        <td><span class="label label-lg label-{{ $ticket->status->color }}">{{ $ticket->status->description }}</span></td>
        <td><span class="label label-lg label-{{ $ticket->priority->color }}">{{ $ticket->priority->description }}</span></td>
        <td><span class="label label-lg label-{{ $ticket->category->color }}">{{ $ticket->category->description }}</span></td>
        <td>{{ \Str::limit($ticket->content, 100, '... | Click to view detail') }}</td>
        <td align="center">
            @foreach($ticket->media AS $mediaItem)
            @php
            $file_details = 'File Size: ' . $mediaItem->size . '<br>';
            $file_details .= '<i class=\'fa fa-clock-o\'></i> ' . \Carbon\Carbon::parse($mediaItem->updated_at)->format('d/m/Y H:i:s') . '<br>';
            @endphp
            <a href="{{ route('files.download',  $mediaItem) }}">
                <i
                data-trigger="hover"
                data-rel="popover"
                data-original-title="{{ $mediaItem->file_name }}"
                data-content="{{ $file_details }}"
                class='fa {{ \App\Models\LookupManager::getFileIcon($mediaItem->file_name) }} fa-2x'></i>
            </a> &nbsp;
            @endforeach
        </td>
   </tr>
   @empty
   <tr><td colspan="8">No support ticket found in the system.</td></tr>
   @endforelse
</tbody>
</table>
</div>

<div class="well well-sm">
   {{ $tickets->appends($_GET)->links() }}<br>
   Showing <strong>{{ ($tickets->currentpage()-1)*$tickets->perpage()+1 }}</strong>
   to <strong>{{ $tickets->currentpage()*$tickets->perpage() >
      $tickets->total() ? $tickets->total() :
      $tickets->currentpage()*$tickets->perpage() }}</strong>
      of <strong>{{ $tickets->total() }}</strong> entries
 </div>

 <!-- PAGE CONTENT ENDS -->
</div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
@endsection

@section('page-inline-scripts')

<script type="text/javascript">

$('[data-rel=popover]').popover({html:true});


</script>

@endsection

