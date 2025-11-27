@extends('layouts.master')

@section('title', 'Compose Message')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('messages.compose') }}
@endsection

@section('page-content')
<div class="page-header"><h1>Compose Message</h1></div>
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

        <div class="row">
            <div class="col-sm-12">
                <div class="well well-sm">
                    <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.history.back();">
                        <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                {!! Form::open([
                    'class' => 'form-horizontal',
                    'name' => 'frmMessage',
                    'files' => true,
                    'id' => 'id-message-form'
                ]) !!}
                @if ($mode == 'reply')
                @include('messages.partials.send-form', [
                    'message_id' => $message->id,
                    'to_id' => $message->sender->id,
                    'subject' => Str::startsWith($message->subject, 'RE: ') ? $message->subject : 'RE: ' . $message->subject,
                ])
                @elseif ($mode == 'forward')
                @include('messages.partials.send-form', [
                    'message_id' => null,
                    'to_id' => null,
                    'subject' => Str::startsWith($message->subject, 'FW: ') ? $message->subject : 'FW: ' . $message->subject,
                    'content' => nl2br($message->content),
                ])
                @elseif ($mode == 'draft_send')
                @include('messages.partials.send-form', [
                    'message_id' => $message->id,
                    'to_id' => $message->receiver->id,
                    'subject' => $message->subject,
                    'content' => nl2br($message->content),
                ])
                @else
                @include('messages.partials.send-form')
                @endif
                {!! Form::close() !!}
            </div>
        </div>

        <hr>
        @if($mode == 'reply')
            @if(!$message->isRoot())
                @include('messages.partials.conversation', ['_message' => $message->root])
                @foreach ($message->root->conversation as $_message)
                    @include('messages.partials.conversation')
                @endforeach
            @else
            @include('messages.partials.conversation', ['_message' => $message])
            @endif
        @endif

        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.inputlimiter.min.js') }}"></script>
@endsection

@section('page-inline-scripts')

<script>
    if(!ace.vars['touch']) {
        $('.chosen-select').chosen({allow_single_deselect:true});
        //resize the chosen on window resize

        $(window)
        .off('resize.chosen')
        .on('resize.chosen', function() {
            $('.chosen-select').each(function() {
                    var $this = $(this);
                    $this.next().css({'width': $this.parent().width()});
            })
        }).trigger('resize.chosen');
        //resize chosen on sidebar collapse/expand
        $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
            if(event_name != 'sidebar_collapsed') return;
            $('.chosen-select').each(function() {
                    var $this = $(this);
                    $this.next().css({'width': $this.parent().width()});
            })
        });


        $('#chosen-multiple-style .btn').on('click', function(e){
            var target = $(this).find('input[type=radio]');
            var which = parseInt(target.val());
            if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
                else $('#form-field-select-4').removeClass('tag-input-style');
        });
    }

    $(function(){
        $('.inputLimiter').inputlimiter();
    });

    $('#btnDraft').on('click', function(e){
        e.preventDefault();

        var form = document.forms['frmMessage'];
        form.action = '{{ route("message.saveAsDraft") }}';

        form.submit();
    });

    var mode = '{{ $mode }}';

    $('#btnSend').on('click', function(e){
        e.preventDefault();

        var form = document.forms['frmMessage'];

        if(form.elements['to_id'].value == '')
        {
            bootbox.alert({
                title: 'Validation',
                message: 'Please select the user to whom you want to send the message.'
            });
            return;
        }

        if(form.elements['subject'].value == '')
        {
            bootbox.alert({
                title: 'Validation',
                message: 'Please give a suitable subject to your message.'
            });
            return;
        }

        if(form.elements['content'].value == '')
        {
            bootbox.alert({
                title: 'Validation',
                message: 'It is not possible to send blank message, please provide your message in the box.'
            });
            return;
        }

        if(window.mode == 'new')
            form.action = '{{ route("messages.send") }}';
        else if(mode == 'reply')
            form.action = '{{ !is_null($message) ? route("messages.respond", $message) : '' }}';
        else if(mode == 'forward')
            form.action = '{{ !is_null($message) ? route("messages.respond", $message) : '' }}';
        else if(mode == 'draft_send')
            form.action = '{{ !is_null($message) ? route("messages.draft_send", $message) : '' }}';

        form.submit();
    });

</script>


@endsection

