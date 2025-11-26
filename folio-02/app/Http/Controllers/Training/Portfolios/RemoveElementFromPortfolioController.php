<?php

namespace App\Http\Controllers\Training\Portfolios;

use App\Http\Controllers\Controller;
use App\Models\Training\Portfolio;
use App\Models\Training\PortfolioUnit;
use App\Models\Training\TrainingRecord;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class RemoveElementFromPortfolioController extends Controller
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
            // action to remove unit from training record's portfolio
            // element_id here represents the id of portfolio_units table id
            $portfolioUnit = PortfolioUnit::find($elementId);
            if(is_null($portfolioUnit))
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request parameters'
                ], Response::HTTP_BAD_REQUEST);
            }

            $portfolioUnit->delete();
            $message = 'Unit has been removed.';
        }

        return response()->json([
            'success' => true,
            'message' => $message ?? '',
        ]);
    }


}