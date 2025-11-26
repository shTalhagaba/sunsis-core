<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <th>Event</th><th>Timestamp</th><th>User</th><th>IP</th><th>Change</th>
                </thead>
                <tbody>
                    @forelse ($model->audits as $audit)
                    <tr>
                        <td class="center">
                            @switch($audit->event)
                                @case('created')
                                    <span class="label label-info ">{{ $audit->event }}</span>
                                    @break
                                @case('updated')
                                    <span class="label label-warning ">{{ $audit->event }}</span>
                                    @break
                                @case('deleted')
                                    <span class="label label-danger ">{{ $audit->event }}</span>
                                    @break
                                @default
                                <span class="label label-info ">{{ $audit->event }}</span>
                            @endswitch
                        </td>
                        <td>{{ \Carbon\Carbon::parse($audit->created_at)->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $audit->user->full_name }}</td>
                        <td><span data-rel="tooltip" title="{{ $audit->user_agent }}">{{ $audit->ip_address }}</span></td>
                        <td>
                            <ul class="unstyled-list">
                                @foreach($audit->old_values AS $field => $value)
                                <li><span class="blue">{{ $field }}</span> <i>changed from </i> <span class="blue">{{ $value }}</span> <i>to</i> <span class="blue">{{ $audit->new_values[$field] }}</span></li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><i>No audit log entry.</i></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
