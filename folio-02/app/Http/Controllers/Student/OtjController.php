<?php

namespace App\Http\Controllers\Student;

use App\Models\Training\TrainingRecord;
use App\Models\Training\Otj;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOtjRequest;
use App\Models\LookupManager;
use App\Models\Training\PortfolioPC;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\DB;

class OtjController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }


    private function getKsbElements(TrainingRecord $training)
    {
        return DB::table('portfolio_pcs')
            ->join('portfolio_units', 'portfolio_pcs.portfolio_unit_id', '=', 'portfolio_units.id')
            ->join('portfolios', 'portfolio_units.portfolio_id', '=', 'portfolios.id')
            ->where('portfolios.tr_id', $training->id)
            ->whereIn('portfolio_pcs.category', [9, 10, 11])
            ->orderBy('portfolio_pcs.category')
            ->orderBy('portfolio_units.unit_sequence')
            ->orderBy('portfolio_pcs.pc_sequence')
            ->select([
                'portfolios.id as portfolio_id',
                'portfolios.qan as portfolio_qan',
                'portfolios.title as portfolio_title',
                'portfolio_units.id as portfolio_unit_id',
                'portfolio_units.title as portfolio_unit_title',
                'portfolio_pcs.id as portfolio_pc_id',
                'portfolio_pcs.title as portfolio_pc_title',
                'portfolio_pcs.category as portfolio_pc_category',
            ])
            ->get();
    }

    private function saveOtjKsbs(Otj $otj, array $ksbElements)
    {
        $ksbRecords = collect($ksbElements)
            ->map(function ($elementId) use ($otj) {
                return [
                    'otj_id' => $otj->id,
                    'pc_id' => $elementId,
                ];
            })
            ->toArray();

        DB::table('otj_ksbs')->where('otj_id', $otj->id)->delete();
        DB::table('otj_ksbs')->insert($ksbRecords);
    }

    public function create(TrainingRecord $training)
    {
        $this->authorize('create', [Otj::class, $training]);

        $otjTypes = LookupManager::getOtjDdl();

        $showAssessmentPanel = false;
        if( auth()->user()->isAssessor() )
        {
            $showAssessmentPanel = true;
        }

        $ksbElements = $this->getKsbElements($training);

        $selectedKsbElements = [];

        return view('trainings.otj.create', compact('training', 'otjTypes', 'showAssessmentPanel', 'ksbElements', 'selectedKsbElements'));
    }

    public function store(TrainingRecord $training, StoreOtjRequest $request, FileUploadService $fileUploadService)
    {
        $this->authorize('create', [Otj::class, $training]);

        $otj = $training->otj()->create([
            'title' => $request->title,
            'type' => $request->type,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'duration' => $request->duration,
            'details' => preg_replace('/[^\x00-\x7F]/', '', $request->details),
            'assessor_comments' => preg_replace('/[^\x00-\x7F]/', '', $request->assessor_comments),
        ]);

        if ($request->hasFile('otj_evidence')) 
        {
            $fileUploadService->uploadAndAttachMedia($request, $otj, 'otj_evidences');
        }

        if (!empty($request->ksbElements)) 
        {
            $this->saveOtjKsbs($otj, $request->ksbElements);
        }

        return redirect()->route('trainings.otj.show', [$training, $otj])->with(['alert-success' => 'OTJ record is created successfully.']);
    }

    public function show(TrainingRecord $training, Otj $otj)
    {
        $this->authorize('show', [$otj, $training]);

        $selectedKsbElementsDetails = collect();
        $selectedKsbElements = DB::table('otj_ksbs')->where('otj_id', $otj->id)->pluck('pc_id')->toArray();
        if(count($selectedKsbElements) > 0)
        {
            $selectedKsbElementsDetails = PortfolioPC::whereIn('id', $selectedKsbElements)->select('title')->get();
        }

        return view('trainings.otj.show', compact('training', 'otj', 'selectedKsbElementsDetails'));
    }

    public function edit(TrainingRecord $training, Otj $otj)
    {
        $this->authorize('edit', [$otj, $training]);

        $otjTypes = LookupManager::getOtjDdl();

        $ksbElements = $this->getKsbElements($training);

        $selectedKsbElements = DB::table('otj_ksbs')->where('otj_id', $otj->id)->pluck('pc_id')->toArray();

        $showAssessmentPanel = false;
        if( auth()->user()->isAssessor() && !$otj->isAccepted() )
        {
            $showAssessmentPanel = true;
        }

        return view('trainings.otj.edit', compact('training', 'otj', 'otjTypes', 'showAssessmentPanel', 'ksbElements', 'selectedKsbElements'));
    }

    public function update(TrainingRecord $training, Otj $otj, StoreOtjRequest $request, FileUploadService $fileUploadService)
    {
        $this->authorize('edit', [$otj, $training]);

        $otj->update([
            'status' => $request->has('status') ? $request->status : $otj->status,  
            'assessor_comments' => $request->has('assessor_comments') ? preg_replace('/[^\x00-\x7F]/', '', $request->assessor_comments) : $otj->assessor_comments, 
            'is_otj' => $request->has('is_otj') ? $request->is_otj : $otj->is_otj, 
            'title' => $request->title,
            'type' => $request->type,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'duration' => $request->duration,
            'details' => preg_replace('/[^\x00-\x7F]/', '', $request->details),
        ]);

        if ($request->hasFile('otj_evidence'))
        {
            if($otj->media->count() > 0)
            {
                $otj->media->first->delete();
            }

            $fileUploadService->uploadAndAttachMedia($request, $otj, 'otj_evidences');
        }

        if(!empty($request->ksbElements))
        {
            $this->saveOtjKsbs($otj, $request->ksbElements);
        }

        return redirect()->route('trainings.otj.show', [$training, $otj])->with(['alert-success' => 'Otj log has been updated successfully.']);
    }

    public function destroy(TrainingRecord $training, Otj $otj)
    {
        $this->authorize('destroy', [$otj, $training]);

        DB::table('otj_ksbs')->where('otj_id', $otj->id)->delete();

        $otj->delete();

        return redirect()
            ->route('trainings.show', [$training])
            ->with(['alert-success' => 'Otj log has been deleted successfully.']);
    }
}
