@if ($user->isOnline())
    <label class="badge badge-success">Online</label>
@else
    <label class="badge badge-default">Offline</label>
@endif