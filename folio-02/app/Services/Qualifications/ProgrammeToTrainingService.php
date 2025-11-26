<?php

namespace App\Services\Qualifications;

use App\Models\Programmes\ProgrammeQualification;
use App\Models\Training\Portfolio;
use App\Models\Training\PortfolioPC;
use App\Models\Training\PortfolioUnit;
use App\Models\Training\TrainingRecord;


class ProgrammeToTrainingService extends BaseCopyService
{
    public function copyProgrammeQualificationToTrainingPortfolio(ProgrammeQualification $programmeQualification, TrainingRecord $training, array $unitIds)
    {
        $portfolio = new Portfolio();
        $portfolio->tr_id = $training->id;

        $relationshipMap = [
            'units' => [
                'class' => PortfolioUnit::class,
                'relations' => [
                    'pcs' => PortfolioPC::class,
                ]
            ]
        ];

        // Filter units by the specified IDs
        $programmeQualification->load(['units' => function($query) use ($unitIds) {
            $query->whereIn('id', $unitIds);
        }]);

        return $this->copy($programmeQualification, $portfolio, $relationshipMap);
    }
}
