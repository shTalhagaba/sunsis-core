<?php

namespace App\DTO\Enrolment;

use App\DTO\Enrolment\QualificationDTO;
use App\Models\Organisations\Location as EmployerLocation;
use App\Models\Programmes\Programme;
use App\Models\Student;
use App\Models\User;

class EnrolmentDTO
{
    /**
     *
     * @var Student
     */
    public $student;

    /**
     *
     * @var Programme
     */
    public $programme;

    /**
     *
     * @var string
     */
    public $startDate;

    /**
     *
     * @var string
     */
    public $plannedEndDate;

    /**
     *
     * @var string
     */
    public $epaDate;

    /**
     *
     * @var EmployerLocation
     */
    public $employerLocation;

    /**
     *
     * @var User
     */
    public $primaryAssessor;

    /**
     *
     * @var User|null
     */
    public $secondaryAssessor;

    /**
     *
     * @var User|null
     */
    public $tutor;

    /**
     *
     * @var User
     */
    public $verifier;

    /**
     *
     * @var QualificationDTO[]
     */
    public $qualifications = [];

    /**
     *
     * @var array
     */
    public $unitIds = [];


    public function setStudent($studentId)
    {
        $student = Student::findOrFail($studentId);
        $this->student = $student;
        return $this;
    }

    public function setProgramme($programmeId)
    {
        $programme = Programme::findOrFail($programmeId);
        $this->programme = $programme->load('qualifications.units.pcs');
        return $this;
    }

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function setPlannedEndDate($plannedEndDate)
    {
        $this->plannedEndDate = $plannedEndDate;
        return $this;
    }

    public function setEpaDate($epaDate)
    {
        $this->epaDate = $epaDate;
        return $this;
    }

    public function setEmployerLocation($employerLocationId)
    {
        $this->employerLocation = EmployerLocation::findOrFail($employerLocationId);
        return $this;
    }

    public function setPrimaryAssessor($primaryAssessorId)
    {
        $this->primaryAssessor = User::findOrFail($primaryAssessorId);
        return $this;
    }

    public function setSecondaryAssessor($secondaryAssessorId)
    {
        $this->secondaryAssessor = User::findOrFail($secondaryAssessorId);
        return $this;
    }

    public function setTutor($tutorId)
    {
        $this->tutor = User::findOrFail($tutorId);
        return $this;
    }

    public function setVerifier($verifierId)
    {
        $this->verifier = User::findOrFail($verifierId);
        return $this;
    }

    public function addQualification(QualificationDTO $qualificationDto)
    {
        array_push($this->qualifications, $qualificationDto);
        return $this;
    }

    public function addUnitIds($unitIds)
    {
        if(is_int($unitIds))
        {
            array_push($this->unitIds, $unitIds);
        }

        if(is_array($unitIds))
        {
            $this->unitIds = array_merge($this->unitIds, $unitIds);
        }

        return $this;
    }
}