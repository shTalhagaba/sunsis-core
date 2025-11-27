@php
    $messages = $inbox_messages;//\Auth::user()->received()->orderBy('created_at', 'DESC')->get();
    $newMessages = \Auth::user()->received()->unSeen()->count();
@endphp
<div class="message-container">
    <!-- message navbar here -->
    <div id="inbox-id-message-list-navbar" class="message-navbar clearfix panelInbox">
        <div class="message-bar">
            <div class="message-infobar" id="id-message-infobar">
                <span class="blue bigger-150">Inbox</span>
                <span class="grey bigger-110">
                    @if ($newMessages == 0)
                    (no unread message)
                    @elseif($newMessages == 1)
                    (1 unread message)
                    @else
                    ({{ $newMessages }} unread messages)
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
                            <a href="#" onclick="mark_messages_archive('inbox');">
                                <i class="ace-icon fa fa-folder-open orange"></i>&nbsp; Archive
                            </a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <a href="#" onclick="mark_messages_read('inbox');">
                                <i class="ace-icon fa fa-eye blue"></i>&nbsp; Mark as read
                            </a>
                        </li>

                        <li>
                            <a href="#" onclick="mark_messages_unread('inbox');">
                                <i class="ace-icon fa fa-eye-slash green"></i>&nbsp; Mark unread
                            </a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <a href="#"  onclick="delete_messages('inbox');">
                                <i class="ace-icon fa fa-trash-o red bigger-110"></i>&nbsp; Delete
                            </a>
                        </li>
                    </ul>
                </div>

                <button type="button" class="btn btn-xs btn-white btn-primary">
                    <i class="ace-icon fa fa-trash-o bigger-125 orange"></i>
                    <span class="bigger-110">Delete</span>
                </button>
            </div>
        </div>
        <div>
            <div class="messagebar-item-left">
                <label class="inline middle">
                    <input type="checkbox" id="inbox-id-toggle-all" class="ace" />
                    <span class="lbl"></span>
                </label>

                &nbsp;
                <div class="inline position-relative">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                        <i class="ace-icon fa fa-caret-down bigger-125 middle"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-lighter dropdown-100">
                        <li>
                            <a id="inbox-id-select-message-all" href="#">All</a>
                        </li>

                        <li>
                            <a id="inbox-id-select-message-none" href="#">None</a>
                        </li>

                        <li class="divider"></li>

                        <li>
                            <a id="inbox-id-select-message-unread" href="#">Unread</a>
                        </li>

                        <li>
                            <a id="inbox-id-select-message-read" href="#">Read</a>
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
                            <a href="{{ route('messages.index') }}?tab=inbox&order_by=date">
                                <i class="ace-icon fa fa-check {{ $order_by == 'date' ? 'green' : 'invisible' }}"></i> Date (latest first)
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('messages.index') }}?tab=inbox&order_by=from">
                                <i class="ace-icon fa fa-check {{ $order_by == 'from' ? 'green' : 'invisible' }}"></i> From
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('messages.index') }}?tab=inbox&order_by=subject">
                                <i class="ace-icon fa fa-check {{ $order_by == 'subject' ? 'green' : 'invisible' }}"></i> Subject
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- <div class="nav-search minimized">
                <form class="form-search" id="frmSearchInbox">
                    <span class="input-icon">
                        <input type="text" autocomplete="off" class="input-small nav-search-input" placeholder="Search inbox ..." />
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                </form>
            </div> --}}
        </div>
    </div>

    <div class="message-list-container">
        <div class="message-list table-responsive inbox-message-list">
            <table class="table table-bordered">
                @forelse ($messages as $message)
                <tr onclick="showMessageDetail(event, '{{ $message->id }}');"
                    class="inbox-message-item
                    inbox-message-{{ $message->canBeSetAsRead() ? 'unread' : 'read' }}
                    message-item message-{{ $message->canBeSetAsRead() ? 'unread' : 'read' }}">
                    <td>
                        <label class="inline">
                            <input type="checkbox" class="ace" name="inbox-checkboxes[]" value="{{ $message->id }}" />
                            <span class="lbl"></span>
                        </label>
                    </td>
                    <td>
                        <span class="sender">{{ $message->sender->full_name }} </span>
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
{{--        <div class="pull-left"> {{ count($messages) }} messages total </div>--}}

        <div class="pull-right">
{{--            {{ $messages->appends($_GET)->links() }}--}}
            <div class="inline middle"> showing {{ ($messages->currentpage()-1)*$messages->perpage()+1 }} to {{ $messages->currentpage()*$messages->perpage() > $messages->total() ? $messages->total() : $messages->currentpage()*$messages->perpage() }} of {{ $messages->total() }} messages</div>
            &nbsp; &nbsp;
            <ul class="pagination middle">
                {{ $messages->appends($_GET)->links() }}
            </ul>
        </div>
    </div><!-- /.message-footer -->
</div>

<script>


        var Inbox = {
        //displays a toolbar according to the number of selected messages
        display_bar : function (count) {
            if(count == 0) {
                $('#inbox-id-toggle-all').removeAttr('checked');
                $('#inbox-id-message-list-navbar .message-toolbar').addClass('hide');
                $('#inbox-id-message-list-navbar .message-infobar').removeClass('hide');
            }
            else {
                $('#inbox-id-message-list-navbar .message-infobar').addClass('hide');
                $('#inbox-id-message-list-navbar .message-toolbar').removeClass('hide');
            }
        }
        ,
        select_all : function() {
            var count = 0;
            $('.inbox-message-item input[type=checkbox]').each(function(){
                this.checked = true;
                $(this).closest('.inbox-message-item').addClass('selected');
                count++;
            });

            $('#inbox-id-toggle-all').get(0).checked = true;

            Inbox.display_bar(count);
        }
        ,
        select_none : function() {
            $('.inbox-message-item input[type=checkbox]').removeAttr('checked').closest('.inbox-message-item').removeClass('selected');
            $('#inbox-id-toggle-all').get(0).checked = false;

            Inbox.display_bar(0);
        }
        ,
        select_read : function() {
            $('.inbox-message-unread input[type=checkbox]').removeAttr('checked').closest('.inbox-message-item').removeClass('selected');

            var count = 0;
            $('.inbox-message-item:not(.inbox-message-unread) input[type=checkbox]').each(function(){
                this.checked = true;
                $(this).closest('.inbox-message-item').addClass('selected');
                count++;
            });
            Inbox.display_bar(count);
        }
        ,
        select_unread : function() {
            $('.inbox-message-item:not(.inbox-message-unread) input[type=checkbox]').removeAttr('checked').closest('.inbox-message-item').removeClass('selected');

            var count = 0;
            $('.inbox-message-unread input[type=checkbox]').each(function(){
                this.checked = true;
                $(this).closest('.inbox-message-item').addClass('selected');
                count++;
            });

            Inbox.display_bar(count);
        }
    }



</script>
