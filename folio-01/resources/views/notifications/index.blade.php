@extends('layouts.master')

@section('title', 'Notifications')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('notifications.index') }}
@endsection

@section('page-content')
<div class="page-header"><h1>Your Notifications</h1></div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">

        @include('partials.session_message')

        <div class="table-responsive">
            <table id="tblNotifications" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Date</th><th>Actor</th><th>Detail</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($receivedNotifications AS $notification)
                    <tr id="row{{ $notification->id }}" style="{{ $notification->checked ? '' : 'font-weight:bold' }}">
                        <td>{{ \Carbon\Carbon::parse($notification->created_at)->format('d/m/Y H:i:s') }}</td>
                        <td>{{ $notification->actor->full_name }}</td>
                        <td><small>{!! nl2br($notification->detail) !!}</small></td>
                        <td class="center">
                            {{-- {!! Form::open(['method' => 'POST', 'route' => ['notifications.check', $notification], 'style' => 'display: inline; margin: 0; padding: 0;' ]) !!}
                            {!! Form::button('<i class="ace-icon fa fa-eye green"></i>', ['class' => 'btn btn-xs btn-white btn-round', 'type' => 'submit']) !!}
                            {!! Form::close() !!} --}}
                            {{-- {!! Form::open(['method' => 'DELETE', 'route' => ['notifications.destroy', $notification], 'style' => 'display: inline; margin: 0; padding: 0;' ]) !!}
                            {!! Form::button('<i class="ace-icon fa fa-trash red"></i>', ['class' => 'btn btn-xs btn-white btn-round', 'type' => 'submit']) !!}
                            {!! Form::close() !!} --}}
                            @if(!$notification->isChecked())
                                {!! Form::button('<i class="ace-icon fa fa-eye green"></i>', ['class' => 'btn btn-xs btn-white btn-round', 'title' => 'Set this notification as read', 'type' => 'button', 'onclick' => 'readNotification(this);']) !!}
                            @endif
                            {!! Form::button('&nbsp;<i class="ace-icon fa fa-trash red"></i>&nbsp;', ['class' => 'btn btn-xs btn-white btn-round', 'title' => 'Delete', 'type' => 'button', 'onclick' => 'deleteNotification(this);']) !!}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9">No new notification.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection

@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

<script>

function deleteNotification(element)
{
    var row_id = $(element).closest('tr').attr('id');


    var url = '{{ route('notifications.destroy', ':slug') }}';
    url = url.replace(':slug', row_id.replace('row', ''));

    $.ajax({
        url: url,
        type: 'DELETE',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    }).done(function(response, textStatus) {
        if(textStatus == 'success')
        {
            $(element).closest('tr').hide();
            toastr.options.positionClass = 'toast-bottom-right';
            toastr.success(response.message);
        }
    }).fail(function(jqXHR, textStatus, errorThrown){
        $.alert({
            title: 'Encountered an error!',
            content: textStatus + ': '+ errorThrown ,
            icon: 'fa fa-warning',
            type: 'red'
        });
    });
}

function readNotification(element)
{
    var row_id = $(element).closest('tr').attr('id');

    var url = '{{ route('notifications.check', ':slug') }}';
    url = url.replace(':slug', row_id.replace('row', ''));

    $.ajax({
        url: url,
        type: 'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    }).done(function(response, textStatus) {
        if(textStatus == 'success')
        {
            $(element).closest('tr').css('font-weight', 'normal');
            $(element).hide();
        }
    }).fail(function(jqXHR, textStatus, errorThrown){
        $.alert({
            title: 'Encountered an error!',
            content: textStatus + ': '+ errorThrown ,
            icon: 'fa fa-warning',
            type: 'red'
        });
    });
}

</script>

@endsection
