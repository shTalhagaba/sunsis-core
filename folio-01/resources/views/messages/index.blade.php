@extends('layouts.master')

@section('title', 'Messages')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('messages.index') }}
@endsection

@section('page-content')

<div class="page-header"><h1>Messages</h1></div>

<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

        <div class="row">
            <div class="col-sm-12">
                <div class="tabbable">
                    <ul class="nav nav-tabs inbox-tabs padding-16 tab-size-bigger tab-space-1" id="inbox-tabs">
                        <li class="li-new-mail pull-right">
                            <a href="{{ route('messages.compose') }}">
                                <span class="btn btn-purple no-border">
                                    <i class="ace-icon fa fa-envelope bigger-110"></i>
                                    <span class="bigger-110">Compose Message</span>
                                </span>
                            </a>
                        </li><!-- /.li-new-mail -->

                        <li class="{{ $tab == 'inbox' ? 'active' : '' }}">
                            <a href="{{ route('messages.index') }}?tab=inbox" >
                                <i class="blue ace-icon fa fa-inbox bigger-130"></i>
                                <span class="bigger-110">Inbox</span>
                            </a>
                        </li>{{-- Inbox --}}

                        <li class="{{ $tab == 'sent' ? 'active' : '' }}">
                            <a href="{{ route('messages.index') }}?tab=sent" >
                                <i class="orange ace-icon fa fa-location-arrow bigger-130"></i>
                                <span class="bigger-110">Sent</span>
                            </a>
                        </li>{{-- Sent --}}

                        <li class="{{ $tab == 'draft' ? 'active' : '' }}">
                            <a href="{{ route('messages.index') }}?tab=draft" >
                                <i class="green ace-icon fa fa-pencil bigger-130"></i>
                                <span class="bigger-110">Draft</span>
                            </a>
                        </li>{{-- Draft --}}

                        <li class="{{ $tab == 'archive' ? 'active' : '' }}">
                            <a href="{{ route('messages.index') }}?tab=archive" >
                                <i class="green ace-icon fa fa-archive bigger-130"></i>
                                <span class="bigger-110">Archive</span>
                            </a>
                        </li>{{-- Archive --}}


                    </ul>

                    <div class="tab-content">
                        <div id="inbox" class="tab-pane {{ $tab == 'inbox' ? 'in active' : '' }}">
                            @include('messages.partials.tab-inbox')
                        </div>
                        <div id="sent" class="tab-pane {{ $tab == 'sent' ? 'in active' : '' }}">
                            @include('messages.partials.tab-sent')
                        </div>
                        <div id="draft" class="tab-pane {{ $tab == 'draft' ? 'in active' : '' }}">
                            @include('messages.partials.tab-draft')
                        </div>
                        <div id="archive" class="tab-pane {{ $tab == 'archive' ? 'in active' : '' }}">
                            @include('messages.partials.tab-archive')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PAGE CONTENT ENDS -->
    <div><!-- /.col -->
</div><!-- /.row -->

@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
@endsection

