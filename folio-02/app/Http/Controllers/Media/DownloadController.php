<?php

namespace App\Http\Controllers\Media;

use App\Models\VideoStream;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DownloadController extends Controller
{
    public function download($file_system_name)
    {
    	//dd($file_new_name);

    	$pathToFile = storage_path('app/trs/' . $file_system_name);
    	return response()->download($pathToFile);

    }

    public function playVideo($file_system_name)
    {
        $pathToFile = storage_path('app/trs/' . $file_system_name);
        $stream = new VideoStream($pathToFile);
        $stream->start();
    }
}
