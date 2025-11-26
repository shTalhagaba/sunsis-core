<?php

namespace App\Http\Controllers\Programme;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Programmes\Programme;
use App\Models\Programmes\ProgrammeQualification;
use App\Models\Programmes\ProgrammeQualificationUnit;
use App\Models\Programmes\ProgrammeQualificationUnitPC;
use Illuminate\Http\Response;

class ProgrammeQualificationUnitPcController extends Controller
{
    public function destroy(Programme $programme, ProgrammeQualification $qualification, ProgrammeQualificationUnit $unit, ProgrammeQualificationUnitPC $pc)
    {
        $this->verify($programme, $qualification, $unit, $pc);

        $pc->delete();

        if(request()->ajax())
        {
            return response()->json([
                'success' => true,
                'message' => 'Performance criteria is deleted.'
            ]);
        }

        return back()->with(['alert-success', 'Performance criteria is deleted.']);
    }

    private function verify(Programme $programme, ProgrammeQualification $qualification, ProgrammeQualificationUnit $unit, ProgrammeQualificationUnitPC $pc = null)
    {
        $this->authorize('update', $programme);

        abort_if($qualification->programme_id !== $programme->id, Response::HTTP_BAD_REQUEST );
        abort_if($unit->programme_qualification_id !== $qualification->id, Response::HTTP_BAD_REQUEST );
        if(!is_null($pc))
        {
            abort_if($pc->programme_qualification_unit_id !== $unit->id, Response::HTTP_BAD_REQUEST );
        }
    }
}
