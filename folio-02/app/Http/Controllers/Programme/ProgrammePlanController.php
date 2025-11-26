<?php

namespace App\Http\Controllers\Programme;


use App\Http\Controllers\Controller;
use App\Models\Programmes\Programme;
use Illuminate\Http\Request;

class ProgrammePlanController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function edit (Programme $programme)
    {
	    $this->authorize('update', $programme);

        return view('programmes.training_plan_template', compact('programme'));
    }

    
    public function update(Request $request, Programme $programme)
    {
        $this->authorize('update', $programme);
        
        if(!isset($request->plans) || !is_array($request->plans))
        {
            return back();
        }

        $programme->training_plans()->delete();
        $start = 0;
        foreach($request->plans AS $plan)
        {
            if(!isset($plan["plan_units"]))
                continue;

            $plan_units1 = array_map(function($plan_units) {
                return $plan_units["id"];
            }, $plan["plan_units"]);

            if(count($plan_units1) == 1 && $plan_units1[0] == "empty")
                continue;

            $programme->training_plans()->create([
                'plan_number' => ++$start,
                'start_date' => $plan["start_date"],
                'end_date' => $plan["end_date"],
                'plan_units' => json_encode($plan_units1),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Training plans are updated.'
        ]);
    }

    public function updateNumberOfTrainingPlans(Request $request, Programme $programme)
    {
        $this->authorize('update', $programme);
        
        $validator = Validator::make($request->all(), [
            'training_plan_start_date' => ['required', 'date', 'after_or_equal:'.$programme->start_date, 'before:'.$programme->end_date],
            'training_plan_end_date' => ['required', 'date', 'after:training_plan_start_date', 'before:'.$programme->end_date],
        ], [
            'training_plan_start_date.after_or_equal' => 'Training plan start date must be on or after programme start date.',
            'training_plan_start_date.before' => 'Training plan start date must be before programme end date.',
            'training_plan_end_date.after' => 'Training plan end date must be after programme start date.',
            'training_plan_end_date.before' => 'Training plan end date must be before programme end date.',
        ]);

        if ($validator->fails())
        {
            return back()->withErrors($validator)->withInput();
        }
        $start = $programme->training_plans->count() + 1;
        for($i = 1; $i <= $request->number_of_training_plans; $i++)
        {
            $plan_number = $programme->training_plans->count()+1;
            $programme->training_plans()->create([
                'plan_number' => $plan_number,
                'start_date' => $request->training_plan_start_date,
                'end_date' => $request->training_plan_end_date,
                'plan_units' => json_encode([]),
            ]);
        }

        return route('programmes.training_plans.edit', $programme);
    }

    public function updateTrainingPlanDates(Request $request, Programme $programme)
    {
        $plan = \App\Models\Programmes\ProgrammeTrainingPlan::findOrFail($request->id);
        $plan->start_date = $request->training_plan_start_date;
        $plan->end_date = $request->training_plan_end_date;
        $plan->save();

        return back();
    }

}