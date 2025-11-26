<?php

namespace App\Models;

use App\Models\Lookups\UserTypeLookup;
use App\Models\Organisations\Organisation;
use App\Models\Training\TrainingRecordEvidence;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LookupManager
{

    public static function getEthnicities($value = '')
    {
        if (!Session::exists('user.lookup_ethnicities')) {
            $list = DB::table('lookup_ethnicities')->pluck('description', 'id')->toArray();
            Session::put('user.lookup_ethnicities', $list);
        } else {
            $list = Session::get('user.lookup_ethnicities');
        }

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getQualificationUnitGroups($value = '')
    {
        if (!Session::exists('qualification.unit.lookup_unit_groups')) {
            $list = DB::table('lookup_unit_groups')->pluck('description', 'id')->toArray();
            Session::put('qualification.unit.lookup_unit_groups', $list);
        } else {
            $list = Session::get('qualification.unit.lookup_unit_groups');
        }

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getAssessors($value = '')
    {
        if (!Session::exists('lookup_assessors')) {
            $list = \App\Models\User::orderBy('firstnames')->select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->where('user_type', UserTypeLookup::TYPE_ASSESSOR)
                ->pluck('name', 'id')->toArray();
            Session::put('lookup_assessors', $list);
        } else {
            $list = Session::get('lookup_assessors');
        }

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getVerifiers($value = '')
    {
        if (!Session::exists('lookup_verifiers')) {
            $list = \App\Models\User::orderBy('firstnames')->select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->where('user_type', UserTypeLookup::TYPE_VERIFIER)
                ->pluck('name', 'id')->toArray();
            Session::put('lookup_verifiers', $list);
        } else {
            $list = Session::get('lookup_verifiers');
        }

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getTutors($value = '')
    {
        if (!Session::exists('lookup_tutors')) {
            $list = \App\Models\User::orderBy('firstnames')->select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->where('user_type', UserTypeLookup::TYPE_TUTOR)
                ->pluck('name', 'id')->toArray();
            Session::put('lookup_tutors', $list);
        } else {
            $list = Session::get('lookup_tutors');
        }

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getQualificationUnitPcCategory($value = '')
    {
        if (!Session::exists('qualification.unit.pc.lookup_evidence_categories')) {
            $list = DB::table('lookup_evidence_categories')->orderBy('description')->pluck('description', 'id')->toArray();
            Session::put('qualification.unit.pc.lookup_evidence_categories', $list);
        } else {
            $list = Session::get('qualification.unit.pc.lookup_evidence_categories');
        }

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function nameOfUser($userId)
    {
        $usersArray = Session::get('usersArray', []);

        if (isset($usersArray[$userId])) {
            return $usersArray[$userId];
        }

        $user = User::find($userId);
        if ($user) {
            $usersArray[$userId] = $user->full_name;
            Session::put('usersArray', $usersArray);
            return $user->full_name;
        }

        return '';
    }

    public static function getEvidenceAssessmentMethod($value = '')
    {
        if (!Session::exists('evidence.lookup_evidence_assessment_methods')) {
            $list = DB::table('lookup_evidence_assessment_methods')->pluck('description', 'id')->toArray();
            Session::put('evidence.lookup_evidence_assessment_methods', $list);
        } else {
            $list = Session::get('evidence.lookup_evidence_assessment_methods');
        }

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getEvidenceTypes($value = '')
    {
        if (!Session::exists('evidence.lookup_evidence_types')) {
            $list = DB::table('lookup_evidence_types')->pluck('description', 'id')->toArray();
            Session::put('evidence.lookup_evidence_types', $list);
        } else {
            $list = Session::get('evidence.lookup_evidence_types');
        }

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getTrainingRecordStatus($value = '')
    {
        if (!Session::exists('training.lookup_tr_status')) {
            $list = DB::table('lookup_tr_status')->pluck('description', 'id')->toArray();
            Session::put('training.lookup_tr_status', $list);
        } else {
            $list = Session::get('training.lookup_tr_status');
        }

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getBilReason($value = '')
    {
        $list = DB::table('lookup_tr_bil_reasons')->orderBy('description')->pluck('description', 'id')->toArray();

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getWithdrawalReason($value = '')
    {
        $list = DB::table('lookup_tr_withdrawl_reasons')->orderBy('description')->pluck('description', 'id')->toArray();

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getCompletionStatus($value = '')
    {
        $list = DB::table('lookup_tr_learning_outcome')->orderBy('description')->pluck('description', 'id')->toArray();

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getQualificationSSAs($value = '')
    {
        if (!Session::exists('qualification.lookup_qual_ssa')) {
            $list = DB::table('lookup_qual_ssa')->pluck('description', 'id')->toArray();
            Session::put('qualification.lookup_qual_ssa', $list);
        } else {
            $list = Session::get('qualification.lookup_qual_ssa');
        }

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getQualificationTypes($value = '')
    {
        if (!Session::exists('qualification.lookup_qual_types')) {
            $list = DB::table('lookup_qual_types')->pluck('description', 'id')->toArray();
            Session::put('qualification.lookup_qual_types', $list);
        } else {
            $list = Session::get('qualification.lookup_qual_types');
        }

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getQualificationStatus($value = '')
    {
        if (!Session::exists('qualification.lookup_qual_status')) {
            $list = DB::table('lookup_qual_status')->pluck('description', 'id')->toArray();
            Session::put('qualification.lookup_qual_status', $list);
        } else {
            $list = Session::get('qualification.lookup_qual_status');
        }

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getQualificationOwnersName($value = '')
    {
        if (!Session::exists('qualification.lookup_qual_owners')) {
            $list = DB::table('lookup_qual_owners')
                ->orderBy('owner_org_name', 'asc')
                ->select('owner_org_rn', DB::raw("CONCAT(owner_org_name, ' [', owner_org_acronym, ']') AS owner_org_detail"))
                ->pluck('owner_org_detail', 'owner_org_rn')
                ->toArray();
            Session::put('qualification.lookup_qual_owners', $list);
        } else {
            $list = Session::get('qualification.lookup_qual_owners');
        }

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getQualificationOwnersAcronym($value = '')
    {
        if (!Session::exists('qualification.lookup_qual_owners_acronyms')) {
            $list = DB::table('lookup_qual_owners')->pluck('owner_org_acronym', 'owner_org_rn')->toArray();
            Session::put('qualification.lookup_qual_owners_acronyms', $list);
        } else {
            $list = Session::get('qualification.lookup_qual_owners_acronyms');
        }

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getQualificationLevels($value = '')
    {
        if (!Session::exists('qualification.lookup_qual_levels')) {
            $list = DB::table('lookup_qual_levels')->pluck('description', 'id')->toArray();
            Session::put('qualification.lookup_qual_levels', $list);
        } else {
            $list = Session::get('qualification.lookup_qual_levels');
        }

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getGenderDDL()
    {
        return [
            'M'    => 'Male',
            'F'    => 'Female',
            'NB'   => 'Non-binary',
            'SELF' => 'Prefer to self-describe',
            'U'    => 'Prefer not to say',
        ];
    }

    public static function getQualificationOwnerOrganisations()
    {
        $orgs = DB::table('qual_orgs')->orderBy('name', 'asc')->pluck('name', 'rn');
        return $orgs;
    }

    public static function getOrganisationSectors($value = '')
    {
        if (!Session::exists('organisation.lookup_org_sectors')) {
            $list = DB::table('lookup_org_sectors')->pluck('description', 'id')->toArray();
            Session::put('organisation.lookup_org_sectors', $list);
        } else {
            $list = Session::get('organisation.lookup_org_sectors');
        }

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getEmployersDDL($org_type, $blank = true)
    {
        $orgs = DB::table('orgs')->where('org_type', '=', $org_type)->orderBy('legal_name', 'asc')->pluck('legal_name', 'id')->toArray();
        return $blank ? ['' => ''] + $orgs : $orgs;
    }

    public static function getOrganisationDescription($id)
    {
        if ($id == '')
            return;

        return DB::table('lookup_org_sectors')->select('description')->where('id', $id)->first()->description;
    }

    public static function getPerPageDDL()
    {
        return [
            10 => '10',
            20 => '20',
            30 => '30',
            40 => '40',
            50 => '50',
            100 => '100'
        ];
    }

    public static function getUserTypes($type = '', $without_learner = true)
    {
        if ($without_learner) {
            $types = [
                1 => 'Administrator',
                2 => 'Tutor',
                3 => 'Assessor',
                4 => 'Verifier',
                8 => 'Manager',
                12 => 'System Viewer',
                17 => 'EQA',
                18 => 'Employer User',
                19 => 'Quality Manager',
            ];
        } else {
            $types = [
                1 => 'Administrator',
                2 => 'Tutor',
                3 => 'Assessor',
                4 => 'Verifier',
                8 => 'Manager',
                12 => 'System Viewer',
                17 => 'EQA',
                18 => 'Employer User',
                19 => 'Quality Manager',
            ];
        }

        return $type == '' ? $types : (isset($types[$type]) ? $types[$type] : '');
    }

    /**
     * @static
     * @param int $size
     * @return string
     */
    public static function formatFileSize($size)
    {
        $sizes = array("&nbsp;B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");

        $i = 0;
        while ($size > 1024) {
            $size = $size / 1024;
            $i++;
        }

        return sprintf("%.1f " . $sizes[$i], $size);
    }

    public static function getQSearchYearList($blank = false)
    {
        $yearList = DB::table(env('LARS_DB_NAME') . '.CoreReference_LARS_AcademicYear_Lookup')->orderBy('StartDate', 'DESC')->pluck('AcademicYearDesc', 'AcademicYear')->toArray();
        return $blank ? ['' => ''] + $yearList : $yearList;
    }

    public static function getQSearchValidityCategory($blank = false)
    {
        $list = DB::table(env('LARS_DB_NAME') . '.CoreReference_LARS_ValidityCategory_Lookup')->whereNull('EffectiveTo')->orWhere('EffectiveTo', '>=', date('Y-m-d'))->orderBy('ValidityCategoryDesc2')->pluck('ValidityCategoryDesc2', 'ValidityCategory')->toArray();
        return $blank ? ['' => ''] + $list : $list;
    }

    public static function getQSearchAimsInclude()
    {
        return [
            'all' => 'All Aims',
            'funded' => 'All Funded Aims',
            'selected' => 'Only the following Aims'
        ];
    }

    public static function getEmployersLocationsDDL($employerId = '')
    {
        $lookupQuery = Organisation::with(['locations' => function ($query) {
            $query->select(DB::raw("CONCAT(title, ', ', address_line_1, ', ', postcode) AS location_title"), "id", "organisation_id");
        }])
            ->active()
            ->employers()
            ->orderBy('legal_name')
            ->select('legal_name', 'id');

        if ($employerId != '') {
            $lookupQuery->where('id', $employerId);
        }

        $employers = $lookupQuery->get();

        $locations = [];
        foreach ($employers as $organisation) {
            $locations[$organisation->legal_name] = $organisation->locations->pluck('location_title', 'id')->map(function ($locationName, $locationId) use ($organisation) {
                return $organisation->legal_name . ' [' . $locationName . ']';
            })->toArray();
        }

        return $locations;
    }

    public static function getEqaDDL()
    {
        $eqa_personnels = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->where('user_type', UserTypeLookup::TYPE_EQA)
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')->toArray();
        return $eqa_personnels;
    }

    public static function getOtjDdl($value = '')
    {
        if (!Session::exists('otj.lookup_otj_types')) {
            $list = DB::table('lookup_otj_types')->orderBy('description')->pluck('description', 'id')->toArray();
            Session::put('otj.lookup_otj_types', $list);
        } else {
            $list = Session::get('otj.lookup_otj_types');
        }

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getTrainingReviewTypes($value = '')
    {
        if (!Session::exists('review.lookup_review_types')) {
            $list = DB::table('lookup_review_types')->orderBy('description')->pluck('description', 'id')->toArray();
            Session::put('review.lookup_review_types', $list);
        } else {
            $list = Session::get('review.lookup_review_types');
        }

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getTrainingEvidenceStatusList()
    {
        return [
            TrainingRecordEvidence::STATUS_LEARNER_SUBMITTED => 'Learner Submitted',
            TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED => 'Assessor Accepted',
            TrainingRecordEvidence::STATUS_ASSESSOR_REJECTED => 'Assessor Rejected',
            TrainingRecordEvidence::STATUS_LEARNER_RESUBMITTED => 'Learner Resubmitted',
            TrainingRecordEvidence::STATUS_IQA_ACCEPTED => 'IQA Accepted',
            TrainingRecordEvidence::STATUS_IQA_REJECTED => 'IQA Rejected',
        ];
    }

    public static function getCrmTypeOfContacts($value = '')
    {
        if (!Session::exists('crm.lookup_crm_type_of_contacts')) {
            $list = DB::table('lookup_crm_type_of_contacts')->orderBy('description')->orderBy('sequence')->pluck('description', 'id')->toArray();
            Session::put('crm.lookup_crm_type_of_contacts', $list);
        } else {
            $list = Session::get('crm.lookup_crm_type_of_contacts');
        }

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getCrmSubjects($value = '')
    {
        if (!Session::exists('crm.lookup_crm_subjects')) {
            $list = DB::table('lookup_crm_subjects')->orderBy('description')->orderBy('sequence')->pluck('description', 'id')->toArray();
            Session::put('crm.lookup_crm_subjects', $list);
        } else {
            $list = Session::get('crm.lookup_crm_subjects');
        }

        if ($value == '')
            return $list;
        else
            return isset($list[$value]) ? $list[$value] : $value;
    }
}
