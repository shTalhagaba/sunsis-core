<?php

namespace App\Http\Controllers\Media;

use App\Models\Training\TrainingRecordEvidence;
use Illuminate\Http\Request;
use App\Models\VideoStream;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\MediaStream;
use App\Http\Controllers\Controller;
use App\Models\Student;

class DownloadMediaController extends Controller
{
    public function show(Media $mediaItem)
    {
        return $mediaItem;
    }

    public function downloadFromDropbox(Media $mediaItem)
    {
        $path = $mediaItem->id . '/' . $mediaItem->file_name;
        $file_exists = \Storage::disk('dropbox')->exists($path);
        if($file_exists)
        {
            $url = \Storage::disk('dropbox')->url($path);
        }

        $tempImage = tempnam(sys_get_temp_dir(), $mediaItem->file_name);
        copy($url, $tempImage);

        return response()->download($tempImage, $mediaItem->file_name);
    }

    public function download($mediaIdEncrypted)
    {
        // return response()->download($mediaItem->getPath(), $mediaItem->file_name);
        //$file = \Storage::disk('s3')->getDriver()->readStream('/' . $mediaItem->id . '/' . $mediaItem->file_name);

        // return response()->download($mediaItem->getPath(), $mediaItem->file_name);

        // $file = \Storage::disk('s3')->getDriver()->readStream($mediaItem->getPath());
        
        // return \Response::stream(function() use($file) {
        //     fpassthru($file);
        // }, 200, [
        //     'Content-Type' => $mediaItem->getCustomProperty('mime-type'),
        //     'Content-Disposition' => 'attachment; filename="' . $mediaItem->file_name . '"',
        //     'Content-Length' => $mediaItem->size
        //     ]);

        $mediaItem = Media::find(decrypt($mediaIdEncrypted));
        
        $file = $mediaItem->stream();
        return \Response::stream(function() use($file) {
            fpassthru($file);
        }, 200, [
            'Content-Type' => $mediaItem->mime_type,
            'Content-Disposition' => 'attachment; filename="' . $mediaItem->file_name . '"',
            'Content-Length' => $mediaItem->size
            ]);


    }

    public function playVideo(Media $mediaItem)
    {
        $stream = new VideoStream($mediaItem->getPath());
        $stream->start();
    }

    public function downloadArchive(TrainingRecordEvidence $evidence)
    {
        // Let's get some media.
        $evidences = $evidence->getMedia('evidences');

        // Download the files associated with the media in a streamed way.
        // No prob if your files are very large.
        return MediaStream::create('evidences.zip')->addMedia($evidences);
    }
}
