<div class="message-container">
    <!-- message navbar here -->
    <div id="archive-id-message-list-navbar" class="message-navbar clearfix panelArchive">
        <div class="message-bar">
            <div class="message-infobar" id="id-message-infobar">
                <span class="blue bigger-150">Archive</span>
                <span class="grey bigger-110">
                    @if (count($archive_messages) == 0)
                    (no archive message)
                    @elseif(count($archive_messages) == 1)
                    (1 archive message)
                    @else
                    ({{ count($archive_messages) }} archive messages)
                    @endif
                </span>
            </div>
            <div class="message-toolbar hide">
                <button type="button" class="btn btn-xs btn-white btn-primary" onclick="delete_messages('archive');">
                    <i class="ace-icon fa fa-trash-o bigger-125 orange"></i>
                    <span class="bigger-110">Delete</span>
                </button>
            </div>
        </div>
        <div>
            <div class="messagebar-item-left">
                <label class="inline middle">
                    <input type="checkbox" id="archive-id-toggle-all" class="ace" />
                    <span class="lbl"></span>
                </label>

                &nbsp;
                <div class="inline position-relative">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                        <i class="ace-icon fa fa-caret-down bigger-125 middle"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-lighter dropdown-100">
                        <li>
                            <a id="archive-id-select-message-all" href="#">All</a>
                        </li>

                        <li>
                            <a id="archive-id-select-message-none" href="#">None</a>
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
                            <a href="{{ route('messages.index') }}?tab=archive&order_by=date">
                                <i class="ace-icon fa fa-check {{ $order_by == 'date' ? 'green' : 'invisible' }}"></i> Date (latest first)
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('messages.index') }}?tab=archive&order_by=to">
                                <i class="ace-icon fa fa-check {{ $order_by == 'to' ? 'green' : 'invisible' }}"></i> To
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('messages.index') }}?tab=archive&order_by=subject">
                                <i class="ace-icon fa fa-check {{ $order_by == 'subject' ? 'green' : 'invisible' }}"></i> Subject
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- <div class="nav-search minimized">
                <form class="form-search">
                    <span class="input-icon">
                        <input type="text" autocomplete="off" class="input-small nav-search-input" placeholder="Search archive ..." />
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                </form>
            </div> --}}
        </div>
    </div>

    <div class="message-list-container">
        <div class="message-list table-responsive archive-message-list">
            <table class="table table-bordered">
                @forelse ($archive_messages as $message)
                <tr onclick="window.location.href='{{ route('messages.compose', $message) }}?mode=archive'"
                    class="archive-message-item archive-message-read message-item message-read">
                    <td>
                        <label class="inline">
                            <input type="checkbox" class="ace" name="archive-checkboxes[]" value="{{ $message->id }}" />
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
        <div class="pull-left"> {{ count($archive_messages) }} messages total </div>
    </div><!-- /.message-footer -->
</div>

<script>

    var Archive = {
        //displays a toolbar according to the number of selected messages
        display_bar : function (count) {
            if(count == 0) {
                $('#archive-id-toggle-all').removeAttr('checked');
                $('#archive-id-message-list-navbar .message-toolbar').addClass('hide');
                $('#archive-id-message-list-navbar .message-infobar').removeClass('hide');
            }
            else {
                $('#archive-id-message-list-navbar .message-infobar').addClass('hide');
                $('#archive-id-message-list-navbar .message-toolbar').removeClass('hide');
            }
        }
        ,
        select_all : function() {
            var count = 0;
            $('.archive-message-item input[type=checkbox]').each(function(){
                this.checked = true;
                $(this).closest('.archive-message-item').addClass('selected');
                count++;
            });

            $('#archive-id-toggle-all').get(0).checked = true;

            Archive.display_bar(count);
        }
        ,
        select_none : function() {
            $('.archive-message-item input[type=checkbox]').removeAttr('checked').closest('.archive-message-item').removeClass('selected');
            $('#archive-id-toggle-all').get(0).checked = false;

            Archive.display_bar(0);
        }
    }






</script>
