@extends('layouts.master')

@section('title', 'Students')

@section('breadcrumbs')
    {{ Breadcrumbs::render('students.index') }}
@endsection

@section('page-content')
    <div class="page-header">
        <h1>Students</h1>
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            @can('create-student')
            <button class="btn btn-sm btn-bold btn-primary btn-round" type="button" onclick="window.location.href='{{ route('students.create') }}'">
                <i class="ace-icon fa fa-user-plus bigger-120"></i> Add New Student
            </button>
            <div class="hr hr-12 hr-dotted"></div>
            @endcan
            @include('partials.session_message')
            <div class="widget-box transparent ui-sortable-handle collapsed">
                <div class="widget-header widget-header-small">
                    <h5 class="widget-title smaller">Search Filters</h5>
                    <div class="widget-toolbar">
                        @if (!\Auth::user()->isStudent())
                            <a title="Export view to Excel"
                                href="{{ route('students.export') }}">
                                <i class="ace-icon fa fa-file-excel-o bigger-125"></i>
                            </a> &nbsp;
                            <a href="#" data-action="collapse"><i
                                    class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                        @endif
                    </div>
                </div>
                @include('partials.filter_crumbs')
                <div class="widget-body">
                    <div class="widget-main">
                        <small> @include('students.filter')</small>
                    </div>
                </div>
            </div>

            <div class="table-header">List of students</div>

            <div class="table-responsive">
                <table id="tblUsers" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>System Access</th>
                            <th>Last Login</th>
                            <th>Training Records</th>
                            <th>ULN</th>
                            <th>NI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students AS $student)
                            <tr class="" onclick="window.location.href='{{ route('students.show', $student) }}';"
                                onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};"
                                onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};">
                                <td>
                                    {{ $student->firstnames }} {{ $student->surname }}
                                    <br><span class="ace-icon fa fa-user"></span> {{ $student->primary_email }}
                                    @if ($student->isOnline())
                                        <label class="pull-right label label-success">Online</label>
                                    @else
                                        <label class="pull-right label label-default">Offline</label>
                                    @endif
                                </td>
                                <td align="center">{!! $student->isActive() == '1'
                                    ? '<i class="fa fa-check green fa-lg"></i>'
                                    : '<i class="fa fa-remove red fa-lg"></i>' !!}</td>
                                <td>
                                    {{ optional($student->latestAuth)->login_at }}<br>
                                    {{ optional($student->latestAuth)->ip_address }}
                                </td>
                                <td class="center">{{ $student->training_records_count }}</td>
                                <td>{{ $student->uln }}</td>
                                <td>{{ $student->ni }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">No user found in the system.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="well well-sm">
                @include('partials.pagination', ['collection' => $students])
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
