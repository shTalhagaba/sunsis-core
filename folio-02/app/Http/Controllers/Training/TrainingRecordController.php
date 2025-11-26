<?php

namespace App\Http\Controllers\Training;

use App\Exports\PortfoliosExport;
use App\Exports\TrainingRecordsExport;
use App\Filters\OtjFilters;
use App\Filters\TrainingRecordFilters;
use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTrainingRecordRequest;
use App\Models\EqaSamples\EqaSample;
use App\Models\LookupManager;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Student;
use App\Models\Training\Portfolio;
use App\Models\Training\PortfolioPC;
use App\Models\Training\TrainingRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TrainingRecordController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    private function addCaseloadCondition(Builder &$query)
    {
        switch (auth()->user()->user_type) {
            case UserTypeLookup::TYPE_ADMIN:
            case UserTypeLookup::TYPE_SYSTEM_VIEWER:
            case UserTypeLookup::TYPE_QUALITY_MANAGER:
                break;

            case UserTypeLookup::TYPE_ASSESSOR:
                $query->where(function ($q) {
                    $q->where('tr.primary_assessor', '=', auth()->user()->id)
                        ->orWhere('tr.secondary_assessor', '=', auth()->user()->id);
                });
                break;

            case UserTypeLookup::TYPE_TUTOR:
                $trIds = DB::table('portfolios')->where('fs_tutor_id', auth()->user()->id)->pluck('tr_id')->toArray();
                $query->where(function ($q) use ($trIds) {
                    $q->where('tr.tutor', '=', auth()->user()->id)
                        ->orWhereIn('tr.id', $trIds);
                });
                break;

            case UserTypeLookup::TYPE_VERIFIER:
                $trIds = DB::table('portfolios')->where('fs_verifier_id', auth()->user()->id)->pluck('tr_id')->toArray();
                $query->where(function ($q) use ($trIds) {
                    $q->where('tr.verifier', '=', auth()->user()->id)
                        ->orWhereIn('tr.id', $trIds);
                });
                break;

            case UserTypeLookup::TYPE_STUDENT:
                $query->where('tr.student_id', '=', auth()->user()->id);
                break;

            case UserTypeLookup::TYPE_EMPLOYER_USER:
                $assessorIds = DB::table('employer_user_assessor')->where('employer_user_id', auth()->user()->id)->pluck('assessor_id')->toArray();
                $query->where('tr.employer_location', auth()->user()->employer_location)
                    ->where(function ($q) use ($assessorIds) {
                        $q->where('tr.employer_user_id', auth()->user()->id)
                            ->orWhere(function ($q2) use ($assessorIds) {
                                $q2->whereIn('tr.primary_assessor', $assessorIds)
                                    ->orWhereIn('tr.secondary_assessor', $assessorIds);
                            });
                    });
                break;

            case UserTypeLookup::TYPE_EQA:
                $activeAllocatedSampleId = DB::table('eqa_samples')
                    ->join('eqa_samples_personnels', 'eqa_samples.id', 'eqa_samples_personnels.sample_id')
                    ->where('eqa_samples.active', '=', 1)
                    ->where('eqa_samples_personnels.eqa_user_id', '=', auth()->user()->id)
                    ->select('eqa_samples.id')
                    ->pluck('id')
                    ->toArray();

                $sample_training_records = [];
                if (!isset($activeAllocatedSampleId[0])) {
                    $training_records = collect([]);
                } else {
                    $sample = EqaSample::findOrFail($activeAllocatedSampleId[0]);
                    foreach ($sample->training_records as $tr) {
                        $sample_training_records[] = !in_array($tr->id, $sample_training_records) ? $tr->id : '';
                    }
                }
                $query->whereIn('tr.id', $sample_training_records);
                break;

            case UserTypeLookup::TYPE_MANAGER:
                $assessorIds = DB::table('user_caseload_accounts')
                    ->where('user_id', auth()->user()->id)
                    ->where('caseload_account_type', UserTypeLookup::TYPE_ASSESSOR)
                    ->pluck('caseload_account_id')
                    ->toArray();

                $tutorIds = DB::table('user_caseload_accounts')
                    ->where('user_id', auth()->user()->id)
                    ->where('caseload_account_type', UserTypeLookup::TYPE_TUTOR)
                    ->pluck('caseload_account_id')
                    ->toArray();

                $verifierIds = DB::table('user_caseload_accounts')
                    ->where('user_id', auth()->user()->id)
                    ->where('caseload_account_type', UserTypeLookup::TYPE_VERIFIER)
                    ->pluck('caseload_account_id')
                    ->toArray();

                $query->where(function ($q1) use ($assessorIds, $tutorIds, $verifierIds) {
                    $q1
                        ->whereIn('tr.tutor', $tutorIds)
                        ->orWhereIn('tr.verifier', $verifierIds)
                        ->orWhere(function ($q2) use ($assessorIds) {
                            $q2->whereIn('tr.primary_assessor', $assessorIds)
                                ->orWhereIn('tr.secondary_assessor', $assessorIds);
                        });
                });

                break;

            default:
                $query->where('tr.employer_location', auth()->user()->employer_location);
                break;
        }
    }

    public function index(Request $request, TrainingRecordFilters $filters)
    {
        $this->authorize('index', TrainingRecord::class);
        $check_over_due = $request->over_due;
        $query = TrainingRecord::filter($filters)
            ->with(['programme', 'portfolios'])
            ->when(filter_var($request->over_due, FILTER_VALIDATE_BOOLEAN), function ($q) {
                $q->whereHas('reviews', function ($sub) {
                    $sub->overdueReview();
                });
            });

        $query->join('users AS students', 'students.id', '=', 'tr.student_id')
            ->select('tr.*');

        $this->addCaseloadCondition($query);

        $trainings = $query->paginate(
            session('trs_per_page', config('model_filters.default_per_page'))
        );

        return view('trainings.index', compact('trainings', 'filters', 'check_over_due'));
    }

    public function show(TrainingRecord $training, OtjFilters $otj_filters)
    {
        $this->authorize('show', $training);

        $student = $training->student;

        $tab = request()->query()['tab'] ?? 'tab' . $training->portfolios->first()->qan;

        $workAddress = $student->workAddress();
        $homeAddress = $student->homeAddress();

        $training->load([
            'primaryAssessor',
            'secondaryAssessor',
            'verifierUser',
            'portfolios',
            'training_plans',
            'otj',
            'crmNotes.media',
            'portfolios.units',
            'portfolios.units.pcs',
            'portfolios.units.pcs.mapped_evidences',
            'portfolios.units.pcs.mapped_evidences.media',
            'evidences' => function ($query) {
                $query->orderBy('updated_at', 'DESC');
            },
            'evidences.categories',
            'evidences.creator',
            'evidences.mapped_pcs',
            'evidences.media',
            'sessions',
            'sessions.tasks',
            'sessions.ksb',
            'reviews' => function ($query) {
                $query->orderBy('due_date');
            },
            'reviews.form',
        ]);

        $completedOtjSeconds = $training->otj->where('status', 'Accepted')->where('is_otj', true)->sum(function ($otj) {
            list($hours, $minutes, $seconds) = sscanf($otj->duration, '%d:%d:%d');
            return ($hours * 3600) + ($minutes * 60) + $seconds;
        });

        $portfolios = $training->portfolios;

        $assessment_complete_units_count = $training->units->where('assessment_complete', 1)->count();

        $tr_id = $training->id;

        $signedOffPcs = PortfolioPC::whereHas('unit', function ($query) use ($tr_id) {
            $query->whereHas('portfolio', function ($subQuery) use ($tr_id) {
                $subQuery->where('tr_id', $tr_id);
            });
        })
            ->where('assessor_signoff', 1)
            ->count();

        $allPcs = PortfolioPC::whereHas('unit', function ($query) use ($tr_id) {
            $query->whereHas('portfolio', function ($subQuery) use ($tr_id) {
                $subQuery->where('tr_id', $tr_id);
            });
        })
            ->count();

        $overallProgress = $allPcs == 0 ? 0 : round(($signedOffPcs / $allPcs) * 100);

        $sqlReadyToSignoffPcs = "
            SELECT 
                COUNT(*) AS ready_to_signoff_pcs
            FROM 
                portfolio_pcs 
            WHERE 
                portfolio_pcs.`assessor_signoff` = 0 
                AND portfolio_pcs.`accepted_evidences` >= portfolio_pcs.`min_req_evidences`
                AND portfolio_pcs.`portfolio_unit_id` IN (
                    SELECT 
                        portfolio_units.`id`
                    FROM 
                        portfolio_units INNER JOIN portfolios ON portfolio_units.`portfolio_id` = portfolios.`id` 
                    WHERE 
                        portfolios.`tr_id` = '{$tr_id}')";
        $readyToSignoffPcs = DB::select(DB::raw($sqlReadyToSignoffPcs));
        $readyToSignoffPcs = isset($readyToSignoffPcs[0]->ready_to_signoff_pcs) ? $readyToSignoffPcs[0]->ready_to_signoff_pcs : 0;

        $unsignedOffPcs = $allPcs - $signedOffPcs;
        $readyToSignoffPcsPercentage = $unsignedOffPcs == 0 ? 0 : round(($readyToSignoffPcs / $unsignedOffPcs) * 100);

        $sqlProgressForDial = "
            SELECT DISTINCT
                portfolios.tr_id AS ID,
                (
                    SUM(IF(portfolio_pcs.assessor_signoff = 1 OR portfolio_pcs.accepted_evidences >= portfolio_pcs.min_req_evidences, 1, 0))/SUM(IF(1 = 1, 1, 0))*100
                ) AS Progress
            FROM
                portfolio_pcs
                INNER JOIN portfolio_units
                    ON portfolio_pcs.portfolio_unit_id = portfolio_units.id
                INNER JOIN portfolios
                    ON portfolio_units.portfolio_id = portfolios.id
                INNER JOIN tr ON portfolios.tr_id = tr.id
            WHERE portfolios.`tr_id` = '{$tr_id}' 
            GROUP BY portfolios.tr_id
        ";
        $progressForDial = DB::select(DB::raw($sqlProgressForDial));

        $progressValue = isset($progressForDial[0]) ? round($progressForDial[0]->Progress) : 0;
        $progressChart = $this->progressSpeedoMeter($progressValue);

        $view = 'trainings.show';
        if (auth()->user()->getOriginal('user_type') == UserTypeLookup::TYPE_EQA) {
            $view = 'trainings.show_eqa_view';
        }

        $mainDirectoryFiles = $training->media->filter(function ($media) {
            return ! $media->hasCustomProperty('section_name') ? true : false;
        });

        $sectionFilesCount['main'] = count($mainDirectoryFiles);
        foreach ($training->mediaSections as $section) {
            if (! array_key_exists($section->slug, $sectionFilesCount)) {
                $sectionFilesCount[$section->slug] = 0;
            }
            foreach ($training->media as $media) {
                $sectionFilesCount[$section->slug] += $media->getCustomProperty('section_name') === $section->slug ? 1 : 0;
            }
        }

        $sectionName = (request()->has('section') && in_array(request()->section, $training->mediaSections->pluck('slug')->toArray())) ?
            request()->section :
            '';

        $mediaFiles = $training->media->filter(function ($media) use ($sectionName) {
            return $sectionName != '' ? $media->getCustomProperty('section_name') === $sectionName : true;
        });

        if ($sectionName == '') {
            $mediaFiles = $mainDirectoryFiles;
        }

        $disabledDeletePortfolioIds = DB::table('pc_evidence_mappings')
            ->join('tr_evidences', 'tr_evidences.id', '=', 'pc_evidence_mappings.tr_evidence_id')
            ->join('portfolio_pcs', 'pc_evidence_mappings.portfolio_pc_id', '=', 'portfolio_pcs.id')
            ->join('portfolio_units', 'portfolio_pcs.portfolio_unit_id', '=', 'portfolio_units.id')
            ->join('portfolios', 'portfolio_units.portfolio_id', '=', 'portfolios.id')
            ->where('portfolios.tr_id', '=', $training->id)
            ->select('portfolios.id')
            ->distinct()
            ->pluck('id')
            ->toArray();

        $filteredOtjQuery = $otj_filters->apply($training->otj()->getQuery());

        // Add default sorting (if none applied)
        if (!isset($otj_filters->filters()['sort_by'])) {
            $filteredOtjQuery->orderBy('date', 'desc')->orderBy('start_time', 'desc');
        }

        $filteredOtj = $filteredOtjQuery->get();

        return view($view, compact(
            'training',
            'student',
            'tab',
            'workAddress',
            'homeAddress',
            'portfolios',
            'progressChart',
            'assessment_complete_units_count',
            'signedOffPcs',
            'allPcs',
            'overallProgress',
            'completedOtjSeconds',
            'mediaFiles',
            'sectionName',
            'sectionFilesCount',
            'disabledDeletePortfolioIds',
            'readyToSignoffPcs',
            'readyToSignoffPcsPercentage',
            'otj_filters',
            'filteredOtj',
        ));
    }

    private function progressSpeedoMeter($progress)
    {
        $options = new \stdClass();
        $options->chart = (object)[
            'type' => 'gauge',
            'plotBackgroundColor' => null,
            'plotBackgroundImage' => null,
            'plotBorderWidth' => 0,
            'plotShadow' => false
        ];
        $options->credits = 'disabled';
        $options->title = (object)['text' => ''];
        $options->pane = (object)[
            'startAngle' => -150,
            'endAngle' => 150,
            'background' => [
                (object)[
                    'backgroundColor' => (object)[
                        'linearGradient' => (object)['x1' => 0, 'y1' => 0, 'x2' => 0, 'y1' => 1],
                        'stops' => [
                            [0, '#FFF'],
                            [1, '#333']
                        ]
                    ],
                    'borderWidth' => 0,
                    'outerRadius' => '109%'
                ],
                (object)[
                    'backgroundColor' => (object)[
                        'linearGradient' => (object)['x1' => 0, 'y1' => 0, 'x2' => 0, 'y1' => 1],
                        'stops' => [
                            [0, '#333'],
                            [1, '#FFF']
                        ]
                    ],
                    'borderWidth' => 0,
                    'outerRadius' => '107%'
                ],
                (object)[
                    'backgroundColor' => '#DDD',
                    'borderWidth' => 0,
                    'outerRadius' => '105%',
                    'innerRadius' => '103%'
                ]
            ]
        ];
        $options->yAxis = (object)[
            'min' => 0,
            'max' => 100,
            'minorTickInterval' => 'auto',
            'minorTickWidth' => 1,
            'minorTickLength' => 10,
            'minorTickPosition' => 'inside',
            'minorTickColor' => '#666',
            'tickPixelInterval' => 30,
            'tickWidth' => 2,
            'tickPosition' => 'inside',
            'tickLength' => 10,
            'tickColor' => '#666',
            'labels' => (object)['step' => 2, 'rotation' => 'auto'],
            'title' => (object)['text' => '%'],
            'plotBands' => [
                (object)['from' => 0, 'to' => 100, 'color' => '#55BF3B']
            ],
        ];
        $options->series = [
            (object)[
                'name' => 'Progress',
                'data' => [$progress],
                'tooltip' => (object)['valueSuffix' => '%']
            ]
        ];

        return json_encode($options, JSON_NUMERIC_CHECK);
    }

    public function edit(TrainingRecord $training)
    {
        $this->authorize('edit', $training);

        $assessors = LookupManager::getAssessors();
        $secondary_assessors = LookupManager::getAssessors();
        $tutors = LookupManager::getTutors();
        $verifiers = LookupManager::getVerifiers();
        $employers = LookupManager::getEmployersLocationsDDL();

        $employerUsers = \App\Models\User::orderBy('firstnames')->select(DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
            ->where('user_type', UserTypeLookup::TYPE_EMPLOYER_USER)
            ->where('employer_location', $training->employer_location)
            ->pluck('name', 'id')->toArray();

        return view('trainings.edit', compact('training', 'assessors', 'employers', 'verifiers', 'tutors', 'secondary_assessors', 'employerUsers'));
    }

    public function update(TrainingRecord $training, StoreTrainingRecordRequest $request)
    {
        $this->authorize('edit', $training);

        $training->update([
            'employer_location' => $request->employer_location,
            'primary_assessor' => $request->primary_assessor,
            'secondary_assessor' => $request->secondary_assessor,
            'tutor' => $request->tutor,
            'verifier' => $request->verifier,
            'otj_hours' => $request->otj_hours,
            'contracted_hours_per_week' => $request->contracted_hours_per_week,
            'weeks_to_worked_per_year' => $request->weeks_to_worked_per_year,
            'employer_user_id' => $request->employer_user_id,
        ]);

        $portfolio_fields = [
            "status_code",
            "start_date",
            "planned_end_date",
            "actual_end_date",
            "ab_registration_number",
            "ab_registration_date",
            "cert_applied",
            "cert_received",
            "cert_sent_to_learner",
            "learning_outcome",
            "fs_tutor_id",
            "fs_verifier_id",
            "certificate_no",
            "cert_expiry_date",
            "batch_no",
            "candidate_no",
        ];

        foreach ($training->portfolios as $portfolio) {
            foreach ($portfolio_fields as $portfolio_field) {
                $_field = $portfolio_field . $portfolio->id;
                if ($request->has($_field)) {
                    $portfolio->$portfolio_field = $request->$_field;
                }

                if (in_array($portfolio_field, ["status_code", "start_date", "planned_end_date", "actual_end_date"])) {
                    if (is_null($portfolio->getOriginal($portfolio_field)) && !is_null($training->getOriginal($portfolio_field))) {
                        $portfolio->$portfolio_field = $training->getOriginal($portfolio_field);
                    }
                }
            }

            $portfolio->save();
        }

        return redirect()
            ->route('trainings.show', $training)
            ->with(['alert-success' => 'Training record is updated successfully.']);
    }

    public function destroy(TrainingRecord $training)
    {
        $this->authorize('delete', $training);

        if (!AppHelper::requestFromOffice()) {
            return response()->json([
                'success' => false,
                'message' => 'Deletion of training record is currently disabled. Please contact Perspective Support for further info.'
            ]);
        }

        if ($training->evidences->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Training record contains evidences, hence it cannot be deleted.'
            ]);
        }

        $training->delete();

        return response()->json([
            'success' => true,
            'message' => 'Training record is deleted.'
        ]);
    }

    public function export(TrainingRecordFilters $filters)
    {
        $this->authorize('index', TrainingRecord::class);

        return Excel::download(new TrainingRecordsExport($filters,  request('check_over_due', false)), 'TrainingRecords.xlsx');
    }

    public function exportPortfolios(TrainingRecordFilters $filters)
    {
        $this->authorize('index', TrainingRecord::class);

        return Excel::download(new PortfoliosExport($filters), 'Portfolios.xlsx');
    }
}
