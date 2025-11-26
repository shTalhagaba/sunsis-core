<?php

namespace App\Http\Controllers\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\FileUploadService;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\Models\Media;
use ReflectionClass;

class MediaController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function upload(Request $request, FileUploadService $fileUploadService)
    {
        $request->validate([
            'model_type' => 'required|string|max:255',
            'model_id' => 'required|numeric',
            ]
        );

        $modelClass = $request->model_type;
        $model = $modelClass::findOrFail($request->model_id);
        $modelType = new ReflectionClass($modelClass);
        $mediaCollections = $request->collection_name ? $request->collection_name : \Str::plural(strtolower($modelType->getShortName()), 2);
       
        $fileUploadService->uploadAndAttachMedia($request, $model, $mediaCollections);

        return back()->with(['alert-success' => 'File is uploaded successfully.']);
    }

    public function remove($model, Media $media)
    {
        abort_if(! auth()->user()->isStaff(), Response::HTTP_UNAUTHORIZED);
        abort_if( $media->model->id != $model, Response::HTTP_UNAUTHORIZED);

        $media->delete();

        return back()->with(['alert-success' => 'File is deleted successfully.']);
    }
}
