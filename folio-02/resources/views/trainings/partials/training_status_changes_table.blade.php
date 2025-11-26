<table class="table table-bordered">
    <thead>
        <tr>
            <th>When</th>
            <th>From</th>
            <th>To</th>
            <th>By</th>
            <th>Additional Info</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($training->statusChanges as $audit)
        <tr>
            <td>{{ $audit->created_at->format('d/m/Y H:i:s') }}</td>
            <td>
                {{-- {{ App\Models\LookupManager::getTrainingRecordStatus($audit->status_code_from) }} --}}
                @include('trainings.partials.training_status_label', ['statusCode' => $audit->status_code_from])
            </td>
            <td>
                {{-- {{ App\Models\LookupManager::getTrainingRecordStatus($audit->status_code_to) }} --}}
                @include('trainings.partials.training_status_label', ['statusCode' => $audit->status_code_to])
            </td>
            <td>{{ $audit->creator->full_name }}</td>
            <td>
                @if ($audit->status_code_to == App\Models\Lookups\TrainingStatusLookup::STATUS_BIL)
                    <span class="text-info">Last day of learning: </span>{{ optional($audit->bil_last_day)->format('d/m/Y') }}<br>
                    <span class="text-info">BIL Reason: </span>{{ $audit->bil_reason != '' ? App\Models\LookupManager::getBilReason($audit->bil_reason) : '' }}<br>
                    <span class="text-info">Expected return date: </span>{{ optional($audit->bil_expected_return)->format('d/m/Y') }}<br>
                @elseif ($audit->status_code_to == App\Models\Lookups\TrainingStatusLookup::STATUS_CONTINUING)
                    <span class="text-info">Restart date: </span>{{ $audit->restart_date->format('d/m/Y') }}<br>
                    <span class="text-info">Revised Planned End date: </span>{{ optional($audit->revised_planned_end_date)->format('d/m/Y') }}
                    <span class="text-info">Revised EPA date: </span>{{ optional($audit->revised_epa_date)->format('d/m/Y') }}
                @elseif ($audit->status_code_to == App\Models\Lookups\TrainingStatusLookup::STATUS_COMPLETED)
                    <span class="text-info">Completion date: </span>{{ optional($audit->completion_date)->format('d/m/Y') }}<br>
                    <span class="text-info">Achievement date: </span>{{ optional($audit->achievement_date)->format('d/m/Y') }}<br>
                    <span class="text-info">Learning Outcome: </span>{{ $audit->learning_outcome != '' ? App\Models\LookupManager::getCompletionStatus($audit->learning_outcome) : '' }}<br>
                @elseif ($audit->status_code_to == App\Models\Lookups\TrainingStatusLookup::STATUS_WITHDRAWN)
                    <span class="text-info">Withdraw date: </span>{{ optional($audit->withdraw_date)->format('d/m/Y') }}<br>
                    <span class="text-info">Learning Outcome: </span>{{ $audit->learning_outcome != '' ? App\Models\LookupManager::getCompletionStatus($audit->learning_outcome) : '' }}<br>
                    <span class="text-info">Withdrawal Reason: </span>{{ $audit->withdrawal_reason != '' ? App\Models\LookupManager::getWithdrawalReason($audit->withdrawal_reason) : '' }}<br>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5"><i>No status change since enrolment</i></td>
        </tr>
        @endforelse
    </tbody>
</table>
