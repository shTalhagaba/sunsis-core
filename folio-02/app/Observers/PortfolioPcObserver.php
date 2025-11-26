<?php

namespace App\Observers;

use App\Models\Training\PortfolioPC;

class PortfolioPcObserver
{
    public function saved(PortfolioPC $pc)
    {
        $this->updateUnitSignoffStatus($pc);
    }

    public function updated(PortfolioPC $pc)
    {
        $this->updateUnitSignoffStatus($pc);
    }

    private function updateUnitSignoffStatus(PortfolioPC $pc)
    {
        $portfolioUnit = $pc->unit;
        $portfolioUnit->update([
            'assessor_signoff' => $portfolioUnit->pcs()->signOffStatus(false)->count() === 0
        ]);
    }
}
