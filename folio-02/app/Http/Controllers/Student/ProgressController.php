<?php

namespace App\Http\Controllers\Student;

use App\Models\User;
use App\Models\Training\TrainingRecord;
use App\Models\Training\Portfolio;
use App\Models\Training\PortfolioPC;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lookups\TrainingStatusLookup;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Training\TrainingRecordEvidence;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;


class ProgressController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'is_staff']);
    }
 
    public function signoff(TrainingRecord $training, Portfolio $portfolio)
    {
        abort_if($portfolio->tr_id !== $training->id, Response::HTTP_UNAUTHORIZED);

        abort_if(! $this->isAllowed(auth()->user(), $training, $portfolio), Response::HTTP_UNAUTHORIZED);

        $portfolio->load([
            'units',
            'units.pcs',
            'units.pcs.mapped_evidences' => function ($query) {
                $query->orderBy('updated_at', 'DESC');
            },
            'units.pcs.mapped_evidences.media'
        ]);

        $pcsStats = [];
        foreach($portfolio->units AS $portfolioUnit)
        {
            foreach($portfolioUnit->pcs AS $portfolioUnitPc)
            {
                if(! isset($pcsStats[$portfolioUnitPc->id]) )
                {
                    $pcsStats[$portfolioUnitPc->id] = 0;
                }
                foreach($portfolioUnitPc->mapped_evidences AS $mappedEvidence)
                {
                    if( $mappedEvidence->getOriginal('evidence_status') == TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED )
                    {
                        $pcsStats[$portfolioUnitPc->id] += 1;
                    }
                }
            }
        }

        return view('trainings.progress_signoff', compact('training', 'portfolio', 'pcsStats'));
    }

    public function saveSignoff(TrainingRecord $training, Portfolio $portfolio, Request $request)
    {
        abort_if($portfolio->tr_id !== $training->id, Response::HTTP_UNAUTHORIZED);

        abort_if(! $this->isAllowed(auth()->user(), $training, $portfolio), Response::HTTP_UNAUTHORIZED);

        if(!isset($request->chkPC))
        {
            return back()->withErrors('Nothing to signoff.');
        }

        foreach($request->chkPC AS $pc_id)
        {
            $pc = PortfolioPC::findOrFail($pc_id);
            $pc->assessor_signoff = 1;
            $pc->save();
        }

        $trainingSignedOffPercentage  = $training->signedOffPercentage();
        // if($trainingSignedOffPercentage == '100.0')
        // {
        //     $training->actual_end_date = date('Y-m-d');
        //     $training->status_code = TrainingStatusLookup::STATUS_COMPLETED;
        //     $training->save();
        // }

	DB::table('portfolio_pcs_signoff')->insert([
            'tr_id' => $training->id,
            'portfolio_id' => $portfolio->id,
            'pc_ids' => json_encode($request->chkPC),
            'training_progress' => $trainingSignedOffPercentage,
            'portfolio_progress' => $portfolio->signedOffPCsPercentage(),
            'signedoff_by' => auth()->user()->id,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ]);

        return redirect()
            ->route('trainings.show', $training)
            ->with(['alert-success' => 'Portfolio progress is saved successfully.']);
    }

    public function cancelPcSignoff(TrainingRecord $training, Portfolio $portfolio, Request $request)
    {
	abort_if($portfolio->tr_id !== $training->id, Response::HTTP_UNAUTHORIZED);

        abort_if(! $this->isAllowed(auth()->user(), $training, $portfolio), Response::HTTP_UNAUTHORIZED);

        if( $portfolio->tr_id !== $training->id )
        {
            return response()->json([
                'success' => false,
                'message' => 'Bad Request',
            ], Response::HTTP_BAD_REQUEST);
        }

        if(! $this->isAllowed(auth()->user(), $training, $portfolio))
        {
            return response()->json([
                'success' => false,
                'message' => 'Bad Request',
            ], Response::HTTP_BAD_REQUEST);
        }

        if(! auth()->user()->can('cancel-signoff-progress') )
        {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorization action',
            ], Response::HTTP_UNAUTHORIZED);           
        }

        $validator = Validator::make($request->all(), [
            'training_id' => 'required|numeric',
            'pc_id' => 'required|numeric',
            'reason' => 'required|string|max:500',
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()->all()
            ], Response::HTTP_BAD_REQUEST);
        }

        try 
        {
            $training = TrainingRecord::findOrFail($request->training_id);
            $pc = PortfolioPC::findOrFail($request->pc_id);

            $portfolio = $pc->unit->portfolio;

            abort_if($portfolio->tr_id !== $training->id, Response::HTTP_UNAUTHORIZED);
            abort_if(! $this->isAllowed(auth()->user(), $training, $portfolio), Response::HTTP_UNAUTHORIZED);

            $pc->update(['assessor_signoff' => 0]);

            DB::table('portfolio_pcs_cancel_signoff')->insert([
                'pc_id' => $pc->id,
                'reason' => $request->input('reason', 'No reason given by ' . auth()->user()->full_name),
                'actor_id' => auth()->user()->id,
                'training_progress' => $training->signedOffPercentage(),
                'portfolio_progress' => $portfolio->signedOffPCsPercentage(),
                'created_at' => now()->format('Y-m-d H:i:s'),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ]);
        }
        catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found'
            ], 404);
        }
        catch (HttpException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized Action.'
            ], 401);
        }
        catch (\Exception $e) 
        {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Information saved successfully.'
        ]);
    }

    private function isInUserCaseload(User $user, TrainingRecord $trainingRecord, $portfolio)
    {
        if( $user->user_type == UserTypeLookup::TYPE_ASSESSOR )
        {
            return in_array($user->id, [$trainingRecord->primaryAssessor->id, optional($trainingRecord->secondaryAssessor)->id]);
        }

        if( $user->user_type == UserTypeLookup::TYPE_TUTOR )
        {
            return $user->id === $trainingRecord->tutor || $user->id === $portfolio->fs_tutor_id;
        }

        return false;
    }

    private function isAllowed(User $user, TrainingRecord $training, $portfolio)
    {
        if( 
            $user->can('signoff-progress') && 
            in_array($user->user_type, [UserTypeLookup::TYPE_ASSESSOR, UserTypeLookup::TYPE_TUTOR]) 
        )
        {
            return $this->isInUserCaseload($user, $training, $portfolio);
        }

        if($user->isAdmin() && $user->can('signoff-progress'))
        {
            return true;
        }

        return false;
    }

}
