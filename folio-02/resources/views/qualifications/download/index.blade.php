@extends('layouts.master')

@section('title', 'Download Qualification')

@section('page-content')
    <div class="page-header">
        <h1>
            Download Qualification
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                download qualification from our central database
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('qualifications.index') }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box transparent ui-sortable-handle">
                        <div class="widget-header widget-header-small">
                            <h5 class="widget-title smaller">
                                Search Qualification
                            </h5>

                        </div>

                        <div class="widget-body" style="">
                            <div class="widget-main">
                                <small>
                                    {!! Form::open([
                                        'url' => route('download_qualification.index'), 
                                        'class' => 'form-horizontal', 
                                        'method' => 'GET',
                                        'role' => 'form',
                                        'name' => 'formFilters',
                                        ]) !!}

                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="keyword" class="control-label">Keyword/Number</label>
                                                <input class="form-control" maxlength="150" name="keyword" type="text"
                                                    id="keyword">
                                            </div>
                                        </div>

                                        <div class="clearfix" style="margin-top: 5px;">
                                            <button class="btn btn-sm btn-round btn-primary" type="submit">
                                                <i class="ace-icon fa fa-search bigger-110"></i>
                                                Search
                                            </button>
                                        </div>

                                    {!! Form::close() !!}
                                    <hr>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>QAN</th><th>Title</th><th>Level</th><th>Units Count</th><th>Mandatory Units Count</th><th>Optional Units Count</th><th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($qualifications as $qualification)
                                    <tr>
                                        <td>{{ $qualification->qan }}</td>
                                        <td>{{ $qualification->title }}</td>
                                        <td>{{ $qualification->level }}</td>
                                        <td>{{ $qualification->units_count }}</td>
                                        <td>{{ $qualification->mandatoryUnitsCount() }}</td>
                                        <td>{{ $qualification->optionalUnitsCount() }}</td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-info btn-white btn-round" 
                                                onclick="document.location.href='{{ route('download_qualification.show', ['download_qualification' => $qualification->id]) }}'">
                                                <i class="fa fa-folder-open"></i> View Details
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    @if (request()->has('keyword'))
                                    <tr>
                                        <td colspan="5"><i>No qualifications found matching your query in our central database.</i></td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td colspan="5"><i>Enter keyword/number to search qualification in our central databast.</i></td>
                                    </tr>
                                    @endif
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@section('page-inline-scripts')

@endsection
