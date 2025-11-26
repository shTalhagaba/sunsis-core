@extends('layouts.master')

@section('title', 'Logins')

@section('page-plugin-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('logins.successful.index') }}
@endsection

@section('page-content')
    <div class="page-header">
        <h1>
            Authentication Log
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                showing all successful logins into the system
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->

            <div class="table-header">
                Summmary of successful logins into the system
            </div>
            <div class="table-responsive">
                <table id="tblAuthLog" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th style="width: 35%;">User Agent</th>
                            <th>Browser Name</th>
                            <th>Platform</th>
                            <th>Login At</th>
                            <th>Logout At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs AS $authLog)
                            @php
                                $browser = \App\Helpers\AppHelper::getBrowser($authLog->user_agent);
                            @endphp
                            <tr>
                                <td>
                                    {{ $authLog->authenticatable->surname }},
                                    {{ $authLog->authenticatable->firstnames }}<br>
                                    <span
                                        class="label label-info">{{ $authLog->authenticatable->systemUserType->description }}</span>
                                </td>
                                <td><small>{{ $browser['userAgent'] }}</small></td>
                                <td><span
                                        class="{{ $browser['icon'] }}"></span>&nbsp;{{ $browser['name'] }}&nbsp;{{ $browser['version'] }}
                                </td>
                                <td><span class="fa fa-{{ $browser['platform'] }}"></span>&nbsp;{{ $browser['platform'] }}
                                </td>
                                <td>{{ $authLog->login_at }}</td>
                                <td>{{ optional($authLog->logout_at)->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No authentication log found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="well well-sm">
                {{ $logs->appends($_GET)->links() }}<br>
                Showing <strong>{{ ($logs->currentpage() - 1) * $logs->perpage() + 1 }}</strong>
                to
                <strong>{{ $logs->currentpage() * $logs->perpage() > $logs->total()
                    ? $logs->total()
                    : $logs->currentpage() * $logs->perpage() }}</strong>
                of <strong>{{ $logs->total() }}</strong> entries
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.buttons.min.js') }}"></script>

@endsection

@section('page-inline-scripts')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.fa-map-marker').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '/getIPGeoLocationFromIPStackDotCom',
                data: {
                    ip: $(this).next().html()
                },
                success: function(data) {
                    console.log(data);
                }
            });
        });

        $(function() {
            $('#tblAuthLog').DataTable({
                "lengthChange": false,
                "paging": false,
                "info": false,
                "order": []
            });
        });
    </script>
@endsection
