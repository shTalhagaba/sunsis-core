@php
    $draft_messages = \Auth::user()->sent()->inDraft()->orderBy('created_at', 'DESC')->get();
@endphp
<div class="message-container">
    <!-- message navbar here -->
    <div id="draft-id-message-list-navbar" class="message-navbar clearfix panelDraft">
        <div class="message-bar">
            <div class="message-infobar" id="id-message-infobar">
                <span class="blue bigger-150">Draft</span>
                <span class="grey bigger-110">
                    @if (count($draft_messages) == 0)
                    (no draft message)
                    @elseif(count($draft_messages) == 1)
                    (1 draft message)
                    @else
                    ({{ count($draft_messages) }} draft messages)
                    @endif
                </span>
            </div>
            <div class="message-toolbar hide">
                <button type="button" class="btn btn-xs btn-white btn-primary" onclick="delete_messages('draft');">
                    <i class="ace-icon fa fa-trash-o bigger-125 orange"></i>
                    <span class="bigger-110">Delete</span>
                </button>
            </div>
        </div>
        <div>
            <div class="messagebar-item-left">
                <label class="inline middle">
                    <input type="checkbox" id="draft-id-toggle-all" class="ace" />
                    <span class="lbl"></span>
                </label>

                &nbsp;
                <div class="inline position-relative">
                    <a href="#" data-toggle="dropdown" class="dropdown-toggle">
                        <i class="ace-icon fa fa-caret-down bigger-125 middle"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-lighter dropdown-100">
                        <li>
                            <a id="draft-id-select-message-all" href="#">All</a>
                        </li>

                        <li>
                            <a id="draft-id-select-message-none" href="#">None</a>
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
                            <a href="{{ route('messages.index') }}?tab=draft&order_by=date">
                                <i class="ace-icon fa fa-check {{ $order_by == 'date' ? 'green' : 'invisible' }}"></i> Date (latest first)
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('messages.index') }}?tab=draft&order_by=to">
                                <i class="ace-icon fa fa-check {{ $order_by == 'to' ? 'green' : 'invisible' }}"></i> To
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('messages.index') }}?tab=draft&order_by=subject">
                                <i class="ace-icon fa fa-check {{ $order_by == 'subject' ? 'green' : 'invisible' }}"></i> Subject
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- <div class="nav-search minimized">
                <form class="form-search">
                    <span class="input-icon">
                        <input type="text" autocomplete="off" class="input-small nav-search-input" placeholder="Search draft ..." />
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                </form>
            </div> --}}
        </div>
    </div>

    <div class="message-list-container">
        <div class="message-list table-responsive draft-message-list">
            <table class="table table-bordered">
                @forelse ($draft_messages as $message)
                <tr onclick="window.location.href='{{ route('messages.compose', $message) }}?mode=draft_send'"
                    class="draft-message-item draft-message-read message-item message-read">
                    <td>
                        <label class="inline">
                            <input type="checkbox" class="ace" name="draft-checkboxes[]" value="{{ $message->id }}" />
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
        <div class="pull-left"> {{ count($draft_messages) }} messages total </div>
    </div><!-- /.message-footer -->
</div>

<script>

    var Draft = {
        //displays a toolbar according to the number of selected messages
        display_bar : function (count) {
            if(count == 0) {
                $('#draft-id-toggle-all').removeAttr('checked');
                $('#draft-id-message-list-navbar .message-toolbar').addClass('hide');
                $('#draft-id-message-list-navbar .message-infobar').removeClass('hide');
            }
            else {
                $('#draft-id-message-list-navbar .message-infobar').addClass('hide');
                $('#draft-id-message-list-navbar .message-toolbar').removeClass('hide');
            }
        }
        ,
        select_all : function() {
            var count = 0;
            $('.draft-message-item input[type=checkbox]').each(function(){
                this.checked = true;
                $(this).closest('.draft-message-item').addClass('selected');
                count++;
            });

            $('#draft-id-toggle-all').get(0).checked = true;

            Draft.display_bar(count);
        }
        ,
        select_none : function() {
            $('.draft-message-item input[type=checkbox]').removeAttr('checked').closest('.draft-message-item').removeClass('selected');
            $('#draft-id-toggle-all').get(0).checked = false;

            Draft.display_bar(0);
        }
    }






</script>
