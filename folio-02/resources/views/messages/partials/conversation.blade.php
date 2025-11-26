<div class="row">
    <div class="col-sm-12">
        <div class="message-content" id="id-message-content">
            <div class="message-header clearfix">
                <div class="pull-left">
                    <span class="blue bigger-125"> {{ $_message->subject }} </span>

                    <div class="space-4"></div>

                    <img class="middle" src="{{ $_message->sender->avatar_url }}" width="32" /> <br>
                    <span class="sender">{{ $_message->sender->full_name }}</span> <br>
                    <i class="ace-icon fa fa-envelope bigger-110 orange middle"></i>
                    <span>{{ $_message->sender->primary_email }}</span> <br>
                    <i class="ace-icon fa fa-clock-o bigger-110 orange middle"></i>
                    <span class="time grey">{{ \Carbon\Carbon::parse($_message->created_at)->diffForHumans() }}</span>
                </div>
            </div>
            <div class="hr hr-double"></div>
            <div class="message-body">
                {!! nl2br($_message->content) !!}
            </div>
            <div class="hr hr-double"></div>
        </div>
    </div>
</div>
