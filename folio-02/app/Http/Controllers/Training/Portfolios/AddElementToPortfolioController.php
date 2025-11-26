<?php

namespace App\Http\Controllers\Training\Portfolios;

use App\Http\Controllers\Controller;
use App\Models\Programmes\ProgrammeQualificationUnit;
use App\Models\Programmes\ProgrammeQualificationUnitPC;
use App\Models\Training\Portfolio;
use App\Models\Training\PortfolioUnit;
use App\Models\Training\TrainingRecord;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class AddElementToPortfolioController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function __invoke(TrainingRecord $training, Portfolio $portfolio, Request $request)
    {
        $this->authorize('edit', $training);
        abort_if($portfolio->tr_id != $training->id, Response::HTTP_BAD_REQUEST, 'Invalid request parameters');

        $validator = Validator::make($request->all(), [
            'training_id' => 'required|numeric|in:'.$training->id,
            'portfolio_id' => 'required|numeric|in:'.$portfolio->id,
            'element_type' => 'required|string|in:unit,pc',
            'element_id' => 'required|numeric',
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ], Response::HTTP_BAD_REQUEST);
        }

        if($portfolio->tr_id !== $training->id)
        {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request parameters'
            ], Response::HTTP_BAD_REQUEST);
        }

        $elementId = $request->element_id;
        $elementType = $request->element_type;        
                
        if($elementType === 'unit')
        {
            // action to add unit into training record's portfolio
            // element_id here represents the id of programme_qualification_units table id
            // unit from the (training_record -> programme -> qualification -> unit) is added along with its pcs to the laerner's portfolio.
            $portfolioUnit = $this->addPortfolioElement($portfolio, $elementType, $elementId);
            $message = 'Unit has been added.';
        }

        return response()->json([
            'success' => true,
            'message' => $message ?? '',
        ]);
    }

    private function addPortfolioElement(Portfolio $portfolio, $elementType, $elementId)
    {
        if($elementType === 'unit')
        {
            $programmeUnit = $this->getProgrammeUnit($elementId);
            if(!is_null($programmeUnit))
            {
                $exists = $portfolio->units()->whereSystemCode($programmeUnit->getOriginal('system_code'))->count();
                if($exists == 0)
                {
                    $portfolioUnit = $this->addUnitToPortfolio($portfolio, $programmeUnit);
                    foreach($programmeUnit->pcs AS $programmeUnitPc)
                    {
                        $portfolioPc = $this->addPcToPortfolio($portfolioUnit, $programmeUnitPc);
                    }
                }
                return $portfolioUnit;
            }
        }
        elseif($elementType === 'pc')
        {
            // should we offer this flexibility
        }
    }

    private function addUnitToPortfolio(Portfolio $portfolio, ProgrammeQualificationUnit $programmeUnit)
    {
        $portfolioUnit = $portfolio->units()->create([
            'unit_sequence' => $programmeUnit->getOriginal('unit_sequence'),
            'unit_group' => $programmeUnit->getOriginal('unit_group'),
            'unit_owner_ref' => $programmeUnit->getOriginal('unit_owner_ref'),
            'unique_ref_number' => $programmeUnit->getOriginal('unique_ref_number'),
            'title' => $programmeUnit->getOriginal('title'),
            'unit_credit_value' => $programmeUnit->getOriginal('unit_credit_value'),
            'learning_outcomes' => $programmeUnit->getOriginal('learning_outcomes'),
            'system_code' => $programmeUnit->getOriginal('system_code'),
            'glh' => $programmeUnit->getOriginal('glh'),
        ]);

        return $portfolioUnit;
    }
    
    private function addPcToPortfolio(PortfolioUnit $portfolioUnit, ProgrammeQualificationUnitPC $programmePc)
    {
        $portfolioPc = $portfolioUnit->pcs()->create([
            'pc_sequence' => $programmePc->getOriginal('pc_sequence'),
            'reference' => $programmePc->getOriginal('reference'),
            'category' => $programmePc->getOriginal('category'),
            'title' => $programmePc->getOriginal('title'),
            'min_req_evidences' => $programmePc->getOriginal('min_req_evidences'),
            'description' => $programmePc->getOriginal('description'),
            'delivery_hours' => $programmePc->getOriginal('delivery_hours'),
            'system_code' => $programmePc->getOriginal('system_code'),
        ]);

        return $portfolioPc;
    }

    private function getProgrammeUnit($unitId)
    {
        return ProgrammeQualificationUnit::find($unitId);
    }
    
    private function getProgrammeUnitPc($pcId)
    {
        return ProgrammeQualificationUnitPC::find($pcId);
    }
}