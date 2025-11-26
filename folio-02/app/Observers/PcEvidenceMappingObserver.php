<?php

namespace App\Observers;

use App\Models\Training\PCEvidenceMapping;
use App\Models\Training\PortfolioPC;

class PcEvidenceMappingObserver
{
    public function saved(PCEvidenceMapping $mapping)
    {
        $pc = PortfolioPC::findOrFail($mapping->portfolio_pc_id);

        $pc->update([
            'accepted_evidences' => $pc->getAcceptedEvidencesCount(),
            'awaiting_evidences' => $pc->getAwaitingEvidencesCount(),
        ]);
    }
}
