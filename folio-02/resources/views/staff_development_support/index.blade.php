@extends('layouts.master')

@section('title', 'Staff Development Support')

@section('page-content')
    <div class="page-header">
        <h1>Staff Development Support</h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            @if (!\Auth::user()->isQualityManager())
                <button class="btn btn-sm btn-bold btn-primary btn-round" type="button"
                    onclick="window.location.href='{{ route('staff_development_support.create') }}'">
                    <i class="ace-icon fa fa-plus bigger-120"></i> Create New Entry
                </button>

                <div class="hr hr-12 hr-dotted"></div>
            @endif

            <div class="widget-box transparent ui-sortable-handle collapsed">
                <div class="widget-header widget-header-small">
                    <h5 class="widget-title smaller">Search Filters</h5>
                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                    </div>
                </div>
                @include('partials.filter_crumbs')
                <div class="widget-body">
                    <div class="widget-main small">
                        <small> @include('staff_development_support.filter')</small>
                    </div>
                </div>
            </div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="table-header">List of Staff Sevelopment Support</div>

            <div class="table-responsive">
                <table id="tblSupportStaff" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Staff Name</th>
                            <th>Support Provided By</th>
                            <th>Support Type</th>
                            <th>Support Date</th>
                            <th>Support Duration</th>
                            <th>Staff Signed</th>
                            <th>Support Signed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records AS $row)
                            <tr onclick="window.location.href='{{ route('staff_development_support.show', $row->id) }}';"
                                onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};"onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};">
                                <td>
                                    {{ $row->supportTo->full_name }}<br>
                                    <i class="fa fa-user"></i> {{ $row->supportTo->systemUserType->description }}
                                </td>
                                <td>
                                    {{ $row->supportFrom->full_name }}<br>
                                    <i class="fa fa-user"></i> {{ $row->supportFrom->systemUserType->description }}
                                </td>
                                <td>{{ $row->support_type }}</td>
                                <td>{{ $row->provision_date->format('d/m/Y') }}</td>
                                <td>{{ $row->duration }}</td>
                                <td class="center">
                                    {!! $row->support_to_sign ? '<i class="fa fa-check fa-lg green"></i>' : '' !!}<br>
                                    {{ $row->support_to_sign_date != '' ? $row->support_to_sign_date->format('d/m/Y') : ''  }}
                                </td>
                                <td class="center">
                                    {!! $row->support_from_sign ? '<i class="fa fa-check fa-lg green"></i>' : '' !!}<br>
                                    {{ $row->support_from_sign_date != '' ? $row->support_from_sign_date->format('d/m/Y') : ''  }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">No records found in the system.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="well well-sm">
                @include('partials.pagination', ['collection' => $records])
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@push('after-scripts')
    <script></script>
@endpush
