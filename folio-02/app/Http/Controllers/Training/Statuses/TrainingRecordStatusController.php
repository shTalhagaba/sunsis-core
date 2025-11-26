<?php

namespace App\Http\Controllers\Training\Statuses;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTrainingRecordStatusRequest;
use App\Models\LookupManager;
use App\Models\Lookups\TrainingStatusLookup;
use App\Models\Training\TrainingRecord;

class TrainingRecordStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }
    
    public function showUpdate(TrainingRecord $training)
    {
        $this->authorize('edit', $training);

        $statuses = [
            TrainingStatusLookup::STATUS_BIL => "Break in Learning",
            TrainingStatusLookup::STATUS_COMPLETED => "Completed",
            TrainingStatusLookup::STATUS_CONTINUING => "Continuing",
            TrainingStatusLookup::STATUS_WITHDRAWN => "Withdrawn",
            TrainingStatusLookup::STATUS_DEACTIVATED => "Deactivated",
        ];
        // unset($statuses[$training->status_code]);

        if($training->status_code == TrainingStatusLookup::STATUS_COMPLETED)
        {
            $statuses = [
                TrainingStatusLookup::STATUS_CONTINUING => 'Continuing'
            ];   
        }

        $bilReasons = LookupManager::getBilReason();

        $withdrawalReasons = LookupManager::getWithdrawalReason();

        $learningOutcomes = LookupManager::getCompletionStatus();

        $training->load([
            'statusChanges',
            'statusChanges.creator',
        ]);
        return view('trainings.update_status', compact('training', 'statuses', 'bilReasons', 'learningOutcomes', 'withdrawalReasons'));
    }
    
    public function storeUpdate(TrainingRecord $training, StoreTrainingRecordStatusRequest $request)
    {
        $this->authorize('edit', $training);

        $fromStatus = $training->status_code;
        $toStatus = $request->status_code;
        $data = [
            'status_code_from' => $fromStatus,
            'status_code_to' => $toStatus,
            'created_by' => auth()->user()->id,
        ];

        if($toStatus == TrainingStatusLookup::STATUS_BIL)
        {
            $data = array_merge($data, [
                'bil_last_day' => $request->input('last_day_of_learning'),
                'bil_reason' => $request->input('existing_bil_reason_id'),
                'bil_expected_return' => $request->input('expected_return_date'),
            ]);
        }
        elseif($toStatus == TrainingStatusLookup::STATUS_CONTINUING)
        {
            $data = array_merge($data, [
                'restart_date' => $request->input('restart_date'),
                'revised_planned_end_date' => $request->input('revised_planned_end_date'),
                'revised_epa_date' => $request->input('revised_epa_date'),
            ]);
            $training->start_date = $request->input('restart_date');
            $training->planned_end_date = $request->input('revised_planned_end_date');
            $training->epa_date = $request->input('revised_epa_date');
            $training->actual_end_date = null;
        }
        elseif($toStatus == TrainingStatusLookup::STATUS_COMPLETED)
        {
            $data = array_merge($data, [
                'completion_date' => $request->input('completion_date'),
                'achievement_date' => $request->input('achievement_date'),
                'learning_outcome' => $request->input('learning_outcome_completion'),
            ]);
            $training->actual_end_date = $request->completion_date;
        }
        elseif($toStatus == TrainingStatusLookup::STATUS_WITHDRAWN)
        {
            $data = array_merge($data, [
                'withdraw_date' => $request->input('withdraw_date'),
                'learning_outcome' => $request->input('learning_outcome_withdraw'),
                'withdrawal_reason' => $request->input('withdrawal_reason'),
            ]);
            $training->actual_end_date = $request->withdraw_date;
        }

        $training->status_code = $toStatus;
        $training->update();

        $training->statusChanges()->create($data);

        return redirect()
            ->route('trainings.show', $training)
            ->with(['alert-success' => 'Training record is updated successfully.']);
    }
}
