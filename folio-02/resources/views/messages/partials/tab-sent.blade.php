@php
    $sent_messages = $sent_messages;//\Auth::user()->sent()->orderBy('created_at', 'DESC')->get();
    $total_sent_messages_count = \Auth::user()->sent()->where('archive_for_sender', 0)->where('delete_for_sender', 0)->count();
@endphp
<div class="message-container">
    <!-- message navbar here -->
    <div id="sent-id-message-list-navbar" class="message-navbar clearfix panelSent">
        <div class="message-bar">
            <div class="message-infobar" id="id-message-infobar">
                <span class="blue bigger-150">Sent</span>
                <span class="grey bigger-110">
                    @if (($total_sent_messages_count) == 0)
                    (no sent message)
                    @elseif(($total_sent_messages_count) == 1)
                    (1 sent message)
                    @else
                    ({{ ($total_sent_messages_count) }} sent messages)
                    @endif
                </span>
            </div>
            <div class="message-toolbar hide">
                <div class="inline position-relative align-left">
                    <button type="button" class="btn-white btn-primary btn btn-xs dropdown-toggle" data-toggle="dropdown">
                        <span class="bigger-110">Action</span>

                        <i class="ace-icon fa fa-caret-down icon-on-right"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-lighter dropdown-caret dropdown-125">
                        <li>
                            <a href="#" onclick="mark_messages_archive('sent');">
                                <i class="ace-icon fa fa-folder-open orange"></i>&nbsp; Archive
                            </a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <a href="#"  onclick="delete_messages('sent');">
                                <i class="ace-icon fa fa-trash-o red bigger-110"></i>&nbsp; Delete
                            </a>
                        </li>
                    </ul>
                </div>

                <button type="button" class="btn btn-xs btn-white btn-primary" onclick="delete_messages('sent');">
                    <i class="ace-icon fa fa-trash-o bigger-125 orange"></i>
                    <span class="bigger-110">Delete</span>
                </button>
            </div>
        </div>
        <div>
            <div class="messagebar-item-left">
                <label class="inline middle">
                    <input type="checkbox" id="sent-id-toggle-all" class="ace" />
                    <span class="lbl"></span>
                </label>

                &nbsp;
                <div class="inline position-relative">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                        <i class="ace-icon fa fa-caret-down bigger-125 middle"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-lighter dropdown-100">
                        <li>
                            <a id="sent-id-select-message-all" href="#">All</a>
                        </li>

                        <li>
                            <a id="sent-id-select-message-none" href="#">None</a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <a id="sent-id-select-message-unread" href="#">Unread</a>
                        </li>

                        <li>
                            <a id="sent-id-select-message-read" href="#">Read</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="messagebar-item-right">
                <div class="inline position-relative">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                            Sort &nbsp;
                            <i class="ace-icon fa fa-caret-down bigger-125"></i>
                        </a>

                    <ul class="dropdown-menu dropdown-lighter dropdown-menu-right dropdown-100">
                        <li>
                            <a href="{{ route('messages.index') }}?tab=sent&order_by=date">
                                <i class="ace-icon fa fa-check {{ $order_by == 'date' ? 'green' : 'invisible' }}"></i> Date (latest first)
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('messages.index') }}?tab=sent&order_by=to">
                                <i class="ace-icon fa fa-check {{ $order_by == 'to' ? 'green' : 'invisible' }}"></i> To
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('messages.index') }}?tab=sent&order_by=subject">
                                <i class="ace-icon fa fa-check {{ $order_by == 'subject' ? 'green' : 'invisible' }}"></i> Subject
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- <div class="nav-search minimized">
                <form class="form-search">
                    <span class="input-icon">
                        <input type="text" autocomplete="off" class="input-small nav-search-input" placeholder="Search sent ..." />
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                </form>
            </div> --}}
        </div>
    </div>

    <div class="message-list-container">
        <div class="message-list table-responsive sent-message-list">
            <table class="table table-bordered">
                @forelse ($sent_messages as $message)
                <tr onclick="showMessageDetail(event, '{{ $message->id }}');"
                    class="sent-message-item sent-message-read message-item message-read">
                    <td>
                        <label class="inline">
                            <input type="checkbox" class="ace" name="sent-checkboxes[]" value="{{ $message->id }}" />
                            <span class="lbl"></span>
                        </label>
                    </td>
                    <td>
                        <span class="sender">{{ $message->receiver->full_name }} </span>
                    </td>
                    <td>
                        <span class="sender">{{ $message->subject }} </span>
                    </td>
                    <td>
                        <span class="summary">
                            <span class="text">
                                {{ \Str::limit($message->content, 100) }}
                            </span>
                        </span>
                    </td>
                    <td>
                        <span class="time">{{ \Carbon\Carbon::parse($message->created_at)->format('d/m/Y H:i:s') }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td><i>No messages.</i></td>
                </tr>
                @endforelse
            </table>
        </div><!-- /.message-list -->
    </div><!-- /.message-list-container -->
    <div class="message-footer clearfix">
{{--        <div class="pull-left"> {{ count($sent_messages) }} messages total </div>--}}

        <div class="pull-right">
{{--            {{ $sent_messages->appends($_GET)->links() }}--}}
            <div class="inline middle"> showing {{ ($sent_messages->currentpage()-1)*$sent_messages->perpage()+1 }} to {{ $sent_messages->currentpage()*$sent_messages->perpage() > $sent_messages->total() ? $sent_messages->total() : $sent_messages->currentpage()*$sent_messages->perpage() }} of {{ $sent_messages->total() }} messages</div>
            &nbsp; &nbsp;
            <ul class="pagination middle">
                {{ $sent_messages->appends($_GET)->links() }}
            </ul>
        </div>
    </div><!-- /.message-footer -->
</div>

<script>


    var Sent = {
        //displays a toolbar according to the number of selected messages
        display_bar : function (count) {
            if(count == 0) {
                $('#sent-id-toggle-all').removeAttr('checked');
                $('#sent-id-message-list-navbar .message-toolbar').addClass('hide');
                $('#sent-id-message-list-navbar .message-infobar').removeClass('hide');
            }
            else {
                $('#sent-id-message-list-navbar .message-infobar').addClass('hide');
                $('#sent-id-message-list-navbar .message-toolbar').removeClass('hide');
            }
        }
        ,
        select_all : function() {
            var count = 0;
            $('.sent-message-item input[type=checkbox]').each(function(){
                this.checked = true;
                $(this).closest('.sent-message-item').addClass('selected');
                count++;
            });

            $('#sent-id-toggle-all').get(0).checked = true;

            Sent.display_bar(count);
        }
        ,
        select_none : function() {
            $('.sent-message-item input[type=checkbox]').removeAttr('checked').closest('.sent-message-item').removeClass('selected');
            $('#sent-id-toggle-all').get(0).checked = false;

            Sent.display_bar(0);
        }
        ,
        select_read : function() {
            $('.sent-message-unread input[type=checkbox]').removeAttr('checked').closest('.sent-message-item').removeClass('selected');

            var count = 0;
            $('.sent-message-item:not(.sent-message-unread) input[type=checkbox]').each(function(){
                this.checked = true;
                $(this).closest('.sent-message-item').addClass('selected');
                count++;
            });
            Sent.display_bar(count);
        }
        ,
        select_unread : function() {
            $('.sent-message-item:not(.sent-message-unread) input[type=checkbox]').removeAttr('checked').closest('.sent-message-item').removeClass('selected');

            var count = 0;
            $('.sent-message-unread input[type=checkbox]').each(function(){
                this.checked = true;
                $(this).closest('.sent-message-item').addClass('selected');
                count++;
            });

            Sent.display_bar(count);
        }
    }






</script>
