<?php

namespace App\Models\Training;

use App\Models\Lookups\QualificationTypeLookup;
use App\Models\User;
use App\Traits\Filterable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use Filterable;

    protected $table = 'portfolios';

    protected $fillable = [
        "tr_id",
        "start_date",
        "planned_end_date",
        "actual_end_date",
        "status_code",
        "qan",
        "title",
        "min_glh",
        "max_glh",
        "glh",
        "total_credits",
        "assessment_methods",
        "ab_registration_number",
        "ab_registration_date",
        "tbl_qualification_id",
        "cert_applied",
        "cert_received",
        "cert_sent_to_learner",
        "main",
        "sequence",
        "proportion",
        "duration",
        "offset",
        "learning_outcome",
        "fs_tutor_id",
        "fs_verifier_id",
        "type",
        "certificate_no",
        "cert_expiry_date",
        "batch_no",
        "candidate_no",
    ];

    public function training_record()
    {
        return $this->belongsTo(TrainingRecord::class, 'tr_id');
    }

    public function tutor()
    {
        return $this->hasOne(User::class, 'id', 'fs_tutor_id');
    }

    public function verifier()
    {
        return $this->hasOne(User::class, 'id', 'fs_verifier_id');
    }

    public function student()
    {
        return $this->belongsTo(TrainingRecord::class, 'tr_id');
    }

    public function units()
    {
        return $this->hasMany(PortfolioUnit::class, 'portfolio_id')
            ->orderBy('unit_sequence', 'ASC')
            ->orderBy('id', 'ASC');
    }

    public function reviews()
    {
        return $this->hasMany(TrainingReview::class, 'portfolio_id');
    }

    public function isFsQualification()
    {
        return in_array($this->type, QualificationTypeLookup::FS_QUAL_TYPES);
    }

    public function getStartDateAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function getPlannedEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function getActualEndDateAttribute($value)
    {
        return $value != '' ? Carbon::parse($value)->format('d/m/Y') : '';
    }

    public static function getStatusCodeAttribute($value)
    {
        $list = ['1' => 'Continuing', '2' => 'Completed', '3' => 'Withdrawn', '4' => 'Temporary Withdrawn'];
        return isset($list[$value]) ? $list[$value] : $value;
    }

    public static function getDDLStatusCode($blank = true)
    {
        $list = ['1' => 'Continuing', '2' => 'Completed', '3' => 'Withdrawn', '4' => 'Temporary Withdrawn'];
        return $blank ? ['' => ''] + $list : $list;
    }

    public function totalPCs()
    {
        $total_pcs = \DB::table('portfolio_pcs')
            ->join('portfolio_units', 'portfolio_pcs.portfolio_unit_id', '=', 'portfolio_units.id')
            ->where('portfolio_units.portfolio_id', '=', $this->id)
            ->count();

        return $total_pcs;
    }

    public function pcsWithEvidence() //mapped
    {
        $evidenced_pcs = \DB::table('tr_evidences')
            ->join('pc_evidence_mappings', 'tr_evidences.id', '=', 'pc_evidence_mappings.tr_evidence_id')
            ->join('portfolio_pcs', 'pc_evidence_mappings.portfolio_pc_id', '=', 'portfolio_pcs.id')
            ->join('portfolio_units', 'portfolio_pcs.portfolio_unit_id', '=', 'portfolio_units.id')
            ->where('portfolio_units.portfolio_id', '=', $this->id)
            ->where('tr_evidences.evidence_status', '!=', \App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_REJECTED)
            ->count();

        return $evidenced_pcs;
    }

    public function pcsWithEvidencePercentage() //mapped %
    {
        if ($this->totalPCs() == 0)
            return 0;

        return round(($this->pcsWithEvidence() / $this->totalPCs()) * 100);
    }

    public function signedOffPCs() //signed off
    {
        $signedoff_pcs = \DB::table('portfolio_pcs')
            ->join('portfolio_units', 'portfolio_pcs.portfolio_unit_id', '=', 'portfolio_units.id')
            ->where('portfolio_units.portfolio_id', '=', $this->id)
            ->where('portfolio_pcs.assessor_signoff', '=', 1)
            ->count();

        return $signedoff_pcs;
    }

    public function notSignedOffPCs() //signed off
    {
        $signedoff_pcs = \DB::table('portfolio_pcs')
            ->join('portfolio_units', 'portfolio_pcs.portfolio_unit_id', '=', 'portfolio_units.id')
            ->where('portfolio_units.portfolio_id', '=', $this->id)
            ->where('portfolio_pcs.assessor_signoff', '!=', 1)
            ->count();

        return $signedoff_pcs;
    }

    public function signedOffPCsPercentage() //signed off %
    {
        if ($this->totalPCs() == 0)
            return 0;

        return round(($this->signedOffPCs() / $this->totalPCs()) * 100);
    }

    public function awaitingSignoff() //awaiting signoff
    {
        return $this->pcsWithEvidence() - $this->signedOffPCs();
    }

    public function awaitingSignoffPercentage() //awaiting signoff %
    {
        if ($this->totalPCs() == 0)
            return 0;

        return round((($this->pcsWithEvidence() / $this->totalPCs()) * 100) - $this->signedOffPCsPercentage());
    }

    public function signedOffUnits()
    {
        $units = 0;
        foreach ($this->units as $unit) {
            if ($unit->isSignedOff())
                $units++;
        }
        return $units;
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($portfolio) {
            $portfolio->units()->each(function ($portfolio_unit) {
                $portfolio_unit->delete();
            });
        });
    }

    public function isSafeToDelete()
    {
        foreach ($this->units as $unit) {
            if (!$unit->isSafeToDelete())
                return false;
        }
        return true;
    }

    public function getAwaitingProgressPercentage()
    {
        $awaiting = 0;
        $awaiting_signoff_percentage = 0;
        foreach ($this->units as $unit) {
            if (!$unit->isSignedOff()) {
                $awaiting++;
                $awaiting_signoff_percentage += $unit->getAwaitingProgressPercentage();
            }
        }

        return round($awaiting_signoff_percentage / $awaiting);
    }

    public function getAwaitingPercentage() // orange bar
    {
        $orange = 0;
        $units = $this->units;
        if (count($units) == 0)
            return 0;
        foreach ($units as $unit) {
            $orange += $unit->getAwaitingPercentage();
        }

        $result = round(($orange / count($units)));
        return $result > 100 ? 100 : $result;
    }

    public function getProgressPercentageGreen() // green bar
    {
        $green = 0;
        $units = $this->units;
        if (count($units) == 0)
            return 0;
        foreach ($units as $unit) {
            $green += $unit->getProgressPercentageGreen();
        }
        $result = round(($green / count($units)));
        return $result > 100 ? 100 : $result;
    }

    public function getProgressPercentageBlue() // blue bar
    {
        $blue = 0;
        $units = $this->units;
        if (count($units) == 0)
            return 0;
        foreach ($units as $unit) {
            $blue += $unit->getProgressPercentageBlue();
        }
        $result = round(($blue / count($units)));
        return $result > 100 ? 100 : $result;
    }

    public function getTargetProgressAttribute()
    {
        $programme = $this->training_record->programme;
        $leewayInDays = $programme->leeway * 7;

        // Calculate the elapsed time
        $startDate = Carbon::parse($this->getOriginal('start_date'));
        $leewayStartDate = $startDate->addDays($leewayInDays);
        $currentDate = Carbon::now();
        $elapsedDays = $currentDate->greaterThan($leewayStartDate)
            ? $leewayStartDate->diffInDays($currentDate)
            : 0;

        // Calculate the total duration
        $plannedEndDate = Carbon::parse($this->getOriginal('planned_end_date'));
        $totalDuration = $startDate->diffInDays($plannedEndDate);

        // Avoid division by zero
        if ($totalDuration <= 0) {
            return 0;
        }

        // Calculate the target progress percentage
        $progress = ($elapsedDays / $totalDuration) * 100;

        // Ensure progress does not exceed 100%
        return round(min($progress, 100));
    }

    public function getActualProgressAttribute()
    {
        $allPcs = $this->totalPCs();
        $signedOffPcs = $this->signedOffPCs();

        if ($allPcs == 0) {
            return 0; // Avoid division by zero
        }

        // Calculate the overall progress as a percentage
        return round(($signedOffPcs / $allPcs) * 100);
    }

    const STATUS_CONTINUE = 1;
    const STATUS_COMPMLETED = 2;
    const STATUS_WITHDRAWN = 3;
    const STATUS_TEMP_WITHDRAWN = 4;
}