<?php

namespace App\Models\Training;

use App\Models\Lookups\PcCategoryLookup;
use Illuminate\Database\Eloquent\Model;

class PortfolioPC extends Model
{
    protected $table = 'portfolio_pcs';

    protected $fillable = [
        "portfolio_unit_id",
        "pc_sequence",
        "reference",
        "category",
        "title",
        "min_req_evidences",
        "description",
        "assessor_signoff",
        "iqa_status",
        "portfolio_id",
        "accepted_evidences",
        "awaiting_evidences",
        "delivery_hours",
        "system_code",
    ];

    public function unit()
    {
    	return $this->belongsTo(PortfolioUnit::class, 'portfolio_unit_id');
    }

    public function scopeSignOffStatus($query, $signoff = true)
    {
        return $query->whereAssessorSignoff($signoff);
    }

    public function scopeKsbElements($query)
    {
        return $query->whereIn('category', [PcCategoryLookup::KSB_KNOWLEDGE, PcCategoryLookup::KSB_SKILLS, PcCategoryLookup::KSB_BEHAVIOURS]);
    }

    public function mapped_evidences()
    {
        return $this->belongsToMany(TrainingRecordEvidence::class, 'pc_evidence_mappings', 'portfolio_pc_id', 'tr_evidence_id')
             ->withPivot('created_by');
    }

    public function isSafeToDelete()
    {
        return $this->mapped_evidences->count() > 0 ? false : true;
    }

    public function getAwaitingEvidencesCount()
    {
        return $this->mapped_evidences()->where('evidence_status', TrainingRecordEvidence::STATUS_LEARNER_SUBMITTED)->count();
    }

    public function getAcceptedEvidencesCount()
    {
        return $this->mapped_evidences()->where('evidence_status', TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED)->count();
    }

    public function getAwaitingPercentage() // orange bar
    {
        // if($this->getAcceptedEvidencesCount() >= $this->min_req_evidences)
        if($this->accepted_evidences >= $this->min_req_evidences)
            return 0;

        // $result = round( ($this->getAwaitingEvidencesCount()/$this->min_req_evidences)*100 );
        $result = round( ($this->awaiting_evidences/$this->min_req_evidences)*100 );
        return $result > 100 ? 100 : $result ;
    }

    public function getProgressPercentage() // green bar
    {
        // $result = round( ($this->getAcceptedEvidencesCount()/$this->min_req_evidences)*100 );
        $result = round( ($this->accepted_evidences/$this->min_req_evidences)*100 );
        return $result > 100 ? 100 : $result ;
    }

    public function getProgressPercentageGreen() // green bar
    {
        // $result = $this->assessor_signoff == 1 ? round( ($this->getAcceptedEvidencesCount()/$this->min_req_evidences)*100 ) : 0;
        $result = $this->assessor_signoff == 1 ? round( ($this->accepted_evidences/$this->min_req_evidences)*100 ) : 0;
        return $result > 100 ? 100 : $result ;
    }

    public function getProgressPercentageBlue() // blue bar
    {
        // $result = $this->assessor_signoff == 0 ? round( ($this->getAcceptedEvidencesCount()/$this->min_req_evidences)*100 ) : 0;
        $result = $this->assessor_signoff == 0 ? round( ($this->accepted_evidences/$this->min_req_evidences)*100 ) : 0;
        return $result > 100 ? 100 : $result ;
    }

    public function isReadyForSignOff()
    {
        return $this->assessor_signoff == 0 && $this->min_req_evidences <= $this->getAcceptedEvidencesCount();
        // return $this->assessor_signoff == 0 && $this->min_req_evidences <= $this->accepted_evidences;
    }

    public function isKsb()
    {
        return in_array($this->category, [PcCategoryLookup::KSB_KNOWLEDGE, PcCategoryLookup::KSB_SKILLS, PcCategoryLookup::KSB_BEHAVIOURS]);
    }
}
