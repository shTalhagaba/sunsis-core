<div class="row">
    <div class="col-sm-12">

        <div class="widget-box transparent">
            <div class="widget-header">
                <h5 class="widget-title">Learner Comments</h5>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <col width="40%" />
                            <col width="60%" />
                            <tr>
                                <th>How is the extra support helping you?</th>
                                <td>{!! (isset($learnerComments->question1) ? nl2br(e($learnerComments->question1)) : '') !!}</td>
                            </tr>
                            <tr>
                                <th>What areas do you feel you have improved on this month?</th>
                                <td>{!! (isset($learnerComments->question2) ? nl2br(e($learnerComments->question2)) : '') !!}</td>
                            </tr>
                            <tr>
                                <th>Without the additional support, what barriers might you face?</th>
                                <td>{!! (isset($learnerComments->question3) ? nl2br(e($learnerComments->question3)) : '') !!}</td>
                            </tr>
                            <tr>
                                <th>Do you need more support than you are currently getting?</th>
                                <td>{!! (isset($learnerComments->question4) ? nl2br(e($learnerComments->question4)) : '') !!}</td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
