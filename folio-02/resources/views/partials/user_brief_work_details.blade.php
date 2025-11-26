<div>
    <span class="profile-picture">
        <img class="avatar img-responsive" width="50px;" height="50px;" src="{{ $user->avatar_url }}"
            alt="{{ $user->firstnames }}" />
    </span><br>

    <span>{{ $user->full_name }}</span><br>

    @include('partials.user_login_status', ['user' => $user])

    <br>{{ $user->primary_email }}</span><br>

    @if ($user->workAddress()->mobile != '')
        <span class="small"><i class="ace-icon fa fa-mobile blue"></i>
            {{ $user->workAddress()->mobile }}</span><br>
    @endif

    @if ($user->workAddress()->telephone != '')
        <span class="small"><i class="ace-icon fa fa-phone blue"></i>
            {{ $user->workAddress()->telephone }}</span>
    @endif
</div>
