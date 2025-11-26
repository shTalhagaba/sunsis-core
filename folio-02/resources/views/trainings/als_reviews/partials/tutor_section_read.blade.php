<div class="row">
    <div class="col-sm-12">

        <div class="widget-box transparent">
            <div class="widget-header">
                <h5 class="widget-title">Functional Skills (to be completed by FS Tutor)</h5>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>
                                        What reasonable adjustments have been provided by the Functional Skills
                                        Specialist this month?
                                        Normal ways of working are monthly workshops/recordings. Reasonable adjustments
                                        set out below are outside normal ways of working.
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reasonableAdjustments as $adjustment)
                                    <tr>
                                        <td>{{ $adjustment->description }}</td>
                                        <td>{!! in_array($adjustment->id, $selectedReasonableAdjustmentsTutor)
                                            ? '<i class="fa fa-check-circle green fa-2x"></i>'
                                            : '' !!}</td>
                                    </tr>
                                @endforeach
                                @if (!empty($alsReview->reasonable_adjustments_other_tutor))
                                    <tr>
                                        <td colspan="2">
                                            {!! nl2br(e($alsReview->reasonable_adjustments_other_tutor)) !!}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Session Date</th>
                                    <th>Session Topics (Intent)</th>
                                    <th>How have reasonable adjustment supported the learner?
                                        (Impact)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alsReview->alsSessions('Tutor') AS $session)
                                    <tr>
                                        <td>{{ $session->session_date->format('d/m/Y') }}</td>
                                        <td>{{ $session->session_topics }}</td>
                                        <td>{!! nl2br(e($session->learner_support_detail)) !!}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">No records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
