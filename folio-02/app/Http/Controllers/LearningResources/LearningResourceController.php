<?php

namespace App\Http\Controllers\LearningResources;

use App\Filters\LearningResourceFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLearningResourceRequest;
use App\Models\LearningResources\LearningResource;
use App\Models\LearningResources\LearningResourceUser;
use App\Models\Lookups\LearningResourceTypeLookup;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\DB;

class LearningResourceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(LearningResourceFilters $filters)
    {
        $learningResources = LearningResource::filter($filters)
            ->with(['userLike', 'tags'])
            ->paginate(session('resources_per_page', config('model_filters.default_per_page')));

        $selectedFilters = $filters->filters();

        return view('learnig_resources.index', compact('learningResources', 'selectedFilters'));
    }

    public function create()
    {
        $this->authorize('create', LearningResource::class);

        $resourceTypes = LearningResourceTypeLookup::getSelectData();

        return view('learnig_resources.create', compact('resourceTypes'));
    }

    public function store(StoreLearningResourceRequest $request, FileUploadService $fileUploadService)
    {
        $this->authorize('create', LearningResource::class);

        if ($request->resource_type == LearningResourceTypeLookup::TYPE_FILE_UPLOAD && !$request->has('learning_resource_file')) 
        {
            return back()
                ->withErrors(['learning_resource_file' => 'The resource file is required when resource type is File Upload.'])
                ->withInput();
        }

        DB::beginTransaction();
        try 
        {
            $learningResource = LearningResource::create([
                'resource_type' => $request->resource_type,
                'resource_name' => $request->resource_name,
                'resource_short_description' => $request->resource_short_description,
                'resource_content' => $request->resource_type == LearningResourceTypeLookup::TYPE_TEXT ? $request->resource_content : null,
                'resource_url' => $request->resource_type == LearningResourceTypeLookup::TYPE_URL ? $request->resource_url : null,
                'is_featured' => $request->input('is_featured', 0),
                'created_by' => auth()->user()->id,
            ]);

            if ($request->resource_type == LearningResourceTypeLookup::TYPE_FILE_UPLOAD && $request->hasFile('learning_resource_file')) 
            {
                $fileUploadService->uploadAndAttachMedia($request, $learningResource, 'learning_resources');
            }

            DB::commit();
        } 
        catch (\Exception $ex) 
        {
            DB::rollBack();

            return back()
                ->with(['alert-danger' => 'Failed to create learning resource: ' . $ex->getMessage()]);
        }

        return redirect()
            ->route('learning_resources.show', $learningResource)
            ->with(['alert-success' => 'Learning resource has been created successfully.']);
    }        

    public function show(LearningResource $learningResource)
    {
        

        return view('learnig_resources.show', compact('learningResource'));
    }

    public function edit(LearningResource $learningResource)
    {
        $this->authorize('edit', $learningResource);

        return view('learnig_resources.edit', compact('learningResource'));
    }

    public function update(StoreLearningResourceRequest $request, LearningResource $learningResource)
    {
        $this->authorize('create', LearningResource::class);

        DB::beginTransaction();
        try 
        {
            $learningResource->update([
                'resource_name' => $request->resource_name,
                'resource_short_description' => $request->resource_short_description,
                'resource_content' => $learningResource->resource_type == LearningResourceTypeLookup::TYPE_TEXT ? $request->resource_content : null,
                'resource_url' => $learningResource->resource_type == LearningResourceTypeLookup::TYPE_URL ? $request->resource_url : null,
                'is_featured' => $request->input('is_featured', 0),
                'created_by' => auth()->user()->id,
            ]);

            DB::commit();
        } 
        catch (\Exception $ex) 
        {
            DB::rollBack();

            return back()
                ->with(['alert-danger' => 'Failed to update learning resource: ' . $ex->getMessage()]);
        }

        return redirect()
            ->route('learning_resources.show', $learningResource)
            ->with(['alert-success' => 'Learning resource has been updated successfully.']);
    }     
    
    public function destroy(LearningResource $learningResource)
    {
        $this->authorize('delete', $learningResource);

        $learningResource->users()->detach();

        $learningResource->delete();

        return redirect()
            ->route('learning_resources.index')
            ->with(['alert-success' => 'Learning resource has been deleted successfully.']);
    }

    public function likeUnlike(LearningResource $learningResource)
    {
        $userId = auth()->user()->id;
        $learningResourceUser = LearningResourceUser::updateOrCreate(
            [
                'user_id' => $userId,
                'learning_resource_id' => $learningResource->id
            ],
            [
                'liked' => DB::raw('!liked')
            ]
        );

        $learningResource->update([
            'likes' => $learningResource->users()->sum('liked')
        ]);

        return response()->json([
            'message' => 'Success',
            'liked' => $learningResourceUser->liked
        ], 200);
    }

    public function bookUnbookmark(LearningResource $learningResource)
    {
        $userId = auth()->user()->id;
        $learningResourceUser = LearningResourceUser::updateOrCreate(
            [
                'user_id' => $userId,
                'learning_resource_id' => $learningResource->id
            ],
            [
                'bookmarked' => DB::raw('!bookmarked')
            ]
        );

        return response()->json([
            'message' => 'Success',
            'bookmarked' => $learningResourceUser->bookmarked
        ], 200);
    }
}
