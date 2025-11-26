<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class ImageCropperController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('cropper');
    }

    public function manageSignature()
    {
        $user = Auth::user();

        $sig = $user->getFirstMedia('signatures');

        $temporaryS3Url = ($sig) ? $sig->getTemporaryUrl(\Carbon\Carbon::now()->addMinutes(1)) : '';

        return view('signature', compact('user', 'temporaryS3Url'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:4096',
        ]);

        $folderPath = public_path('uploads/');

        $image_parts = explode(";base64,", $request->image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . uniqid() . '.png';

        file_put_contents($file, $image_base64);

        return response()->json(['success'=>'success']);
    }

    public function uploadSignature(Request $request)
    {
        $user = \Auth::user();

        $folderPath = storage_path('tmp' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR);

        if(!file_exists($folderPath))
        {
            mkdir($folderPath, 0777, true);
        }

        $image_parts = explode(";base64,", $request->image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . uniqid() . '.png';

        file_put_contents($file, $image_base64);

        try{
            $user->addMedia($file)->toMediaCollection('signatures', 's3');

            return response()->json(['success'=>'success']);
        }
        catch(\Throwable $exception){
            \Session::flash('alert-danger', $exception->getMessage());
            return response()->json(['success'=>'false']);
        }
    }
}
