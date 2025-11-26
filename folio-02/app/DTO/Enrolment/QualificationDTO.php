<?php

namespace App\DTO\Enrolment;

use App\Models\Programmes\ProgrammeQualification;

class QualificationDTO
{
    /**
     *
     * @var int
     */
    public $programmeQualificationId;

    /**
     *
     * @var ProgrammeQualification
     */
    public $programmeQualification;

    /**
     *
     * @var string
     */
    public $startDate;

    /**
     *
     * @var int
     */
    public $fsTutor;

    /**
     *
     * @var int
     */
    public $fsVerifier;

    /**
     *
     * @var string
     */
    public $plannedEndDate;

    public function __construct($programmeQualificationId, $startDate, $plannedEndDate, $fsTutor = null, $fsVerifier = null)
    {
        $this->programmeQualificationId = $programmeQualificationId;
        $this->startDate = $startDate;
        $this->plannedEndDate = $plannedEndDate;
        $this->fsTutor = $fsTutor;
        $this->fsVerifier = $fsVerifier;

        $this->programmeQualification = ProgrammeQualification::findOrFail($programmeQualificationId);
    }
}