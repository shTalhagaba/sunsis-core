@php
$_person = \Auth::user();

if($_message->isSentBy($_person))
{
    $isReceived = false;
}
if($isReceived)
{
    $_person = $_message->sender;
}
$avatar_url = $_person->getFirstMediaUrl('avatars');
@endphp
<div class="message-content" id="id-message-content">

    <div class="message-header clearfix">
        <div class="pull-left">
            <span class="blue bigger-125"> {{ $_message->subject }} </span>

            <div class="space-4"></div>

            <img class="middle" src="{{ $avatar_url }}" width="32" /> <br>
            <span class="sender">{{ $_person->full_name }}</span> <br>
            <i class="ace-icon fa fa-envelope bigger-110 orange middle"></i>
            <span>{{ $_person->primary_email }}</span> <br>
            <i class="ace-icon fa fa-clock-o bigger-110 orange middle"></i>
            <span class="time grey">{{ \Carbon\Carbon::parse($_message->created_at)->diffForHumans() }}</span>
        </div>

        <div class="pull-right action-buttons">
            <a href="#">
                <i class="ace-icon fa fa-reply green icon-only bigger-130"></i>
            </a>

            <a href="#">
                <i class="ace-icon fa fa-mail-forward blue icon-only bigger-130"></i>
            </a>

            <a href="#">
                <i class="ace-icon fa fa-trash-o red icon-only bigger-130"></i>
            </a>
        </div>
    </div>

    <div class="hr hr-double"></div>

    <div class="message-body">
        {!! $_message->content !!}
    </div>

    <div class="hr hr-double"></div>

</div>
