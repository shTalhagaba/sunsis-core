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
                <button class="btn btn-sm btn-white btn-default btn-round" type="button" onclick="window.location.href='{{ route('users.index') }}'">
                    <i class="ace-icon fa fa-times bigger-110"></i> Close
                </button>
                @can('update-system-user')
                <button class="btn btn-sm btn-primary btn-bold btn-round" type="button" onclick="window.location.href='{{ route('users.edit', $user) }}'">
                    <i class="ace-icon fa fa-edit bigger-120"></i> Edit User
                </button>
                @endcan
                @can('update-system-user')
                <button class="btn btn-sm btn-primary btn-bold btn-round" type="button" onclick="window.location.href='{{ route('users.manage-user-access', $user->id) }}'">
                    <i class="ace-icon fa fa-key bigger-120"></i> Manage Access
                </button>
                @endcan
                @can('delete-system-user')
                {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user], 'style' => 'display: inline;', 'class' => 'form-inline', 'id' => 'frmDeleteUser' ]) !!}
                    {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-120"></i> Delete', ['class' => 'btn btn-sm btn-danger btn-bold btn-round', 'type' => 'submit', 'style' => 'display: inline']) !!}
                {!! Form::close() !!}
                @endcan
                <div class="hr hr-12 hr-dotted"></div>
            </div>
        </div>

        @include('partials.session_message')

        <div class="row">

            <div class="col-sm-6">
                <div class="center">
                    <img class="img-responsive img-thumbnail" width="150px;" height="150px;" alt="{{ $user->firstnames}}'s Avatar" id="avatar2" src="{{ asset($user->avatar_url) }}" />
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
                        <div class="info-div-value"><span>{{ $user->systemUserType->description }}</span></div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> Primary Email </div>
                        <div class="info-div-value"><span>{{ $user->primary_email }}</span></div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> Secondary Email </div>
                        <div class="info-div-value"><span>{{ $user->secondary_email }}</span></div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> Home Address </div>
                        <div class="info-div-value">
                            @include('partials.address_lines', ['address' => $homeAddress])
                        </div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> Work Address </div>
                        <div class="info-div-value">
                            @include('partials.address_lines', ['address' => $workAddress])
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
                            <h5 class="widget-title"><i class="fa fa-key"></i> Access & Login</h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="info-div info-div-striped">
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Username </div>
                                        <div class="info-div-value"><span>{{ $user->username }}</span></div>
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
                                            @if ($user->latestAuth)
                                            <span>
                                                {{ $user->latestAuth->login_at }} 
                                                from 
                                                {{ $user->latestAuth->ip_address }}</span>
                                            @else
                                            <i>Not yet logged in to the system</i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="space"></div>
                    @if($linkedUserAccounts->count() > 0)
                        <p class="text-info">This account is currently linked with the following accounts.</p>
                        <table class="table table-bordered">
                            @foreach($linkedUserAccounts AS $linkedUserAccount)
                            <tr>
                                <td>
                                    <i class="fa fa-user"></i> {{ $linkedUserAccount->full_name }}<br>
                                    <i class="fa fa-envelope"></i> {{ $linkedUserAccount->primary_email }}<br>
                                    {{ optional($linkedUserAccount->systemUserType)->description }}<br>
                                    <code>{{ $linkedUserAccount->username }}</code>
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    @endif

                    @if($user->user_type == App\Models\Lookups\UserTypeLookup::TYPE_EMPLOYER_USER)
                        <div class="widget-box">
                            <div class="widget-header">
                                <h5 class="widget-title"><i class="fa fa-building"></i> Employer and Location</h5>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main">
                                    <div class="info-div info-div-striped">
                                        <div class="info-div-row">
                                            <div class="info-div-name"> Employer </div>
                                            <div class="info-div-value">
                                                @can('read-employer-organisation')
                                                <a href="{{ route('employers.show', $user->employer) }}">{{ $user->employer->legal_name }}</a>
                                                @else
                                                {{ $user->employer->legal_name }}
                                                @endcan                                            
                                            </div>
                                        </div>
                                        <div class="info-div-row">
                                            <div class="info-div-name"> Location </div>
                                            <div class="info-div-value">
                                                @include('partials.address_lines', ['address' => $user->location])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($employerLinkedAssessors->count() > 0)
                            <table class="table table-bordered">
                                <caption class="text-info">
                                    This employer user account ({{ $user->full_name }}) is currently linked with the following {{ $employerLinkedAssessors->count() }} {{ Str::plural('assessor', $employerLinkedAssessors->count()) }}.
                                </caption>
                                @foreach($employerLinkedAssessors AS $employerLinkedAssessor)
                                <tr>
                                    <td>
                                        <i class="fa fa-user"></i> {{ $employerLinkedAssessor->firstnames }} {{ $employerLinkedAssessor->surname }}<br>
                                        <i class="fa fa-envelope"></i> {{ $employerLinkedAssessor->primary_email }}<br>
                                        <code> {{ $employerLinkedAssessor->username }}</code>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            @else
                            <p class="text-info">
                                <i class="fa fa-info-circle"></i> This employer user account ({{ $user->full_name }}) is currently not linked with any assessors.
                            </p>
                            <div class="space"></div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
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
                    label: '<i class="fa fa-times"></i> Cancel',
                    className: 'btn-xs btn-round'
                },
                confirm: {
                    label: '<i class="fa fa-trash"></i> Confirm',
                    className: 'btn-danger btn-xs btn-round'
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
