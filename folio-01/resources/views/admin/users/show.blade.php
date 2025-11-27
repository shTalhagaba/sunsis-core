@extends('layouts.master')

@section('title', 'System User')

@section('breadcrumbs')
{{ Breadcrumbs::render('users.show', $user) }}
@endsection

@section('page-content')
<div class="page-header"><h1>User Detail</h1></div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

        <div class="row">
            <div class="col-sm-12">
                <div class="well well-sm">
                    <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('users.index') }}'">
                        <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
                    </button>
                    @can('edit-system-user')
                    <button class="btn btn-sm btn-white btn-primary btn-bold btn-round" type="button" onclick="window.location.href='{{ route('users.edit', $user) }}'">
                        <i class="ace-icon fa fa-edit bigger-120 blue"></i> Edit User
                    </button>
                    @endcan
                    @can('manage-access')
                    <button class="btn btn-sm btn-white btn-primary btn-bold btn-round" type="button" onclick="window.location.href='{{ route('users.manage-user-access', $user->id) }}'">
                        <i class="ace-icon fa fa-key bigger-120 blue"></i> Manage Access
                    </button>
                    @endcan
                    @can('delete-system-user')
                    {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user], 'style' => 'display: inline;', 'class' => 'form-inline', 'id' => 'frmDeleteUser' ]) !!}
                        {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-120 orange"></i> Delete', ['class' => 'btn btn-sm btn-white btn-danger btn-bold btn-round', 'type' => 'submit', 'style' => 'display: inline']) !!}
                    {!! Form::close() !!}
                    @endcan
                </div>
            </div>
        </div>

        @include('partials.session_message')

        <div class="row">

            <div class="col-sm-6">
                <div class="center">
                    <img class="img-responsive img-thumbnail" width="150px;" height="150px;" alt="{{ $user->firstnames}}'s Avatar" id="avatar2" src="{{ asset($avatar_url) }}" />
                    <div class="space-4"></div>
                    @if ($user->isOnline())
                    <label class="label label-success">Online</label>
                    @else
                    <label class="label label-default">Offline</label>
                    @endif
                    <div class="space-4"></div>
                </div>
                <div class="info-div info-div-striped">
                    <div class="info-div-row">
                        <div class="info-div-name"> Firstname(s) </div>
                        <div class="info-div-value"><span>{{ $user->firstnames }}</span></div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> Surname </div>
                        <div class="info-div-value"><span>{{ $user->surname }}</span></div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> System User Type </div>
                        <div class="info-div-value"><span>{{ $user->user_type }}</span></div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> Date of Birth </div>
                        <div class="info-div-value"><span>{{ $user->date_of_birth }}</span></div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> National Insurance </div>
                        <div class="info-div-value"><span>{{ $user->ni }}</span></div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> Primary Email </div>
                        <div class="info-div-value"><span>{{ $user->primary_email }}</span></div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> Secondary Email </div>
                        <div class="info-div-value"><span>{{ $user->secondry_email }}</span></div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> Home Address </div>
                        <div class="info-div-value">
                            {!! $home_address->address_line_1 != '' ? '<span>' .
                                $home_address->address_line_1 . '</span><br>' : '' !!}
                            {!! $home_address->address_line_2 != '' ? '<span>' .
                                $home_address->address_line_2 . '</span><br>' : '' !!}
                            {!! $home_address->address_line_3 != '' ? '<span>' .
                                $home_address->address_line_3 . '</span><br>' : '' !!}
                            {!! $home_address->address_line_4 != '' ? '<span>' .
                                $home_address->address_line_4 . '</span><br>' : '' !!}
                            {!! $home_address->postcode != '' ? '<i
                                class="fa fa-map-marker light-orange bigger-110"></i> <span>' .
                                $home_address->postcode . '</span><br>' : '' !!}
                            {!! $home_address->telephone != '' ? '<i
                                class="fa fa-phone light-orange bigger-110"></i> <span>' .
                                $home_address->telephone . '</span><br>' : '' !!}
                            {!! $home_address->mobile != '' ? '<i
                                class="fa fa-mobile light-orange bigger-110"></i> <span>' .
                                $home_address->mobile . '</span><br>' : '' !!}
                        </div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> Work Address </div>
                        <div class="info-div-value">
                            {!! $work_address->address_line_1 != '' ? '<span>' .
                                $work_address->address_line_1 . '</span><br>' : '' !!}
                            {!! $work_address->address_line_2 != '' ? '<span>' .
                                $work_address->address_line_2 . '</span><br>' : '' !!}
                            {!! $work_address->address_line_3 != '' ? '<span>' .
                                $work_address->address_line_3 . '</span><br>' : '' !!}
                            {!! $work_address->address_line_4 != '' ? '<span>' .
                                $work_address->address_line_4 . '</span><br>' : '' !!}
                            {!! $work_address->postcode != '' ? '<i
                                class="fa fa-map-marker light-orange bigger-110"></i> <span>' .
                                $work_address->postcode . '</span><br>' : '' !!}
                            {!! $work_address->telephone != '' ? '<i
                                class="fa fa-phone light-orange bigger-110"></i> <span>' .
                                $work_address->telephone . '</span><br>' : '' !!}
                            {!! $work_address->mobile != '' ? '<i
                                class="fa fa-mobile light-orange bigger-110"></i> <span>' .
                                $work_address->mobile . '</span><br>' : '' !!}
                        </div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"></div>
                        <div class="info-div-value">
                            @if($user->fb_id != '')
                            <a target="_blank" href="https://www.facebook.com/{{ $user->fb_id }}"><i
                                    class="middle ace-icon fa fa-facebook-square bigger-150 blue"></i></a>
                            @endif
                            &nbsp;&nbsp;&nbsp;
                            @if($user->twitter_handle != '')
                            <a target="_blank" href="https://twitter.com/{{ $user->twitter_handle }}"><i
                                    class="middle ace-icon fa fa-twitter-square bigger-150 light-blue"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="col-sm-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h5 class="widget-title">Access & Login</h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="info-div info-div-striped">
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Username </div>
                                        <div class="info-div-value"><span>{{ $user->email }}</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Web Access </div>
                                        <div class="info-div-value">
                                            <span>{!! $user->web_access == '1' ? '<span
                                                    class="label label-success">Enabled</span>' : '<span
                                                    class="label label-danger">Disabled</span>' !!}</span>
                                        </div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Last Login </div>
                                        <div class="info-div-value">
                                            @if ($user->authentications()->count() > 0)
                                            <span>{{ $user->lastLoginAt() }} from {{ $user->lastLoginIp() }}</span>
                                            @else
                                            <i>Not yet logged in to the system</i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.easypiechart.min.js') }}"></script>

@endsection

@section('page-inline-scripts')

<script type="text/javascript">

    $('#frmDeleteUser').submit(function(e) {
        var currentForm = this;
        e.preventDefault();
        bootbox.confirm({
            title: "Confirmation",
            message: "Are you sure you want to delete this user?",
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel'
                },
                confirm: {
                    label: '<i class="fa fa-trash"></i> Confirm',
                    className: 'btn-danger'
                }
            },
            callback: function(result) {
                if(result)
                    currentForm.submit();
            }
        });
    });

</script>

@endsection
