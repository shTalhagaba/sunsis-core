<?php

namespace App\Services\Qualifications;

use App\Models\Programmes\Programme;
use App\Models\Programmes\ProgrammeQualification;
use App\Models\Programmes\ProgrammeQualificationUnit;
use App\Models\Programmes\ProgrammeQualificationUnitPC;
use App\Models\Qualifications\Qualification;

class QualificationToProgrammeService extends BaseCopyService
{
    public function copyQualificationToProgramme(Qualification $qualification, Programme $programme)
    {
        $programmeQualification = new ProgrammeQualification();
        $programmeQualification->programme_id = $programme->id;
        $programmeQualification->sequence = $programme->qualifications()->count() + 1;

        return $this->copy($qualification, $programmeQualification, [
            'units' => [
                'class' => ProgrammeQualificationUnit::class,
                'relations' => [
                    'pcs' => ProgrammeQualificationUnitPC::class,
                ]
            ]
        ]);
    }
}
