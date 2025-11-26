<?php

namespace App\Http\Controllers\Qualification;


use App\Http\Controllers\Controller;
use App\Models\Qualifications\Central\CentralQualification;
use App\Services\Qualifications\CentralQualificationToLocalQualfiicationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class DownloadQualificationController extends Controller
{
    public $connection;

    public function __construct($connection = 'mysql_folio_central')
    {
        $this->connection = $connection;
        $this->middleware(['auth', 'is_staff']);
    }

    public function index(Request $request)
    {
        abort_if(! auth()->user()->can('create-qualification'), Response::HTTP_UNAUTHORIZED);

        $qualifications = collect();
        
        if($request->has('keyword'))
        {
            $value = $request->keyword;
            $qualifications = CentralQualification::orderBy('title')
                ->where(function($query) use ($value) {
                    $query->where('title', 'LIKE', '%' . $value . '%')
                        ->orWhere('qan', 'LIKE', '%' . str_replace('/', '', $value) . '%');
                })
                ->select('id', 'title', 'level', 'qan')
                ->withCount(['units'])
                ->get();
        }

        return view('qualifications.download.index', compact('qualifications'));
    }

    public function show($qualification_id)
    {
        $qualification = CentralQualification::findOrFail($qualification_id);
        $qualification->load('units.pcs');

        return view('qualifications.download.show', compact('qualification'));
    }

    public function store(Request $request)
    {
        $validQualIds = CentralQualification::pluck('id')->implode(',');
        $request->validate([
            'qualification_id' => 'required|numeric|in:' . $validQualIds,
            'chkUnit' => 'required|array|min:1',
        ], [
            'chkUnit.required' => 'No unit is selected or qualification has not units.'
        ]);

        $centralQualification = CentralQualification::findOrFail($request->qualification_id);

        DB::beginTransaction();
        try
        {
            $localQualification = (new CentralQualificationToLocalQualfiicationService())->copyQualificationToLocal($centralQualification, $request->chkUnit);
    
            DB::commit();
        }
        catch(Exception $ex)
        {
            DB::rollBack();
            throw new Exception("Error while downloading qualification." . PHP_EOL . $ex->getMessage() . PHP_EOL . $ex->getTraceAsString());
        }

        return redirect()
            ->route('qualifications.show', ['qualification' => $localQualification])
            ->with(['alert-success' => 'Qualification is downloaded successfully.']);
    }
}