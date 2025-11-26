<?php

namespace App\Http\Controllers\Programme;

use App\Exports\ProgrammeQualificationsExport;
use App\Exports\ProgrammeSingleQualificationExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Programmes\Programme;
use App\Models\Programmes\ProgrammeQualification;
use App\Models\Qualifications\Qualification;
use App\Services\Programmes\ProgrammeService;
use App\Services\Qualifications\QualificationToProgrammeService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ProgrammeQualificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function manageQualifications(Programme $programme)
    {
        $this->authorize('update', $programme);

        $qualificationOwners = DB::table('lookup_qual_owners')
            ->select("lookup_qual_owners.owner_org_name", "lookup_qual_owners.owner_org_rn")
            ->join('qualifications', 'qualifications.owner_org_rn', '=', 'lookup_qual_owners.owner_org_rn')
            ->distinct()
            ->orderBy('owner_org_name', 'asc')
            ->pluck('owner_org_name', 'owner_org_rn')->toArray();

        $alreadyAddedQualifications = $programme->qualifications->pluck('qan')->toArray();

        $activeQualifications = [];
        foreach($qualificationOwners AS $key => $value)
        {
            $_q = Qualification::select(DB::raw("CONCAT(qan, ' - ', title) AS qual_title"), "id")
                ->active()
                ->where('owner_org_rn', $key)
                ->orderBy('qual_title', 'asc')
                ->whereNotIn('qan', $alreadyAddedQualifications)
                ->pluck('qual_title', 'id')->toArray();

            if(count($_q) > 0)
                $activeQualifications[$value] = $_q;
        }

        return view('programmes.qualifications.add_remove_qualifications', compact('programme', 'activeQualifications'));
    }

    public function add(Programme $programme, QualificationToProgrammeService $qualificationToProgrammeService, Request $request)
    {
        $this->authorize('update', $programme);

        $validQualificationIds = Qualification::pluck('id')->toArray();
        $validQualificationIds = implode(",", $validQualificationIds);
        $programmeExisingQualificationIds = $programme->qualifications()->pluck('tbl_qualification_id')->toArray();
        $programmeExisingQualificationIds = implode(',', $programmeExisingQualificationIds);

        $request->validate([
            'programme_id' => 'required|numeric|in:'.$programme->id,
            'qualification_to_add' => 'required|numeric|in:'.$validQualificationIds.'|not_in:'.$programmeExisingQualificationIds,
        ], [
            'qualification_to_add.required' => 'Qualification is required',
            'qualification_to_add.not_in' => 'Qualification is already part of the programme',
            'programme_id.required' => 'Programme is required',
        ]);

        abort_if($programme->id != $request->programme_id, Response::HTTP_BAD_REQUEST);

        $qualification = Qualification::findOrFail($request->qualification_to_add);

        DB::beginTransaction();
        try
        {
            $programmeQualification = $qualificationToProgrammeService->copyQualificationToProgramme($qualification, $programme);
    
            DB::commit();
        }
        catch(Exception $ex)
        {
            DB::rollBack();
            throw new Exception("Error while adding qualification into the programme." . PHP_EOL . $ex->getMessage());
        }

        return back()
            ->with(['alert-success' => 'Qualification is added successfully into the programme.']);
    }

    public function remove(Programme $programme, ProgrammeService $programmeService, Request $request)
    {
        $this->authorize('update', $programme);

        $request->validate([
            'programme_id' => 'required|numeric|in:'.$programme->id,
            'qualification_to_remove' => 'required|numeric|in:'.$programme->qualifications()->pluck('id')->implode(','),
        ], [
            'qualification_to_remove.required' => 'No qualification is specified to remove',
            'programme_id.required' => 'Missing querystring argument: programme_id',
        ]);

        abort_if($programme->id != $request->programme_id, Response::HTTP_BAD_REQUEST);

        // validation to make sure that the programme qualification pcs are not used in delivery plan or elsewhere
        $pcsOfQualificationToRemove = DB::table('programme_qualification_unit_pcs')
            ->join('programme_qualification_units', 'programme_qualification_units.id', '=', 'programme_qualification_unit_pcs.programme_qualification_unit_id')
            ->where('programme_qualification_units.programme_qualification_id', $request->qualification_to_remove)
            ->get(['programme_qualification_unit_pcs.id']);

        $programmeSessionPcs = DB::table('programme_dp_sessions')
            ->where('programme_id', $programme->id)
            ->select('session_pcs')
            ->get();

        foreach($programmeSessionPcs AS $sessionPcs)
        {
            $pcs = json_decode($sessionPcs->session_pcs);
            if(!is_array($pcs)) $pcs = [];
            if(count(array_intersect($pcs, $pcsOfQualificationToRemove->pluck('id')->toArray())) > 0)
            {
                return back()
                    ->with(['alert-danger' => 'Qualification cannot be removed as it is used in delivery plan.']);
            }
        }

        DB::beginTransaction();
        try
        {
            $programmeService->removeQualification($programme, $request->qualification_to_remove);

            DB::commit();
        }
        catch(\Exception $ex)
        {
            DB::rollBack();
            throw new \Exception("Error while removing qualification into the programme." . PHP_EOL . $ex->getMessage());
        }

        return back()
            ->with(['alert-success' => 'Qualification is removed successfully from the programme.']);

    }

    public function saveManageQualifications(Programme $programme, Request $request)
    {
        $this->authorize('update', $programme);

        $request->validate([
            'programme_id' => 'required|numeric|in:'.$programme->id,
            'main_aim' => 'required|numeric',
            'data_to_update' => 'required',
        ], [
            'data_to_update.required' => 'No details provided',
        ]);

        parse_str($request->data_to_update, $parsed_data);
        abort_if(!isset($parsed_data['data']), Response::HTTP_UNAUTHORIZED);

        foreach($parsed_data['data'] AS $data_item)
        {
            $programe_qualification = ProgrammeQualification::findOrFail($data_item['qualification_id']);
            $programe_qualification->update([
                'sequence' => $data_item['sequence'],
                'proportion' => $data_item['proportion'],
                'duration' => $data_item['duration'],
                'offset' => $data_item['offset'],
                'main' => $programe_qualification->id == $request->main_aim ? 1 : 0,
            ]);
        }

        return back()
            ->with(['alert-success' => 'Qualifications details are updated successfully.']);
    }

    public function export(Programme $programme)
    {
	    $this->authorize('show', $programme);

        return Excel::download(new ProgrammeQualificationsExport($programme), 'Programme Qualifications.xlsx');
    }

    public function exportSingleQualification(Programme $programme, ProgrammeQualification $qualification)
    {
	    $this->authorize('show', $programme);

        return Excel::download(new ProgrammeSingleQualificationExport($qualification), 'Programme Qualification.xlsx');
    }
}
