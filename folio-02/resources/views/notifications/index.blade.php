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
    <div class="page-header">
        <h1>Your Notifications</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">

            <div class="widget-box transparent ui-sortable-handle collapsed">
                <div class="widget-header widget-header-small">
                    <h5 class="widget-title smaller">Search Filters</h5>
                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                    </div>
                </div>
                @include('partials.filter_crumbs')
                <div class="widget-body">
                    <div class="widget-main small">
                        <small> @include('notifications.filter')</small>
                    </div>
                </div>
            </div>

            @include('partials.session_message')

            @include('partials.session_error')

            @forelse($notifications AS $notification)
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="widget-box {{ $notification->read() ? '' : 'widget-color-blue' }}" id="row{{ $notification->id }}">
                        <div class="widget-header">
                            <h5 class="widget-title">
                                {{ isset($notification->data['title']) ? $notification->data['title'] : '' }} &nbsp;  
                                <i class="fa fa-clock-o"></i> {{ $notification->created_at->format('d/m/Y H:i:s') }}
                            </h5>
                            <div class="widget-toolbar">
                                @if (!$notification->read())
                                    {!! Form::button('<i class="fa fa-eye green"></i>', [
                                        'class' => 'btn btn-xs btn-white btn-round',
                                        'title' => 'Mark this notification as read',
                                        'type' => 'button',
                                        'onclick' => 'readNotification(this);',
                                    ]) !!}
                                @endif
                                {!! Form::button('&nbsp;<i class="fa fa-trash red"></i>&nbsp;', [
                                    'class' => 'btn btn-xs btn-white btn-round',
                                    'title' => 'Delete',
                                    'type' => 'button',
                                    'onclick' => 'deleteNotification(this);',
                                    'style' => 'display: inline',
                                ]) !!}
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                {!! isset($notification->data['message']) ? $notification->data['message'] : '' !!}
                            </div>                    
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> No records found.
            </div>
            @endforelse

            <div class="well well-sm">
                @include('partials.pagination', ['collection' => $notifications])
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <script>
        function deleteNotification(element) {
            var row_id = $(element).closest('div.widget-box').attr('id');

            var url = '{{ route('notifications.destroy', ':slug') }}';
            url = url.replace(':slug', row_id.replace('row', ''));

            $.ajax({
                url: url,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $(element).attr('disabled', true);
                    $(element).html('<i class="fa fa-spinner fa-spin"></i>');
                }
            }).done(function(response, textStatus) {
                if (textStatus == 'success') {
                    $(element).closest('tr').hide();
                    $(element).closest('div.widget-box').hide();
                    toastr.options.positionClass = 'toast-bottom-right';
                    toastr.success(response.message);
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                $.alert({
                    title: 'Encountered an error!',
                    content: textStatus + ': ' + errorThrown,
                    icon: 'fa fa-warning',
                    type: 'red'
                });
            });
        }

        function readNotification(element) {
            var row_id = $(element).closest('div.widget-box').attr('id');

            var url = '{{ route('notifications.markAsRead', ':slug') }}';
            url = url.replace(':slug', row_id.replace('row', ''));

            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function(response, textStatus) {
                if (textStatus == 'success') {
                    $(element).closest('tr').css('font-weight', 'normal');
                    $(element).closest('div.widget-box').removeClass('widget-color-blue');
                    $(element).hide();
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                $.alert({
                    title: 'Encountered an error!',
                    content: textStatus + ': ' + errorThrown,
                    icon: 'fa fa-warning',
                    type: 'red'
                });
            });
        }
    </script>

@endsection
