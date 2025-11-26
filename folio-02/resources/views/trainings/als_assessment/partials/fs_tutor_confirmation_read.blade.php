<!-- Assessor and FS Tutor Confirmation -->
<div class="card" style="margin-bottom: 4px">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <col width="33%" />
                <col width="33%" />
                <col width="33%" />
                <thead>
                    <tr>
                        <th colspan="3" class="text-center">Functional Skills Tutor Confirmation</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th colspan="3" class="text-center">
                            <i>
                                I agree that I will adhere to the above plan to support the learner in achieving their
                                qualification in a timely manner.<br>
                                I will provide all necessary reasonable adjustments and complete monthly reviews.
                            </i>
                        </th>
                    </tr>
                    <tr>
                        <td>Functional Skills Tutor Signature</td>
                        <td>
                            {!! $alsAssessment->fs_tutor_sign == '1'
                                ? '<i class="fa fa-check-circle fa-2x green"></i>'
                                : '<i class="fa fa-times fa-2x red"></i>' !!}
                        </td>
                        <td>
                            {{ optional($alsAssessment->fs_tutor_sign_date)->format('d/m/Y') }}
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>
</div>
