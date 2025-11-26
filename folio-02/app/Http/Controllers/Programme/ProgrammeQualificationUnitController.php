<?php

namespace App\Http\Controllers\Programme;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

use App\Models\Programmes\Programme;
use App\Models\Programmes\ProgrammeQualification;
use App\Models\Programmes\ProgrammeQualificationUnit;
use App\Models\Programmes\ProgrammeQualificationUnitPC;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProgrammeQualificationUnitController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    private function verify(Programme $programme, ProgrammeQualification $qualification, ProgrammeQualificationUnit $unit = null, ProgrammeQualificationUnitPC $pc = null)
    {
        $this->authorize('update', $programme);

        abort_if( ! in_array($qualification->id, $programme->qualifications()->pluck('id')->toArray()), Response::HTTP_BAD_REQUEST, 'Bad Request' );

        if(! is_null($unit))
        {
            abort_if( ! in_array($unit->id, $qualification->units()->pluck('id')->toArray()), Response::HTTP_BAD_REQUEST, 'Bad Request' );
        }

        if(! is_null($pc))
        {
            abort_if( ! in_array($pc->id, $unit->pc()->pluck('id')->toArray()), Response::HTTP_BAD_REQUEST, 'Bad Request' );
        }
    }
    
    public function create(Programme $programme, ProgrammeQualification $qualification)
    {
        $this->verify($programme, $qualification);

    	$savedRefs = $qualification->units->pluck('unique_ref_number')->toArray() ;
    	$savedOwnerRefs = $qualification->units->pluck('unit_owner_ref')->toArray() ;

    	return view('programmes.qualifications.units.create', compact('programme', 'qualification', 'savedRefs', 'savedOwnerRefs'));
    }

    public function store(Programme $programme, ProgrammeQualification $qualification, Request $request)
    {
        $this->verify($programme, $qualification);

        $qualification->load('units');

        $this->isValidPost($request, $qualification);

        $incrementUnitsCount = count($qualification->units)+1;

        DB::beginTransaction();
        try
        {
            $unit = $qualification->units()->create([
                'unit_sequence' => $request->input('unit_sequence', $incrementUnitsCount),
                'unit_owner_ref' => $request->input('unit_owner_ref', 'Ref' . $incrementUnitsCount),
                'unique_ref_number' => $request->unique_ref_number,
                'title' => $request->title,
                'unit_group' => $request->unit_group,
                'glh' => $request->glh,
                'unit_credit_value' => $request->unit_credit_value,
                'learning_outcomes' => $request->learning_outcomes,
                'system_code' => $qualification->qan . '|' . $request->unique_ref_number,
            ]);
    
            $this->refreshUnitPcs($qualification, $unit, $request);
            
            DB::commit();
        }
        catch(Exception $ex)
        {
            DB::rollBack();
            throw new Exception($ex->getMessage());
        }

        return redirect()
            ->route('programmes.show', $programme)
            ->with(['alert-success' => 'Unit has been added successfully.']);
    }

    public function edit(Programme $programme, ProgrammeQualification $qualification, ProgrammeQualificationUnit $unit)
    {
        $this->verify($programme, $qualification, $unit);

        $savedRefs = $qualification->units->where('unique_ref_number', '!=', $unit->unique_ref_number)->pluck('unique_ref_number')->toArray() ;
        $savedOwnerRefs = $qualification->units->where('unit_owner_ref', '!=', $unit->unit_owner_ref)->pluck('unit_owner_ref')->toArray() ;
        $otherUnits = $qualification->units()->where('id', '!=', $unit->id)->get();

    	return view('programmes.qualifications.units.edit', compact('programme', 'qualification', 'unit', 'savedRefs', 'savedOwnerRefs', 'otherUnits'));
    }

    public function update(Programme $programme, ProgrammeQualification $qualification, ProgrammeQualificationUnit $unit, Request $request)
    {
        $this->verify($programme, $qualification, $unit);

        $this->isValidPost($request, $qualification, $unit);

        DB::beginTransaction();
        try
        {
            $unit->update([
                'unit_sequence' => $request->input('unit_sequence'),
                'unit_owner_ref' => $request->input('unit_owner_ref'),
                'unique_ref_number' => $request->unique_ref_number,
                'title' => $request->title,
                'unit_group' => $request->unit_group,
                'glh' => $request->glh,
                'unit_credit_value' => $request->unit_credit_value,
                'learning_outcomes' => $request->learning_outcomes,
                'system_code' => $qualification->qan . '|' . $request->unique_ref_number,
            ]);
    
            $numberOfPcs = $request->input('number_of_pcs', 20);
            foreach(range(1, $numberOfPcs) AS $counter)
            {
                $prefix = 'pc_' . $counter . '_';
                $savedPcId = $request->input($prefix . 'savedId');

                if(is_null( $request->input($prefix . 'title') ) || trim($request->input($prefix . 'title')) == '')
                {
                    // If there is no title, then skip this iteration. However, if there is a saved ID, then delete it.
                    if(!is_null($savedPcId))
                    {
                        ProgrammeQualificationUnitPC::find($savedPcId)->delete();
                    }

                    continue;
                }

                $pc = ProgrammeQualificationUnitPC::find($request->input($prefix . 'savedId'));
                if(is_null($pc))
                {
                    ProgrammeQualificationUnitPC::create([
                        'programme_qualification_unit_id' => $unit->id,
                        'pc_sequence' => $request->input($prefix . 'sequence'),
                        'reference' => $request->input($prefix . 'reference'),
                        'category' => $request->input($prefix . 'category'),
                        'title' => $request->input($prefix . 'title'),
                        'min_req_evidences' => $request->input($prefix . 'min_req_evidences'),
                        'description' => $request->input($prefix . 'description'),
                        'system_code' => $qualification->qan . '|' . $unit->unique_ref_number . '|' . $request->input($prefix . 'reference'),
                    ]);
                }
                else
                {
                    $pc->update([
                        'pc_sequence' => $request->input($prefix . 'sequence'),
                        'reference' => $request->input($prefix . 'reference'),
                        'category' => $request->input($prefix . 'category'),
                        'title' => $request->input($prefix . 'title'),
                        'min_req_evidences' => $request->input($prefix . 'min_req_evidences'),
                        'description' => $request->input($prefix . 'description'),
                        'system_code' => $qualification->qan . '|' . $unit->unique_ref_number . '|' . $request->input($prefix . 'reference'),
                    ]);
                }
            }
            // $unit->pcs()->delete();
            // $this->refreshUnitPcs($qualification, $unit, $request);

            DB::commit();
        }
        catch(Exception $ex)
        {
            DB::rollBack();
            throw new Exception($ex->getMessage());
        }

        return redirect()
            ->route('programmes.show', $programme)
            ->with(['alert-success' => 'Unit has been updated successfully.']);
    }


    public function destroy(Programme $programme, ProgrammeQualification $qualification, ProgrammeQualificationUnit $unit)
    {
        $this->verify($programme, $qualification, $unit);

        // validation to make sure that the unit pcs are not used in delivery plan or elsewhere
        $pcsOfUnitToRemove = $unit->pcs()->pluck('id')->toArray();

        $programmeSessionPcs = DB::table('programme_dp_sessions')
            ->where('programme_id', $programme->id)
            ->select('session_pcs')
            ->get();

        foreach($programmeSessionPcs AS $sessionPcs)
        {
            $pcs = json_decode($sessionPcs->session_pcs);
            if(!is_array($pcs)) $pcs = [];
            if(count(array_intersect($pcs, $pcsOfUnitToRemove)) > 0)
            {
                return back()
                    ->with(['alert-danger' => 'Unit cannot be removed as its pcs are used in delivery plan.']);
            }
        }


        $unit->delete();

        return redirect()
            ->back()
            ->with(['alert-success' => 'Unit has been removed successfully.']);
    }

    private function isValidPost(Request $request, ProgrammeQualification $qualification, ProgrammeQualificationUnit $unit = null)
    {
        $messages = [];
        $unavailableOwnerRefs = collect();
        $unavailableUniqueRefs = collect();
        if(strtolower($request->method()) === 'post')
        {
            $unavailableOwnerRefs = $qualification->units->pluck('unit_owner_ref')->implode(',');
            $unavailableUniqueRefs = $qualification->units->pluck('unique_ref_number')->implode(',');
        }
        elseif(in_array(strtolower($request->method()), ['put', 'patch']))
        {
            $unavailableOwnerRefs = $qualification->units()->where('programme_qualification_units.id', '!=', $unit->id)->pluck('unit_owner_ref')->implode(',');
            $unavailableUniqueRefs = $qualification->units()->where('programme_qualification_units.id', '!=', $unit->id)->pluck('unique_ref_number')->implode(',');
        }

        return $request->validate([
            'unit_sequence' => 'nullable|numeric',
            'unit_owner_ref' => 'required|max:15|not_in:' . $unavailableOwnerRefs,
            'unique_ref_number' => 'required|max:15|not_in:' . $unavailableUniqueRefs,
            'title' => 'required|max:850',
            'unit_group' => 'required|numeric',
            'glh' => 'required|numeric',
            'unit_credit_value' => 'required|numeric',
            'learning_outcomes' => 'nullable|string',
          ], $messages);    	
    }

    private function refreshUnitPcs(ProgrammeQualification $qualification, ProgrammeQualificationUnit $unit, Request $request)
    {
        $now = now();
        $pcsBatchInsert = [];
        $numberOfPcs = $request->input('number_of_pcs', 20);
        foreach(range(1, $numberOfPcs) AS $counter)
        {
            $prefix = 'pc_' . $counter . '_';
            if(is_null( $request->input($prefix . 'title') ) || trim($request->input($prefix . 'title')) == '')
            {
                continue;
            }

            $pcsBatchInsert[] = [
                'programme_qualification_unit_id' => $unit->id,
                'pc_sequence' => $request->input($prefix . 'sequence'),
                'reference' => $request->input($prefix . 'reference'),
                'category' => $request->input($prefix . 'category'),
                'title' => $request->input($prefix . 'title'),
                'min_req_evidences' => $request->input($prefix . 'min_req_evidences'),
                'description' => $request->input($prefix . 'description'),
                'system_code' => $qualification->qan . '|' . $unit->unique_ref_number . '|' . $request->input($prefix . 'reference'),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('programme_qualification_unit_pcs')->insert($pcsBatchInsert);
    }
}
