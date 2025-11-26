@extends('layouts.master')

@section('title', 'OTLA')

@section('page-content')
    <div class="page-header">
        <h1>OTLA</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            @if(auth()->user()->isAdmin() || auth()->user()->isVerifier())
            <button class="btn btn-sm btn-bold btn-primary btn-round" type="button"
                onclick="window.location.href='{{ route('otla.create') }}'">
                <i class="ace-icon fa fa-plus bigger-120"></i> Add New OTLA
            </button>
            @endif
            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="table-responsive">
                <table id="tblOTLA" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Coach</th>
                            <th>Standard</th>
                            <th>Created By</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($otlas AS $otla)
                            <tr onclick="window.location.href='{{ route('otla.show', $otla) }}';"
                                onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};"onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};">
                                <td>{{ $otla->coach->full_name }}</td>
                                <td>{{ $otla->programme->title }}</td>
                                <td>{{ $otla->creator->full_name }}</td>
                                <td>{{ $otla->creator->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No OTLA record found in the system.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="well well-sm">
                @include('partials.pagination', ['collection' => $otlas])
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
