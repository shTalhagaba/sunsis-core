<div class="card" style="margin-bottom: 4px">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <col width="33%" />
                <col width="33%" />
                <col width="33%" />
                <thead>
                    <tr>
                        <th colspan="3" class="text-center">Learner Confirmation</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th colspan="3" class="text-center">
                            <i>
                                I agree that the above Learning Support Plan is necessary to support my apprenticeship and will attend all agreed sessions and monthly reviews.<br>
                                I am aware that if I do not attend sessions, I may be withdrawn from the apprenticeship.<br>
                                I am aware that I have not received a formal diagnosis of any learning difficulty.
                            </i>
                        </th>
                    </tr>
                    <tr>
                        <td>Learner Signature</td>
                        <td>
                            {!! $alsAssessment->learner_sign == '1' ? '<i class="fa fa-check-circle fa-2x green"></i>' : '<i class="fa fa-times fa-2x red"></i>' !!}
                        </td>
                        <td>
                            {{ optional($alsAssessment->learner_sign_date)->format('d/m/Y') }}
                        </td>
                    </tr>
                    <tr>
                        <th class="text-center">
                            <i>
                                I give my consent to sharing this support assessment and plan with my employer.
                            </i>
                        </th>
                        <td>
                            {{ $alsAssessment->share_with_employer }}
                        </td>
                        <td>
                            {!! $alsAssessment->learner_confirm_choice == '1' ? '<i class="fa fa-check-circle fa-2x green"></i>' : '' !!}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>