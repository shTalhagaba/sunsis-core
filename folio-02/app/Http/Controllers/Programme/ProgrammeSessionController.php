<?php

namespace App\Http\Controllers\Programme;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lookups\PcCategoryLookup;
use App\Models\Programmes\Programme;
use App\Models\Programmes\ProgrammeDeliveryPlanSession;
use App\Rules\UniqueProgrammeSessionNumberRule;
use Illuminate\Support\Facades\DB;

class ProgrammeSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function create(Programme $programme)
	{
	    $this->authorize('create', Programme::class);

        $selectedElements = [];
	$isTemplate = request()->query('is_template') == 1 ? 1 : 0;
        
	return view('programmes.sessions.create', compact('programme', 'selectedElements', 'isTemplate'));
    }

    public function store(Programme $programme, Request $request)
	{
	    $this->authorize('create', Programme::class);

        $this->validateRequest($request, $programme);

        $programme->sessions()
            ->create([
                'session_number' => $request->input('session_number'),
                'session_sequence' => $request->input('session_sequence'),
                'session_details_1' => preg_replace('/[^\x00-\x7F]/', '', $request->input('session_details_1')),
                'session_details_2' => preg_replace('/[^\x00-\x7F]/', '', $request->input('session_details_2')),
                'is_template' => $request->input('is_template', 0),
                'session_pcs' => json_encode($request->input('elements')),
            ]);

        return redirect()->route('programmes.show', $programme);
    }

    public function edit(Programme $programme, ProgrammeDeliveryPlanSession $session)
    {
        $this->authorize('create', Programme::class);

        $selectedElements = json_decode($session->session_pcs);
        $selectedElements = !is_array($selectedElements) ? [] : $selectedElements;
	$selectedElementsUnitIds = DB::table('programme_qualification_units')
            ->join('programme_qualification_unit_pcs', 'programme_qualification_units.id', '=', 'programme_qualification_unit_pcs.programme_qualification_unit_id')
            ->whereIn('programme_qualification_unit_pcs.id', $selectedElements)
            ->distinct()
            ->pluck('programme_qualification_units.id')
            ->toArray();

        $isTemplate = request()->query('is_template') == 1 ? 1 : 0;

	return view('programmes.sessions.edit', compact('programme', 'session', 'selectedElements', 'isTemplate', 'selectedElementsUnitIds'));
    }

    public function update(Programme $programme, ProgrammeDeliveryPlanSession $session, Request $request)
    {
        $this->authorize('create', Programme::class);

        $this->validateRequest($request, $programme, $session->id);

        $session->update([
            'session_number' => $request->input('session_number'),
            'session_sequence' => $request->input('session_sequence'),
            'session_details_1' => preg_replace('/[^\x00-\x7F]/', '', $request->input('session_details_1')),
            'session_details_2' => preg_replace('/[^\x00-\x7F]/', '', $request->input('session_details_2')),
            'session_pcs' => json_encode($request->input('elements')),
        ]);

        return redirect()->route('programmes.show', $programme);
    }

    public function destroy(Programme $programme, ProgrammeDeliveryPlanSession $session)
    {
        $this->authorize('create', Programme::class);

        $session->delete();

        return redirect()->route('programmes.show', $programme)->with(['alert-success' => 'Session is deleted successfully.']);
    }

    private function getPcs(Programme $programme)
    {
        $elements = DB::table('programme_qualification_unit_pcs')
            ->join('programme_qualification_units', 'programme_qualification_unit_pcs.programme_qualification_unit_id', '=', 'programme_qualification_units.id')
            ->join('programme_qualifications', 'programme_qualification_units.programme_qualification_id', '=', 'programme_qualifications.id')
            ->where('programme_qualifications.programme_id', $programme->id)
            //->where('programme_qualifications.main', true)
            //->whereIn('programme_qualification_unit_pcs.category', [PcCategoryLookup::KSB_KNOWLEDGE, PcCategoryLookup::KSB_SKILLS, PcCategoryLookup::KSB_BEHAVIOURS])
            ->select(
                'programme_qualification_unit_pcs.id',
                DB::raw('CONCAT("[", programme_qualification_units.unique_ref_number, "] ", programme_qualification_unit_pcs.title) AS title'), 
                'programme_qualification_unit_pcs.delivery_hours', 
                'programme_qualification_unit_pcs.category',
                'programme_qualifications.title AS prog_qual_title'
            )
            ->orderBy('programme_qualifications.sequence')
            ->orderBy('programme_qualification_units.unit_sequence')
            ->orderBy('programme_qualification_unit_pcs.pc_sequence')
            ->get();

        return $elements;
    }

    private function validateRequest(Request $request, Programme $programme, $sessionId = null)
    {
        $request->validate([
            'session_number' => [
                'required',
                'string',
                new UniqueProgrammeSessionNumberRule($programme->id, $sessionId),
            ],
            'session_sequence' => 'required|numeric',
            'session_details_1' => 'nullable|max:1600',
            'session_details_2' => 'nullable|max:1600',
        ]);
    }
}
