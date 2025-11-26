<?php

namespace App\Http\Controllers\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Spatie\MediaLibrary\Models\Media;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    public function storeMedia(Request $request)
    {
        $path = storage_path('tmp' . DIRECTORY_SEPARATOR . 'uploads');

        if(!file_exists($path))
        {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name = pathinfo(trim($file->getClientOriginalName()), PATHINFO_FILENAME);
        $name .= '_' . uniqid() . '.';
        $name .= pathinfo(trim($file->getClientOriginalName()), PATHINFO_EXTENSION);

        $file->move($path, $name);

        return response()->json([
            'message' => 'File Uploaded Successfully',
            'name' => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);

    }

    public function removeMedia(Request $request)
    {
        $path = storage_path('tmp/uploads/'.$request->name);

        \File::delete($path);

        return response()->json([
            'name' => $request->name,
            'message' => 'file deleted from server',
            'path' => $path,
        ]);
    }
}
