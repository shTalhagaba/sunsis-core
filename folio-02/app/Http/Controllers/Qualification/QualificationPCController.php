<?php

namespace App\Http\Controllers\Qualification;

use App\Models\Qualifications\Qualification;
use App\Models\Qualifications\QualificationUnit;
use App\Models\Qualifications\QualificationUnitPC;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class QualificationPCController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }
    
    public function create(Qualification $qualification, QualificationUnit $unit)
    {
        $this->verify($qualification, $unit);

    	$types = QualificationUnitPC::getDDLEvidenceTypes();
        $categories = QualificationUnitPC::getDDLEvidenceCategories();
        $ams = QualificationUnitPC::getDDLEvidenceAssessmentMethods();

    	return view('qualifications.units.pcs.create', compact('qualification', 'unit', 'types', 'categories', 'ams'));
    }

    public function edit(Qualification $qualification, QualificationUnit $unit, QualificationUnitPC $pc)
    {
        $this->verify($qualification, $unit, $pc);

    	$types = QualificationUnitPC::getDDLEvidenceTypes();
        $categories = QualificationUnitPC::getDDLEvidenceCategories();
        $ams = QualificationUnitPC::getDDLEvidenceAssessmentMethods();

    	return view('qualifications.units.pcs.edit', compact('qualification', 'unit', 'pc', 'types', 'categories', 'ams'));
    }

    public function store(Qualification $qualification, QualificationUnit $unit, Request $request)
    {
        $this->verify($qualification, $unit);

        $this->isValidPost($request);

        $unit->pcs()->create([
            'reference' => $request->reference,
            'title' => \Str::limit($request->title, 850, ''),
            'category' => $request->category,
            'min_req_evidences' => $request->min_req_evidences,
            'description' => \Str::limit($request->description, 500, ''),
        ]);

        if(isset($request->save_and_add_new))
            return redirect()->route('qualifications.units.pcs.create', [$qualification, $unit]);

        if(isset($request->save_and_go_back))
            return redirect()->route('qualifications.show', $qualification);

    }

    public function update(Qualification $qualification, QualificationUnit $unit, $id, Request $request)
    {
        $this->verify($qualification, $unit);

        $this->isValidPost($request);

        $pc = QualificationUnitPC::findOrFail($id);

        $pc->update([
            'reference' => $request->reference,
            'title' => \Str::limit($request->title, 850, ''),
            'category' => $request->category,
            'min_req_evidences' => $request->min_req_evidences,
            'description' => \Str::limit($request->description, 500, ''),
        ]);

        if(isset($request->save_and_add_new))
            return redirect()->route('qualifications.units.pcs.create', [$qualification, $unit]);

        if(isset($request->save_and_go_back))
            return redirect()->route('qualifications.show', $qualification);

    }

    private function isValidPost(Request $request)
    {
        switch($request->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            case 'PUT':
            case 'PATCH':
            {
                return $request->validate([
                  'reference' => 'required|min:4|max:10|unique:qualification_unit_pcs,reference,'.$request->id.',id,unit_id,'.$request->unit_id,
                  'title' => 'required|max:850',
                ]);
            }
            default:break;
        }
    }

    public function destroy(Qualification $qualification, QualificationUnit $unit, QualificationUnitPC $pc)
    {
        $this->verify($qualification, $unit, $pc);

        $pc->delete();

        if(request()->ajax())
        {
            return response()->json([
                'success' => true,
                'message' => 'Performance criteria is deleted.'
            ]);
        }

        return back()->with(['alert-success' => 'Performance criteria is deleted.']);
    }

    private function verify(Qualification $qualification, QualificationUnit $unit, QualificationUnitPC $pc = null)
    {
        $this->authorize('update', $qualification);

        abort_if($unit->qualification_id !== $qualification->id, Response::HTTP_BAD_REQUEST );
        if(!is_null($pc))
        {
            abort_if($pc->unit_id !== $unit->id, Response::HTTP_BAD_REQUEST );
        }
    }
}
