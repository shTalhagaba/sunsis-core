<?php

namespace App\Http\Controllers\Training\Evidences;

use App\Exports\TrainingEvidencesExport;
use App\Filters\TrainingRecordEvidenceFilters;
use App\Http\Controllers\Controller;
use App\Services\Students\Trainings\Evidences\TrainingRecordEvidenceService;
use Maatwebsite\Excel\Facades\Excel;

class EvidenceExportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function __invoke(TrainingRecordEvidenceFilters $filters, TrainingRecordEvidenceService $trainingRecordEvidenceService)
    {
        $query = $trainingRecordEvidenceService->unpaginatedIndex(auth()->user()->user_type, $filters);

        return Excel::download(new TrainingEvidencesExport($filters, $query), 'TrainingsEvidences.xlsx');
    }
}