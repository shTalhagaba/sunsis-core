<?php

namespace App\Http\Controllers\EqaSamples;

use App\Models\Organisations\Organisation;
use App\Models\Training\TrainingRecord;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\EqaSamples\EqaSample;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;


class EqaSampleController extends Controller
{
    public function index(Request $request)
    {
	if(\Auth::user()->isStudent())
        {
            abort(403, '403. Unauthorised');
        }
        $filters = new \stdClass();
        $filters->sortBy = $request->has('sortBy') ? $request->sortBy : 'title';
        $filters->orderBy = $request->has('orderBy') ? $request->orderBy : 'ASC';
        $filters->perPage = $request->has('perPage') ? $request->perPage : 20;
        $filters->keyword = $request->has('keyword') ? $request->keyword : null;

        $samples = EqaSample::filter((array)$filters)
            ->orderBy($filters->sortBy, $filters->orderBy)
            ->paginate($filters->perPage);

        return view('eqa_samples.index', compact('samples', 'filters'));
    }

    public function create()
    {
        $training_records_ddl = \DB::table('users')
            ->join('tr', 'users.id', 'tr.student_id')
            ->select("tr.id", \DB::raw("CONCAT(firstnames, ' ', surname) AS name"))
            ->orderBy('firstnames', 'asc')
            ->pluck("name", "id")
            ->toArray();

        $eqa_personnels = [];
        $training_records = [];

        return view('eqa_samples.create', compact('training_records_ddl', 'eqa_personnels', 'training_records'));
    }

    public function edit($id)
    {
        $sample = EqaSample::findOrFail($id);

        $training_records_ddl = \DB::table('users')
            ->join('tr', 'users.id', 'tr.student_id')
            ->select("tr.id", \DB::raw("CONCAT(firstnames, ' ', surname) AS name"))
            ->orderBy('firstnames', 'asc')
            ->pluck("name", "id")
            ->toArray();

        $eqa_personnels = $sample->eqa_personnels;
        $training_records = $sample->training_records;

        return view('eqa_samples.edit', compact('sample', 'training_records_ddl', 'eqa_personnels', 'training_records'));
    }

    public function store(Request $request)
    {
        $messages = [
            'eqa_personnels.required' => 'Please select at least one EQA personnel.',
            'tr_ids.required' => 'Please select at least one training record.',
        ];
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:150',
            'active_from' => 'required|date',
            'active_to' => 'required|date|after:active_from',
            'eqa_personnels' => 'required|array|min:1',
            'tr_ids' => 'required|array|min:1',
        ], $messages);

        $sample = EqaSample::create([
            'active' => $request->active,
            'title' => $request->title,
            'active_from' => $request->active_from,
            'active_to' => $request->active_to,
            'created_by' => \Auth::user()->id,
        ]);

        $sample->eqa_personnels()->sync($request->eqa_personnels);
        $sample->training_records()->sync($request->tr_ids);

        if($request->active == 1)
        {
            \DB::table('eqa_samples')->where('id', '!=', $sample->id)->update(['active' => 0]);
        }

        \Session::flash('alert-success', 'Sample for EQA has been created successfully.');

        return redirect()->route('eqa_samples.index');
    }

    public function update(Request $request, $id)
    {
        $sample = EqaSample::findOrFail($id);

        $messages = [
            'eqa_personnels.required' => 'Please select at least one EQA personnel.',
            'tr_ids.required' => 'Please select at least one training record.',
        ];
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:150',
            'active_from' => 'required|date',
            'active_to' => 'required|date|after:active_from',
            'eqa_personnels' => 'required|array|min:1',
            'tr_ids' => 'required|array|min:1',
        ], $messages);

        $sample->update([
            'active' => $request->active,
            'title' => $request->title,
            'active_from' => $request->active_from,
            'active_to' => $request->active_to,
            'created_by' => \Auth::user()->id,
        ]);

        if($request->active == 1)
        {
            \DB::table('eqa_samples')->where('id', '!=', $sample->id)->update(['active' => 0]);
        }

        $sample->eqa_personnels()->sync($request->eqa_personnels);
        $sample->training_records()->sync($request->tr_ids);

        \Session::flash('alert-success', 'Sample for EQA has been updated successfully.');

        return redirect()->route('eqa_samples.index');
    }
}
