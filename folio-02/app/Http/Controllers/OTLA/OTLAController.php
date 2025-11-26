<?php

namespace App\Http\Controllers\OTLA;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lookups\UserTypeLookup;
use App\Models\OTLA\OTLA;
use App\Models\Programmes\Programme;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OTLAController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }

    public function index(Request $request)
    {
        $query = OTLA::query()
            ->with(['coach', 'programme', 'creator']);

        if (auth()->user()->isAssessor()) {
            $query->where('ld_coach', auth()->id());
        } elseif (auth()->user()->isVerifier()) {
            $query->where('created_by', auth()->id());
        } elseif (!auth()->user()->isAdmin() && !auth()->user()->isQualityManager()) {
            $query->where('observer_2', auth()->id());
        }

        $otlas = $query->paginate(session('otla_per_page', config('model_filters.default_per_page')));
        return view('otla.index', compact('otlas'));
    }

    public function create()
    {
        $assessors = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->where('user_type', UserTypeLookup::TYPE_ASSESSOR)
            ->withActiveAccess()
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        $programmes = Programme::where('status', 1)
            ->active()
            ->orderBy('title')
            ->pluck('title', 'id')
            ->toArray();

        $observers = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->withActiveAccess()
            ->staffUsers()
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        $grades = collect(range(1, 4))->mapWithKeys(function ($grade) {
            return [$grade => $grade];
        })->toArray();

        return view('otla.create', compact('assessors', 'programmes', 'observers', 'grades'));
    }

    public function edit(OTLA $otla)
    {
        $assessors = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->where('user_type', UserTypeLookup::TYPE_ASSESSOR)
            ->withActiveAccess()
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        $programmes = Programme::where('status', 1)
            ->active()
            ->orderBy('title')
            ->pluck('title', 'id')
            ->toArray();

        $observers = User::select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->withActiveAccess()
            ->staffUsers()
            ->orderBy('name', 'asc')
            ->pluck('name', 'id')
            ->toArray();

        $grades = collect(range(1, 4))->mapWithKeys(function ($grade) {
            return [$grade => $grade];
        })->toArray();

        $sessionTypes = json_decode($otla->session_type, true) ?? [];
        $formData = json_decode($otla->form_data, true) ?? [];

        return view('otla.edit', compact('otla', 'assessors', 'programmes', 'observers', 'grades', 'sessionTypes', 'formData'));
    }

    public function store(Request $request)
    {
        $otla = OTLA::create([
            'ld_coach' => $request->input('ld_coach'),
            'programme_id' => $request->input('programme_id'),
            'stage_of_programme' => $request->input('stage_of_programme'),
            'reg_attendees' => $request->input('reg_attendees'),
            'actual_attendees' => $request->input('actual_attendees'),
            'male_attendees' => $request->input('male_attendees'),
            'female_attendees' => $request->input('female_attendees'),
            'observer_1' => auth()->id(),
            'observer_2' => $request->input('observer_2'),
            'lt_1618' => $request->input('lt_1618'),
            'lt_19plus' => $request->input('lt_19plus'),
            'lt_apps' => $request->input('lt_apps'),
            'session_type' => $request->input('session_type') ? json_encode($request->input('session_type')) : null,
            'observation_start' => $request->input('observation_start'),
            'observation_end' => $request->input('observation_end'),
            'created_by' => auth()->id(),
            'form_data' => json_encode($request->except(['ld_coach', 'programme', 'created_by', '_token'])),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('otla.show', $otla)
            ->with(['alert-success' => 'OTLA record has been created successfully.']);
    }

    public function show(OTLA $otla)
    {
        // dd($otla);
        $sessionTypes = json_decode($otla->session_type, true) ?? [];
        $formData = json_decode($otla->form_data, true) ?? [];

        return view('otla.show', compact('otla', 'sessionTypes', 'formData'));
    }

    public function update(Request $request, OTLA $otla)
    {
        $iqaSigned = $otla->iqa_signed;
        $iqaSignedDate = $otla->iqa_signed_date;
        $observer2Signed = $otla->observer_2_signed;
        $observer2SignedDate = $otla->observer_2_signed_date;
        $coachSigned = $otla->coach_signed;
        $coachSignedDate = $otla->coach_signed_date;

        if ($otla->created_by == auth()->user()->id && !$otla->isIqaSigned()) {
            $iqaSigned = $request->input('iqa_signed', 0);
            $iqaSignedDate = $iqaSigned ? now()->format('Y-m-d') : null;
        }
        if ($otla->observer_2 == auth()->user()->id && !$otla->isObserver2Signed()) {
            $observer2Signed = $request->input('observer_2_signed', 0);
            $observer2SignedDate = $observer2Signed ? now()->format('Y-m-d') : null;
            $otla->update([
                'observer_2_signed' => $observer2Signed,
                'observer_2_signed_date' => $observer2SignedDate,
            ]);
            return redirect()
                ->route('otla.show', $otla)
                ->with(['alert-success' => 'Information is saved successfully.']);
        }
        if ($otla->ld_coach == auth()->user()->id && !$otla->isCoachSigned()) {
            $coachSigned = $request->input('coach_signed', 0);
            $coachSignedDate = $coachSigned ? now()->format('Y-m-d') : null;
            $otla->update([
                'coach_signed' => $coachSigned,
                'coach_signed_date' => $coachSignedDate,
            ]);
            return redirect()
                ->route('otla.show', $otla)
                ->with(['alert-success' => 'Information is saved successfully.']);
        }

        $otla->update([
            'ld_coach' => $request->input('ld_coach'),
            'programme_id' => $request->input('programme_id'),
            'stage_of_programme' => $request->input('stage_of_programme'),
            'reg_attendees' => $request->input('reg_attendees'),
            'actual_attendees' => $request->input('actual_attendees'),
            'male_attendees' => $request->input('male_attendees'),
            'female_attendees' => $request->input('female_attendees'),
            'observer_2' => $request->input('observer_2'),
            'lt_1618' => $request->input('lt_1618'),
            'lt_19plus' => $request->input('lt_19plus'),
            'lt_apps' => $request->input('lt_apps'),
            'observation_start' => $request->input('observation_start'),
            'observation_end' => $request->input('observation_end'),
            'session_type' => $request->input('session_type') ? json_encode($request->input('session_type')) : null,
            'observation_start' => $request->input('observation_start'),
            'form_data' => json_encode($request->except(['ld_coach', 'programme', 'created_by', '_token'])),
            'iqa_signed' => $iqaSigned,
            'iqa_signed_date' => $iqaSignedDate,
            'observer_2_signed' => $observer2Signed,
            'observer_2_signed_date' => $observer2SignedDate,
            'coach_signed' => $coachSigned,
            'coach_signed_date' => $coachSignedDate,
        ]);



        return redirect()
            ->route('otla.show', $otla)
            ->with(['alert-success' => 'OTLA record has been updated successfully.']);
    }

    public function destroy(OTLA $otla)
    {
        $otla->delete();

        return redirect()
            ->route('otla.index')
            ->with(['alert-success' => 'OTLA record has been deleted successfully.']);
    }
}
