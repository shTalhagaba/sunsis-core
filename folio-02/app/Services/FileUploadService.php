<?php

namespace App\Services;

use App\Helpers\AppHelper;
use App\Http\Requests\FileUploadRequest;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class FileUploadService
{
    public function validate(Request $request)
    {
        $files = $request->files->all();
        if(! is_array($files))
        {
            return;
        }

        $fileUploadRequest = new FileUploadRequest( array_keys($request->files->all()) );
        
        $request->validate(
            $fileUploadRequest->rules(),
            $fileUploadRequest->messages()
        );
    }

    /**
     * Uploads file using medialibrary and attach to a model.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Model $mediaModel
     * @param string $mediaCollection
     *
     * @return Spatie\MediaLibrary\Models\Media 
     */
    public function uploadAndAttachMedia(Request $request, $mediaModel, $mediaCollection, $additionalCustomProperties = [])
    {
        if(!AppHelper::requestFromOffice())
        {
            $this->validate($request);
        }

        $media = [];
        foreach($request->files->all() AS $file)
        {
            $ext = pathinfo( trim($file->getClientOriginalName()), PATHINFO_EXTENSION );
            $customFileName = md5(env('APP_KEY') . now() . $mediaModel->id) . '.' . $ext;

            $customProperties = ['uploaded_by' => auth()->user()->id];
	    if($additionalCustomProperties)
            {
                $customProperties = array_merge($customProperties, $additionalCustomProperties);
            }
            if($request->has('mediaSection') && $request->mediaSection !== '' && $request->mediaSection !== 'null' && !is_null($request->mediaSection))
            {
                $customProperties = array_merge($customProperties, ['section_name' => $request->mediaSection]);
            }

            $media[] = $mediaModel->addMedia($file)
                ->usingFileName( $customFileName )
                ->withCustomProperties( $customProperties )
                ->toMediaCollection( $mediaCollection, 's3' );
        }

        return $media;
    }
}
