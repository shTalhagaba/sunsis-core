<?php

namespace App\Http\Controllers\Qualification;

use Validator;
use App\Models\Qualifications\Qualification;
use App\Models\Qualifications\QualificationUnit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQualificationUnitRequest;
use App\Services\Qualifications\QualificationService;
use Illuminate\Http\Response;

class QualificationUnitController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }
    
    public function create(Qualification $qualification)
    {
        $this->authorize('update', $qualification);

        $qualification->load([
            'units' => function ($query) {
                $query->orderBy('unit_sequence');
            },
            'units.pcs' => function ($query) {
                $query->orderBy('pc_sequence');
            },
        ]);

        $savedRefs = $qualification->units->pluck('unique_ref_number')->toArray() ;
    	$savedOwnerRefs = $qualification->units->pluck('unit_owner_ref')->toArray() ;

    	return view('qualifications.units.create', compact('qualification', 'savedRefs', 'savedOwnerRefs'));
    }

    public function store(Qualification $qualification, StoreQualificationUnitRequest $request, QualificationService $qualificationService)
    {
        $this->authorize('update', $qualification);

        $unit = $qualificationService->createUnit($qualification, $request->all());

        return redirect()
            ->route('qualifications.show', $qualification)
            ->with(['alert-success' => 'Unit has been created successfully.']);
    }

    public function edit(Qualification $qualification, QualificationUnit $unit)
    {
        $this->authorize('update', $qualification);
        abort_if($unit->qualification_id !== $qualification->id, Response::HTTP_UNAUTHORIZED );

        $qualification->load([
            'units' => function ($query) use ($unit){
                $query->where('id', '!=', $unit->id)->orderBy('unit_sequence');
            },
            'units.pcs' => function ($query) {
                $query->orderBy('pc_sequence');
            },
        ]);
    	$savedRefs = $qualification->units->where('unique_ref_number', '!=', $unit->unique_ref_number)->pluck('unique_ref_number')->toArray() ;
    	$savedOwnerRefs = $qualification->units->where('unit_owner_ref', '!=', $unit->unit_owner_ref)->pluck('unit_owner_ref')->toArray() ;

    	return view('qualifications.units.edit', compact('qualification', 'unit', 'savedRefs', 'savedOwnerRefs'));
    }

    public function update(Qualification $qualification, QualificationUnit $unit, StoreQualificationUnitRequest $request, QualificationService $qualificationService)
    {
        $this->authorize('update', $qualification);
        abort_if($unit->qualification_id !== $qualification->id, Response::HTTP_UNAUTHORIZED );

        $unit = $qualificationService->updateUnit($unit, $request->all());

        return redirect()
            ->route('qualifications.show', $qualification)
            ->with(['alert-success' => 'Unit has been updated successfully.']);
    }

    public function destroy(Qualification $qualification, QualificationUnit $unit, QualificationService $qualificationService)
    {
        $this->authorize('delete', $qualification);
        abort_if($unit->qualification_id !== $qualification->id, Response::HTTP_UNAUTHORIZED );

        $deleted = $qualificationService->deleteUnit($unit);

        if( request()->ajax() )
        {
            return response()->json([
                'success' => $deleted ? true : false,
                'message' => $deleted ? 'Qualification unit is deleted successfully.' : 'Something went wrong, delete aborted.'
            ]);
        }

        if(! $deleted)
        {
            back()->with(['alert-error' => 'Something went wrong, delete aborted.']);            
        }

        return redirect()
            ->route('qualifications.show', $qualification)
            ->with(['alert-success' => 'Qualification Unit is deleted successfully.']);
    }

    public function createMultiple(Qualification $qualification)
    {
        $this->authorize('update', $qualification);

        $qualification->load([
            'units' => function ($query) {
                $query->orderBy('unit_sequence');
            },
            'units.pcs' => function ($query) {
                $query->orderBy('pc_sequence');
            },
        ]);
        $saved_references = $qualification->units->pluck('unique_ref_number')->toArray() ;
    	return view('qualifications.units.createMultiple', compact('qualification', 'saved_references'));
    }

    public function storeMultiple(Qualification $qualification, Request $request)
    {
        $this->authorize('update', $qualification);

        $messages = [
            'unique_ref_number.max' => 'The unique reference must be maximum of 10 characters.',
            'unit_owner_ref.required' => 'Owner reference is required if you input unique reference.',
            'title.required' => 'Title is required if you input unique reference.',
            'glh.required' => 'GLH is required if you input unique reference.',
            'unit_credit_value.required' => 'Credit value is required if you input unique reference.',
        ];

        $start = intval($qualification->units()->count())+1;
        $end = intval($qualification->units()->count())+50;
        for($i = $start; $i <= $end; $i++)
        {
            $prefix = 'unit_'.$i.'_';
            if(!isset($request[$prefix.'unique_ref_number']) || trim($request[$prefix.'unique_ref_number']) == '')
                continue;

            $unit = [];
            $unit['unit_sequence'] = $request[$prefix.'unit_sequence'];
            $unit['unit_owner_ref'] = $request[$prefix.'unit_owner_ref'];
            $unit['unique_ref_number'] = $request[$prefix.'unique_ref_number'];
            $unit['title'] = \Str::limit($request[$prefix.'title'], 850, '');
            $unit['unit_group'] = $request[$prefix.'unit_group'];
            $unit['glh'] = $request[$prefix.'glh'];
            $unit['unit_credit_value'] = $request[$prefix.'unit_credit_value'];
            $unit['learning_outcomes'] = $request[$prefix.'learning_outcomes'];

            $validator = Validator::make($unit, [
                'unique_ref_number' => 'max:15',
                'unit_owner_ref' => 'required|max:15',
                'title' => 'required|max:850',
                'glh' => 'required|numeric|between:0,999',
                'unit_credit_value' => 'required|numeric|between:0,100',
            ], $messages);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $qualification->units()->create($unit);
        }

        return redirect()
            ->route('qualifications.show', $qualification)
            ->with(['alert-success' => 'Multiple units have been created successfully.']);
    }
}
