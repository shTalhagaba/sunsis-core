<?php

namespace App\Services\Qualifications;

use App\Models\Qualifications\Central\CentralQualification;
use App\Models\Qualifications\Qualification;
use App\Models\Qualifications\QualificationUnit;
use App\Models\Qualifications\QualificationUnitPC;

class CentralQualificationToLocalQualfiicationService extends BaseCopyService
{
    public function copyQualificationToLocal(CentralQualification $centralQualification, array $unitIds)
    {
        $localQualification = new Qualification();
        $localQualification->id = null;

        $relationshipMap = [
            'units' => [
                'class' => QualificationUnit::class,
                'relations' => [
                    'pcs' => QualificationUnitPC::class,
                ]
            ]
        ];

        // Filter units by the specified IDs
        $centralQualification->load(['units' => function($query) use ($unitIds) {
            $query->whereIn('id', $unitIds);
        }]);

        return $this->copy($centralQualification, $localQualification, $relationshipMap);
    }
}
