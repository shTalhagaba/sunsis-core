<?php

namespace App\Http\Controllers\Training;

use App\Filters\TrainingRecordEvidenceFilters;
use App\Http\Controllers\Controller;
use App\Models\Lookups\UserTypeLookup;
use App\Services\Students\Trainings\Evidences\TrainingRecordEvidenceService;
use Illuminate\Http\Response;

class EvidenceIndexController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function __invoke(TrainingRecordEvidenceFilters $filters, TrainingRecordEvidenceService $trainingRecordEvidenceService)
    {
	abort_if(
            !in_array(auth()->user()->user_type, [UserTypeLookup::TYPE_STUDENT, UserTypeLookup::TYPE_ADMIN, UserTypeLookup::TYPE_ASSESSOR]),
            Response::HTTP_UNAUTHORIZED
        );

        $query = $trainingRecordEvidenceService->unpaginatedIndex(auth()->user()->user_type, $filters);

        $evidences = $query->paginate(session('evidences_per_page', config('model_filters.default_per_page')));

        return view('trainings.evidences.index', compact('evidences', 'filters'));
    }
}