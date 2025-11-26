<?php

namespace App\Observers;

use App\Models\Training\TrainingRecordEvidence;

class TrainingRecordEvidenceObserver
{
    public function saved(TrainingRecordEvidence $evidence)
    {
        foreach ($evidence->mapped_pcs as $pc) 
        {
            $pc->update([
                'accepted_evidences' => $pc->getAcceptedEvidencesCount(),
                'awaiting_evidences' => $pc->getAwaitingEvidencesCount(),
            ]);
        }
    }
}
