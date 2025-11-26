<div class="space-4"></div>
<div class="row">
    <div class="col-sm-12">
        @if(count($unit->iqa) > 0)
        <div class="widget-box transparent">
            <div class="widget-header">
                <h5 class="widget-title">IQA and Assessor Comments</h5>
                <div class="widget-toolbar">
                    <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-up"></i></a>
                </div>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    @foreach ($unit->iqa as $iqa_history)
                        <div class="itemdiv dialogdiv">
                            <div class="user">
                                <i class="fa fa-comments"></i>
                            </div>
                            <div class="body">
                                <div class="time">
                                    <i class="ace-icon fa fa-clock-o"></i>
                                    <span
                                        class="green">{{ \Carbon\Carbon::parse($iqa_history->created_at)->format('d/m/Y H:i:s') }}</span>
                                </div>
                                <div class="name">
                                    <h4>
                                        @php
                                            $iqa_created_by = \App\Models\User::findOrFail(
                                                $iqa_history->user_id,
                                            );
                                            echo $iqa_created_by->full_name;
                                        @endphp
                                    </h4>
                                </div>
                                <span class="label label-info">{{ $iqa_history->iqa_type }}</span>
                                <div class="text">{!! nl2br($iqa_history->comments) !!}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        <hr>
    </div>
</div>
