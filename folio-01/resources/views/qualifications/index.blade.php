@extends('layouts.master')

@section('title', 'Qualifications')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />

@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('qualifications.index') }}
@endsection

@section('page-content')
<div class="page-header">
    <h1>Qualifications</h1>
</div><!-- /.page-header -->
<div class="row">

    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

      <div class="clearfix">
           <div class="pull-left tableTools-container">
              <button class="btn btn-sm btn-white btn-bold btn-primary btn-round" type="button" onclick="window.location.href='{{ route('qualifications.create') }}'">
                <i class="ace-icon fa fa-plus bigger-120"></i> Add New Qualification
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
                  <small>@include('qualifications.filter')</small>
              </div>
          </div>
      </div>

      <div class="clearfix">
           <div class="pull-right tableTools-container"></div>
      </div>
      <div class="table-header">
          List of qualifications
      </div>

      <div class="table-responsive">
       <table id="tblQualifications" class="table table-striped table-bordered table-hover">
          <thead>
             <tr>
                <th>QAN</th>
                <th>Owner</th>
                <th>Title</th>
                <th>Level</th>
                <th>SSA</th>
                <th>Dates</th>
                <th>Units</th>
           </tr>
      </thead>
      <tbody>
        @forelse($qualifications AS $q)
        <tr
        class=""
        onclick="window.location.href='{{ route('qualifications.show', $q) }}';" onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};"onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};"
        >
        <td>{{ $q->qan }}</td>
        <td title="{{ $q->owner_org_name }}">{{ $q->owner_org_acronym }}</td>
        <td>{{ $q->title }}</td>
        <td>{{ $q->level }}</td>
        <td>{{ $q->ssa }}</td>
        <td>{{ $q->regulation_start_date }}</td>
        <td align="center">{{ $q->units_count }}</td>
   </tr>
   @empty
   <tr><td colspan="7">No qualification found in the system.</td></tr>
   @endforelse
</tbody>
</table>
</div>

<div class="well well-sm">
    @include('partials.pagination', ['collection' => $qualifications])
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

   $('#include_aims').on('change', function(){
      $('input:checkbox[name="aim_validity_category\[\]"]').attr("disabled", true);
      $('input:checkbox[name="aim_validity_category\[\]"]').attr("checked", false);
      if(this.value == 'selected')
         $('input:checkbox[name="aim_validity_category\[\]"]').removeAttr("disabled");
});


</script>

@endsection

