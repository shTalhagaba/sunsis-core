@extends('layouts.master')

@section('title', 'Messages')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('messages.show', $message) }}
@endsection

@section('page-content')

<div class="page-header"><h1>{{ $isReceived ? 'Received' : 'Sent' }} Message</h1></div>

<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="well well-sm">
            <button class="btn btn-sm btn-white btn-primary btn-round" type="button" id="btnBack">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
            </button>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="message-content" id="id-message-content">

                    <div class="message-toolbar text-center">
                        <div class="inline position-relative align-left">
                            <button type="button" class="btn-white btn-primary btn btn-xs dropdown-toggle" data-toggle="dropdown">
                                <span class="bigger-110">Action</span>

                                <i class="ace-icon fa fa-caret-down icon-on-right"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-lighter dropdown-caret dropdown-125">
                                <li>
                                    <a href="{{ route('messages.compose', $message) }}?mode=reply">
                                        <i class="ace-icon fa fa-mail-reply blue"></i>&nbsp; Reply
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('messages.compose', $message) }}?mode=forward">
                                        <i class="ace-icon fa fa-mail-forward green"></i>&nbsp; Forward
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('messages.single.setAsArchive', $message) }}">
                                        <i class="ace-icon fa fa-folder-open orange"></i>&nbsp; Archive
                                    </a>
                                </li>

                                <li class="divider"></li>

                                <li>
                                    <a href="{{ route('messages.single.setAsUnread', $message) }}">
                                        <i class="ace-icon fa fa-eye-slash green"></i>&nbsp; Mark unread
                                    </a>
                                </li>

                                <li class="divider"></li>

                                <li>
                                    <a href="{{ route('messages.single.setAsDelete', $message) }}">
                                        <i class="ace-icon fa fa-trash-o red bigger-110"></i>&nbsp; Delete
                                    </a>
                                </li>
                            </ul>
                        </div>


                        <button type="button" class="btn btn-xs btn-white btn-primary"
                        onclick="window.location.replace('{{ route('messages.single.setAsDelete', $message) }}')">
                            <i class="ace-icon fa fa-trash-o bigger-125 orange"></i>
                            <span class="bigger-110">Delete</span>
                        </button>
                    </div>
                    <div class="message-header clearfix">
                        <div class="pull-left">
                            <span class="blue bigger-125"> {{ $message->subject }} </span>

                            <div class="space-4"></div>

                            <img class="middle" src="{{ $avatar_url }}" width="32" /> <br>
                            <span class="sender">{{ $person->full_name }}</span> <br>
                            @if ($person->isOnline())
                            <label class="label label-success">Online</label>
                            @else
                            <label class="label label-default">Offline</label>
                            @endif
                            <br>
                            <i class="ace-icon fa fa-envelope bigger-110 orange middle"></i>
                            <span>{{ $person->primary_email }}</span> <br>
                            <i class="ace-icon fa fa-clock-o bigger-110 orange middle"></i>
                            <span class="time grey">{{ \Carbon\Carbon::parse($message->created_at)->diffForHumans() }}</span>
                        </div>

                        <div class="pull-right action-buttons">
                            <a title="reply to this message" href="{{ route('messages.compose', $message) }}?mode=reply">
                                <i class="ace-icon fa fa-reply green icon-only bigger-130"></i>
                            </a>

                            <a title="forward this message" href="{{ route('messages.compose', $message) }}?mode=forward">
                                <i class="ace-icon fa fa-mail-forward blue icon-only bigger-130"></i>
                            </a>

                            <a title="delete this message for you" href="{{ route('messages.single.setAsDelete', $message) }}">
                                <i class="ace-icon fa fa-trash-o red icon-only bigger-130"></i>
                            </a>
                        </div>
                    </div>

                    <div class="space-8"></div>

                    <div class="message-body">
                        {!! nl2br($message->content) !!}
                    </div>

                    <div class="hr hr-double"></div>

                </div>
            </div>
        </div>

        <hr>
        @if(!$message->isRoot())
            @include('messages.partials.conversation', ['_message' => $message->root])
            @foreach ($message->root->conversation as $_message)
                @include('messages.partials.conversation')
            @endforeach
        @endif
        <!-- PAGE CONTENT ENDS -->
    <div><!-- /.col -->
</div><!-- /.row -->

@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
@endsection

@section('page-inline-scripts')
<script>
    $('#btnBack').on('click', function(){
        window.location.href=document.referrer;
    });
</script>
@endsection

