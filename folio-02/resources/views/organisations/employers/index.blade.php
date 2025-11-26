@extends('layouts.master')

@section('title', 'Employers')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('employers.index') }}
@endsection

@section('page-content')
<div class="page-header"><h1>Employers</h1></div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <button class="btn btn-sm btn-bold btn-primary btn-round" type="button" onclick="window.location.href='{{ route('employers.create') }}'">
            <i class="ace-icon fa fa-plus bigger-120"></i> Add New Employer
        </button>
        <div class="hr hr-12 hr-dotted"></div>
        <div class="widget-box transparent ui-sortable-handle collapsed">
            <div class="widget-header widget-header-small">
                <h5 class="widget-title smaller">Search Filters</h5>
                <div class="widget-toolbar">
                    <a title="Export view to Excel" href="{{ route('employers.export') }}">
                        <i class="ace-icon fa fa-file-excel-o bigger-125"></i>
                    </a> &nbsp;
                    <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                </div>
            </div>
            @include('partials.filter_crumbs')
            <div class="widget-body">
                <div class="widget-main small">
                    <small> @include('organisations.employers.filter')</small>
                </div>
            </div>
        </div>
        <div class="table-header">List of employers</div>
        <div class="table-responsive">
            <table id="tblEmployers" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Legal Name</th><th>Main Site</th><th>EDRS</th><th>Sector</th><th>Active</th><th>Locations</th><th>Students</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employers AS $e)
                    <tr onclick="window.location.href='{{ route('employers.show', $e->id) }}';" onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};"onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};">
                        <td>{{ $e->legal_name }}</td>
                        <td>
                            @if (isset($e->locations->first()->id))
                                {{ $e->locations->first()->address_line_1 }}
                                {!! $e->locations->first()->address_line_2 != '' ? '<br>' . $e->locations->first()->address_line_2 : '' !!}
                                {!! $e->locations->first()->address_line_3 != '' ? '<br>' . $e->locations->first()->address_line_3 : '' !!}
                                {!! $e->locations->first()->address_line_4 != '' ? '<br>' . $e->locations->first()->address_line_4 : '' !!}
                                {!! $e->locations->first()->postcode != ''
                                    ? '<br><i class="fa fa-map-marker light-orange bigger-110"></i> ' . $e->locations->first()->postcode
                                    : '' !!}
                                {!! $e->locations->first()->telephone != ''
                                    ? '<br><i class="fa fa-phone light-orange bigger-110"></i> ' . $e->locations->first()->telephone
                                    : '' !!}
                                {!! $e->locations->first()->mobile != ''
                                    ? '<br><i class="fa fa-mobile light-orange bigger-110"></i> ' . $e->locations->first()->mobile
                                    : '' !!}
                                {!! $e->locations->first()->fax != '' ? '<br><i class="fa fa-fax light-orange bigger-110"></i> ' . $e->locations->first()->fax : '' !!}
                            @endif
                        </td>
                        <td>{{ $e->edrs }}</td>
                        <td>{{ $e->sector }}</td>
                        <td class="center">{{ $e->active == '1' ? 'Yes' : 'No' }}</td>
                        <td class="center">{{ $e->locations_count }}</td>
                        <td class="center">{{ $e->students_count }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="9">No employer found in the system.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="well well-sm">
            @include('partials.pagination', ['collection' => $employers])
        </div>
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection

@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
@endsection
