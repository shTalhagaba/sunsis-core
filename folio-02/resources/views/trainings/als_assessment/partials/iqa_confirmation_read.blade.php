<div class="card" style="margin-bottom: 4px">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <col width="33%" />
                <col width="33%" />
                <col width="33%" />
                <thead>
                    <tr>
                        <th colspan="3" class="text-center">IQA Confirmation</th>
                    </tr>
                </thead>
                <tbody>                                    
                    <tr>
                        <th colspan="3" class="text-center">
                            <i>
                                I will support the assessor/learner as appropriate according to the above plan
                            </i>
                        </th>
                    </tr>
                    <tr>
                        <td>IQA Signature</td>
                        <td>
                            {!! $alsAssessment->iqa_sign == '1' ? '<i class="fa fa-check-circle fa-2x green"></i>' : '<i class="fa fa-times fa-2x red"></i>' !!}
                        </td>
                        <td>
                            {{ optional($alsAssessment->iqa_sign_date)->format('d/m/Y') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>