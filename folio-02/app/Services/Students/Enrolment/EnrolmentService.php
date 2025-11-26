<?php

namespace App\Services\Students\Enrolment;

use App\DTO\Enrolment\EnrolmentDTO;
use App\Helpers\AppHelper;
use App\Models\Lookups\TrainingStatusLookup;
use App\Models\Programmes\ProgrammeQualification;
use App\Models\Student;
use App\Models\Training\TrainingRecord;
use App\Services\Qualifications\ProgrammeToTrainingService;
use App\Services\Students\Trainings\Reviews\TrainingRecordReviewService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class EnrolmentService
{
    public function enrolmentAllowed()
    {
        return AppHelper::enrolmentAllowed();
    }

    public function enrolStudent(Student $student, EnrolmentDTO $enrolmentDto, $createBlankReviews = false)
    {
        abort_if(! $this->enrolmentAllowed(), Response::HTTP_FORBIDDEN, 'You have exceeded the number of licenses purchased, please contact Perspective (UK) Ltd. to enrol more learners.');

        $training = null;

        DB::beginTransaction();
        try
        {
            $training = $student->training_records()->create([
                'programme_id' => $enrolmentDto->programme->id,
                'learner_ref' => self::generateLearnerRef($student),
                'status_code' => TrainingStatusLookup::STATUS_CONTINUING,
                'start_date' => $enrolmentDto->startDate,
                'planned_end_date' => $enrolmentDto->plannedEndDate,
                'epa_date' => $enrolmentDto->epaDate,
                'employer_location' => $enrolmentDto->employerLocation->id,
                'primary_assessor' => $enrolmentDto->primaryAssessor->id,
                'secondary_assessor' => optional($enrolmentDto->secondaryAssessor)->id,
                'verifier' => $enrolmentDto->verifier->id,
                'tutor' => optional($enrolmentDto->tutor)->id,
            ]);
    
            $training->update([
                'system_ref' => self::generateSystemRef($training)
            ]);

            foreach ($enrolmentDto->qualifications as $qualification) 
            {
                $programmeQualification = $qualification->programmeQualification;

                $additionalFields = [
                    'start_date' => $qualification->startDate,
                    'planned_end_date' => $qualification->plannedEndDate,
                    'fs_tutor_id' => $qualification->fsTutor,
                    'fs_verifier_id' => $qualification->fsVerifier,
                ];

                $portfolio = $this->addPortfolio($training, $programmeQualification, $enrolmentDto->unitIds, $additionalFields);
            }

            // This code was used to copy the programme plans (group of units) to the training record on enrolment.
            /*foreach($training->programme->training_plans AS $plan)
            {
                $planUnits = json_decode($plan->plan_units);
                $studentPlanUnits = [];
                foreach($planUnits AS $planUnitId)
                {
                    if($planUnitId != "empty" && isset($mappingUnitIds[$planUnitId]))
                        $studentPlanUnits[] = $mappingUnitIds[$planUnitId];
                }
                // TO DO: Do not create a plan if all the units are not part of learner's training record
                $training->training_plans()->create([
                    'plan_number' => $plan->plan_number,
                    'plan_units' => json_encode($studentPlanUnits),
                ]);
            }*/

            if($createBlankReviews)
            {
                (new TrainingRecordReviewService)->generateBlankReviews($training);
            }

            DB::commit();
        }
        catch(Exception $ex)
        {
            DB::rollBack();
            throw new Exception($ex->getMessage());
        }

        return $training;
    }

    private static function generateLearnerRef(Student $student)
    {
        $reference = TrainingRecord::max('learner_ref');
        $reference += 1;
        $learnerReference = str_pad($reference, 12, '0', STR_PAD_LEFT);

        if($student->training_records()->count() > 0)
        {
            $learnerReference = $student->training_records->first()->learner_ref;
        }

        return $learnerReference;
    }

    private static function generateSystemRef(TrainingRecord $training)
    {
        return substr(
            $training->student->id . substr( $training->student->firstnames, 0, 1 ) . $training->id . str_replace( ["'", "-", " "], ["", "", ""], $training->student->surname ),
            0,
            14
        );
    }

    public function addPortfolio(TrainingRecord $training, ProgrammeQualification $programmeQualification, array $unitsIds, array $additionalFields)
    {
        $portfolio = (new ProgrammeToTrainingService)->copyProgrammeQualificationToTrainingPortfolio($programmeQualification, $training, $unitsIds);
        $portfolio->update([
            'start_date' => $additionalFields['start_date'],
            'planned_end_date' => $additionalFields['planned_end_date'],
            'fs_tutor_id' => $additionalFields['fs_tutor_id'] ?? null,
            'fs_verifier_id' => $additionalFields['fs_verifier_id'] ?? null,
            'status_code' => TrainingStatusLookup::STATUS_CONTINUING,
            'tbl_qualification_id' => $programmeQualification->id,
        ]);

        return $portfolio;
    }
}