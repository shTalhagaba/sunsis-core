<?php

namespace App\Services\Programmes;

use App\Models\Programmes\Programme;
use App\Models\Programmes\ProgrammeQualification;

class ProgrammeService
{
    public function create(array $programmeData)
    {
        $programme = Programme::create( array_merge($programmeData, ['created_by' => auth()->user()->id]) );

        return $programme;
    }

    public function update(array $programmeData, Programme $programme)
    {
        $programme->update( $programmeData );

        return $programme;
    }

    public function delete(Programme $programme)
    {
        return $programme->delete();
    }

    public function removeQualification(Programme $programme, $qualificationId)
    {
        $programmeQualification = ProgrammeQualification::findOrFail($qualificationId);
        $programmeQualification->delete();
    }
}