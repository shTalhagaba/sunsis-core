<?php

namespace App\Http\Controllers\Qualification;

use App\Exports\QualificationsExport;
use App\Exports\SingleQualificationExport;
use Illuminate\Http\Request;
use App\Models\Qualifications\Qualification;
use App\Http\Controllers\Controller;
use App\Filters\QualificationFilters;
use App\Http\Requests\StoreQualificationRequest;
use App\Services\Qualifications\QualificationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\UnauthorizedException;
use Maatwebsite\Excel\Facades\Excel;

class QualificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function index(Request $request, QualificationFilters $filters)
    {
        $this->authorize('index', Qualification::class);

        $qualifications = Qualification::filter($filters)
            ->withCount(['units'])
            ->paginate(session('qualifications_per_page', config('model_filters.default_per_page')));

        return view('qualifications.index', compact('qualifications', 'filters'));
    }

    public function show(Qualification $qualification)
    {
        $this->authorize('show', $qualification);

        $qualification->load([
            'units' => function ($query) {
                $query->orderBy('unit_sequence');
            },
            'units.pcs' => function ($query) {
                $query->orderBy('pc_sequence');
            },
        ]);

        $mandatoryUnits = 0;
        $optionalUnits = 0;

        $mandatoryUnits = $qualification->units->filter(function ($unit){
             return $unit->unit_group == "Mandatory";
        });
        $optionalUnits = $qualification->units->filter(function ($unit){
             return $unit->unit_group == "Optional";
        });

        return view('qualifications.show', compact('qualification', 'mandatoryUnits', 'optionalUnits'));
    }

    public function create()
    {
        $this->authorize('create', Qualification::class);

        $owners = Qualification::getQualificationOwners();
        $levels = Qualification::getQualificationLevels();
        $types = Qualification::getQualificationTypes();
        $ssa = Qualification::getQualificationSSA();
        $status = Qualification::getQualificationStatus();
        $overall_grading_types = Qualification::getQualificationOverallGradingTypes();

        return view('qualifications.create', compact('owners', 'levels', 'types', 'ssa', 'status', 'overall_grading_types'));
    }

    public function store(StoreQualificationRequest $request, QualificationService $qualificationService)
    {
        $this->authorize('create', Qualification::class);

        $qualification = $qualificationService->create($request->validated());

        return redirect()
            ->route('qualifications.show', ['id' => $qualification->id])
            ->with(['alert-success' => 'Qualification has been created successfully.']);
    }

    public function edit(Qualification $qualification)
    {
        $this->authorize('update', $qualification);

        $owners = Qualification::getQualificationOwners();
        $levels = Qualification::getQualificationLevels();
        $types = Qualification::getQualificationTypes();
        $ssa = Qualification::getQualificationSSA();
        $status = Qualification::getQualificationStatus();
        $overall_grading_types = Qualification::getQualificationOverallGradingTypes();

        return view('qualifications.edit', compact('qualification', 'owners', 'levels', 'types', 'ssa', 'status', 'overall_grading_types'));
    }

    public function update(StoreQualificationRequest $request, Qualification $qualification, QualificationService $qualificationService)
    {
        $this->authorize('update', $qualification);

        $qualification = $qualificationService->update($request->validated(), $qualification);

        return redirect()
            ->route('qualifications.show', ['id' => $qualification->id])
            ->with(['alert-success' => 'Qualification has been updated successfully.']);
    }

    public function destroy(Qualification $qualification, QualificationService $qualificationService)
    {
        $this->authorize('delete', $qualification);

        $deleted = $qualificationService->delete($qualification);

        if( request()->ajax() )
        {
            return response()->json([
                'success' => $deleted ? true : false,
                'message' => $deleted ? 'Qualification is deleted successfully.' : 'Something went wrong, delete aborted.'
            ]);
        }

        if(! $deleted)
        {
            back()->with(['alert-error' => 'Something went wrong, delete aborted.']);            
        }

        return redirect()
            ->route('qualifications.index')
            ->with(['alert-success' => 'Qualification is deleted successfully.']);
    }

    public function loadQualification(Request $request)
    {
        if(!$request->has('qualification_to_load') || $request->qualification_to_load == '')
        {
            return response()->json([
                'success' => false,
                'message' => 'Please select qualification.'
            ]);
        }

        try
        {
            $qualification = Qualification::findOrFail($request->qualification_to_load);

            $this->authorize('show', $qualification);

            $qualification->load([
                'units' => function ($query) {
                    $query->orderBy('unit_sequence');
                },
                'units.pcs' => function ($query) {
                    $query->orderBy('pc_sequence');
                },
            ]);
        }
        catch(ModelNotFoundException $exception)
        {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ]);
        }
        catch(UnauthorizedException $exception)
        {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ]);
        }
        $qualification = Qualification::findOrFail($request->qualification_to_load);

        return response()->json([
            'success' => true,
            'message' => [
                'qualification' => $qualification,
                'units' => $qualification->units,
            ]
        ]);
    }

    public function renderTree($id)
    {
        $qualification = Qualification::findOrFail($id);

        $this->authorize('show', $qualification);

        $data = [];

        foreach($qualification->units AS $unit)
        {
            $temp = new \stdClass();
            $temp->id = $unit->id;
            $temp->name = str_replace('/', '', $unit->unique_ref_number);
            $temp->text = $unit->title;
            $temp->parent_id = 0;
            $temp->icon = "ace-icon fa fa-folder orange";
            $temp->state = (object)[
                'expanded' => false,
            ];
            $temp->nodes = [];
            foreach($unit->pcs AS $pc)
            {
                $temp->nodes[] = (object)[
                    'id' => $pc->id,
                    'name' => $pc->reference,
                    'text' => $pc->title,
                    'parent_id' => $unit->id,
                    'icon' => "ace-icon fa fa-folder-open orange"
                ];
            }
            $data[] = $temp;
        }

        echo json_encode($data);
    }

    public function copy(Qualification $qualification)
    {
        $this->authorize('create', Qualification::class);

        $qualification->load([
            'units' => function ($query) {
                $query->orderBy('unit_sequence');
            },
            'units.pcs' => function ($query) {
                $query->orderBy('pc_sequence');
            },
        ]);

        $mandatory_units = 0;
        $optional_units = 0;

        $mandatory_units = $qualification->units->filter(function ($unit){
             return $unit->unit_group == "Mandatory";
        });
        $optional_units = $qualification->units->filter(function ($unit){
             return $unit->unit_group == "Optional";
        });

        return view('qualifications.copy', compact('qualification', 'mandatory_units', 'optional_units'));
    }

    public function copyAndCreate(Request $request, Qualification $qualification, QualificationService $qualificationService)
    {
        $this->authorize('create', Qualification::class);

        $request->validate([
            'new_qualification_title' => 'required|string|max:250',
        ]);

        $newQualification = $qualificationService->clone($qualification, $request->all());

        return redirect()
            ->route('qualifications.show', ['id' => $newQualification->id])
            ->with(['alert-success' => 'Qualification has been cloned successfully.']);
    }

    public function export(QualificationFilters $filters)
    {
	    $this->authorize('export', Qualification::class);

        return Excel::download(new QualificationsExport($filters), 'qualifications.xlsx');
    }

    public function exportSingleQualification(Qualification $qualification)
    {
	    $this->authorize('export', Qualification::class);

        return Excel::download(new SingleQualificationExport($qualification), 'qualification.xlsx');
    }
}
