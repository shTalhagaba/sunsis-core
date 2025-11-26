<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PortfolioUnit extends Model
{
    protected $table = 'portfolio_units';

    protected $fillable = [
        "portfolio_id",
        "unit_sequence",
        "unit_group",
        "unit_owner_ref",
        "unique_ref_number",
        "title",
        "glh",
        "unit_credit_value",
        "learning_outcomes",
        "system_code",
        "unit_status",
        "iqa_status",
        "assessment_complete",
        "iqa_completed",
        "iqa_sample_id",
        "assessor_signoff",
    ];

    public function portfolio()
    {
    	return $this->belongsTo(Portfolio::class, 'portfolio_id');
    }

    public function scopeSignOffStatus($query, $signoff = true)
    {
        return $query->whereAssessorSignoff($signoff);
    }

    public function pcs()
    {
    	return $this->hasMany(PortfolioPC::class, 'portfolio_unit_id')
            ->orderBy('pc_sequence', 'ASC')
            ->orderBy('id', 'ASC');
    }

    public function iqa()
    {
    	return $this->hasMany(PortfolioUnitIqa::class, 'portfolio_unit_id');
    }

    public function eqa()
    {
    	return $this->hasMany(PortfolioUnitEqa::class, 'portfolio_unit_id');
    }

    public function notifications()
    {
        return $this->morphMany(\App\Models\Notification::class, 'notifiable');
    }

    public function pcsWithEvidence() //mapped excluding rejected
    {
        $evidenced_pcs = DB::table('tr_evidences')
                        ->join('pc_evidence_mappings', 'tr_evidences.id', '=', 'pc_evidence_mappings.tr_evidence_id')
                        ->join('portfolio_pcs', 'pc_evidence_mappings.portfolio_pc_id', '=', 'portfolio_pcs.id')
                        ->where('portfolio_pcs.portfolio_unit_id', '=', $this->id)
                        ->where('tr_evidences.evidence_status', '!=', \App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_REJECTED)
                        ->count();
        return $evidenced_pcs;
    }

    public function IqaStatusDesc()
    {
        return PortfolioUnitIqa::getDescription($this->iqa_status);
    }

    public function isAssessedByIqa()
    {
        return ! is_null($this->iqa_status);
    }

    public function pcsWithEvidencePercentage() //mapped excluding rejected %
    {
        return round(($this->pcsWithEvidence() / $this->pcs->count()) * 100);
    }

    public function pcsWithAssessorAcceptedEvidence()
    {
        $pcs_with_accepted_evidences = DB::table('tr_evidences')
                        ->join('pc_evidence_mappings', 'tr_evidences.id', '=', 'pc_evidence_mappings.tr_evidence_id')
                        ->join('portfolio_pcs', 'pc_evidence_mappings.portfolio_pc_id', '=', 'portfolio_pcs.id')
                        ->where('portfolio_pcs.portfolio_unit_id', '=', $this->id)
                        ->where('tr_evidences.evidence_status', '!=', TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED)
                        ->count();
        return $pcs_with_accepted_evidences;
    }

    public function signedOffPCs() //signed off
    {
        return $this->pcs->where('assessor_signoff', 1)->count();
    }

    public function signedOffPCsPercentage() //signed off %
    {
        if($this->pcs->count() == 0)
            return 0;
        return round( ($this->signedOffPCs() / $this->pcs->count()) * 100 );
    }

    public function awaitingSignoffPCs() //awaiting signoff
    {
        return $this->pcsWithEvidence() - $this->signedOffPCs();
    }

    public function awaitingSignoffPCsPercentage() //awaiting signoff %
    {
        if($this->pcs->count() == 0)
            return 0;

        return round( ($this->awaitingSignoffPCs() / $this->pcs->count())*100 );
    }

    public function isSignedOff()
    {
        return $this->pcs()->signOffStatus(false)->count() === 0;
    }

    public function isMandatory()
    {
        return $this->unit_group == 1;
    }

    public function isSafeToDelete()
    {
        return $this->pcs()->has('mapped_evidences')->count() > 0 ? false : true;
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($portfolio_unit) {
            $portfolio_unit->pcs()->each(function ($portfolio_pc) {
                $portfolio_pc->delete();
            });
        });
    }

    public function getAwaitingProgressPercentage()
    {
        $awaiting = 0;
        $awaiting_signedoff_pcs = $this->pcs()->where('assessor_signoff', 0)->count();
        if($awaiting_signedoff_pcs == 0)
            return 100;

        foreach($this->pcs AS $pc)
        {
            $awaiting += $pc->getProgressPercentage();
        }

        return round( ($awaiting/$awaiting_signedoff_pcs) );
    }

    public function isAnyPCReadyForSignoff()
    {
        foreach($this->pcs AS $pc)
        {
            if($pc->isReadyForSignOff())
                return true;
        }
        return false;
    }

    public function getAwaitingPercentage() // orange bar
    {
        $orange = 0;
        $pcs = $this->pcs;
        if(count($pcs) == 0)
            return 0;
        foreach($pcs AS $pc)
        {
            $orange += $pc->getAwaitingPercentage();
        }
        $result = round( ($orange/count($pcs)) );
        return $result > 100 ? 100 : $result ;
    }

    public function getProgressPercentageGreen() // green bar
    {
        $green = 0;
        $pcs = $this->pcs;
        if(count($pcs) == 0)
            return 0;
        foreach($pcs AS $pc)
        {
            $green += $pc->getProgressPercentageGreen();
        }
        $result = round( ($green/count($pcs)) );
        return $result > 100 ? 100 : $result ;
    }

    public function getProgressPercentageBlue() // blue bar
    {
        $green = 0;
        $pcs = $this->pcs;
        if(count($pcs) == 0)
            return 0;
        foreach($pcs AS $pc)
        {
            $green += $pc->getProgressPercentageBlue();
        }
        $result = round( ($green/count($pcs)) );
        return $result > 100 ? 100 : $result ;
    }
}