@section('page-inline-scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //check/uncheck all messages
    $('#inbox-id-toggle-all').removeAttr('checked').on('click', function(){
        if(this.checked) {
            Inbox.select_all();
        } else Inbox.select_none();
    });
    $('#sent-id-toggle-all').removeAttr('checked').on('click', function(){
        if(this.checked) {
            Sent.select_all();
        } else Sent.select_none();
    });
    $('#draft-id-toggle-all').removeAttr('checked').on('click', function(){
        if(this.checked) {
            Draft.select_all();
        } else Draft.select_none();
    });
    $('#archive-id-toggle-all').removeAttr('checked').on('click', function(){
        if(this.checked) {
            Archive.select_all();
        } else Archive.select_none();
    });

    //select all
    $('#inbox-id-select-message-all').on('click', function(e) {
        e.preventDefault();
        Inbox.select_all();
    });
    $('#sent-id-select-message-all').on('click', function(e) {
        e.preventDefault();
        Sent.select_all();
    });
    $('#draft-id-select-message-all').on('click', function(e) {
        e.preventDefault();
        Draft.select_all();
    });
    $('#archive-id-select-message-all').on('click', function(e) {
        e.preventDefault();
        Archive.select_all();
    });

    //select none
    $('#inbox-id-select-message-none').on('click', function(e) {
        e.preventDefault();
        Inbox.select_none();
    });
    $('#sent-id-select-message-none').on('click', function(e) {
        e.preventDefault();
        Sent.select_none();
    });
    $('#draft-id-select-message-none').on('click', function(e) {
        e.preventDefault();
        Draft.select_none();
    });
    $('#archive-id-select-message-none').on('click', function(e) {
        e.preventDefault();
        Archive.select_none();
    });

    //select read
    $('#inbox-id-select-message-read').on('click', function(e) {
        e.preventDefault();
        Inbox.select_read();
    });
    $('#sent-id-select-message-read').on('click', function(e) {
        e.preventDefault();
        Sent.select_read();
    });

    //select unread
    $('#inbox-id-select-message-unread').on('click', function(e) {
        e.preventDefault();
        Inbox.select_unread();
    });
    $('#sent-id-select-message-unread').on('click', function(e) {
        e.preventDefault();
        Sent.select_unread();
    });

    //basic initializations
    $('.inbox-message-list .message-item input[type=checkbox]').removeAttr('checked');
    $('.inbox-message-list').on('click', '.message-item input[type=checkbox]', function() {
        $(this).closest('.message-item').toggleClass('selected');
        if (this.checked) Inbox.display_bar(1); //display action toolbar when a message is selected
        else {
            Inbox.display_bar($('.inbox-message-list input[type=checkbox]:checked').length);
            //determine number of selected messages and display/hide action toolbar accordingly
        }
    });
    $('.sent-message-list .message-item input[type=checkbox]').removeAttr('checked');
    $('.sent-message-list').on('click', '.message-item input[type=checkbox]', function() {
        $(this).closest('.message-item').toggleClass('selected');
        if (this.checked) Sent.display_bar(1); //display action toolbar when a message is selected
        else {
            Sent.display_bar($('.sent-message-list input[type=checkbox]:checked').length);
        }
    });
    $('.draft-message-list .message-item input[type=checkbox]').removeAttr('checked');
    $('.draft-message-list').on('click', '.message-item input[type=checkbox]', function() {
        $(this).closest('.message-item').toggleClass('selected');
        if (this.checked) Draft.display_bar(1); //display action toolbar when a message is selected
        else {
            Draft.display_bar($('.draft-message-list input[type=checkbox]:checked').length);
        }
    });
    $('.archive-message-list .message-item input[type=checkbox]').removeAttr('checked');
    $('.archive-message-list').on('click', '.message-item input[type=checkbox]', function() {
        $(this).closest('.message-item').toggleClass('selected');
        if (this.checked) Archive.display_bar(1); //display action toolbar when a message is selected
        else {
            Archive.display_bar($('.archive-message-list input[type=checkbox]:checked').length);
        }
    });

    function showMessageDetail(e, message_id)
    {
        var target = e.target;
        while (target)
        {
            if (target.tagName === 'TD')
            {
                break;
            }
            target = target.parentElement;
        }

        if (target === null) {return;}
        if (target.cellIndex === 0) {return;}

        window.location.href='/messages/'+message_id;
    }

    function mark_messages_archive(tab)
    {
        var message_ids = [];
        $('.'+tab+'-message-item input[type=checkbox]').each(function(){
            if(this.checked)
                message_ids.push(this.value);
        });

        if(message_ids.length == 0)
            return;

        $.ajax({
            url: '{{ route("messages.multiple.setAsArchive") }}',
            type: 'POST',
            data: {message_ids: message_ids}
        }).done(function(response, textStatus) {
            window.location.reload();
        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log(textStatus, errorThrown);
        });
    }

    function mark_messages_unread(tab)
    {
        var message_ids = [];
        $('.'+tab+'-message-item input[type=checkbox]').each(function(){
            if(this.checked)
                message_ids.push(this.value);
        });

        if(message_ids.length == 0)
            return;

        $.ajax({
            url: '{{ route("messages.multiple.setAsUnread") }}',
            type: 'POST',
            data: {message_ids: message_ids}
        }).done(function(response, textStatus) {
            window.location.reload();
        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log(textStatus, errorThrown);
        });
    }

    function mark_messages_read(tab)
    {
        var message_ids = [];
        $('.'+tab+'-message-item input[type=checkbox]').each(function(){
            if(this.checked)
                message_ids.push(this.value);
        });

        if(message_ids.length == 0)
            return;

        $.ajax({
            url: '{{ route("messages.multiple.setAsRead") }}',
            type: 'POST',
            data: {message_ids: message_ids}
        }).done(function(response, textStatus) {
            window.location.reload();
        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log(textStatus, errorThrown);
        });
    }

    function delete_messages(tab)
    {
        var message_ids = [];
        $('.'+tab+'-message-item input[type=checkbox]').each(function(){
            if(this.checked)
                message_ids.push(this.value);
        });

        if(message_ids.length == 0)
            return;

        $.ajax({
            url: '{{ route("messages.multiple.setAsDelete") }}',
            type: 'POST',
            data: {message_ids: message_ids}
        }).done(function(response, textStatus) {
            window.location.reload();
        }).fail(function(jqXHR, textStatus, errorThrown){
            console.log(textStatus, errorThrown);
        });
    }

</script>
@endsection

