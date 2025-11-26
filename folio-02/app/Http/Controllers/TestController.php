<?php

namespace App\Http\Controllers;

//use Storage;

use App\Models\Lookups\UserTypeLookup;
use App\Models\Training\TrainingRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function testShowHide()
    {
        $students = User::where('user_type', UserTypeLookup::TYPE_STUDENT)
            ->where('web_access', 1)
            ->whereHas("training_records",  function($tr){
                $tr->where('status_code', TrainingRecord::STATUS_CONTINUING);
            })
            ->with("training_records")
            ->get();
	$totalEmailsSent = 0;
        foreach($students AS $student)
        {
            $last_login_at = optional($student->latestAuth)->login_at;
            if($last_login_at == '')
            {
                $tr = $student
                    ->training_records()
                    ->where('status_code', '=', TrainingRecord::STATUS_CONTINUING)
                    ->latest('created_at')
                    ->first();
                $date_to_check = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $tr->created_at);
            }
            else
            {
                $date_to_check = \Carbon\Carbon::createFromFormat('d/m/Y H:i:s', $last_login_at);
            }

            $not_logged_days = $date_to_check->diffInDays();
            if($not_logged_days > 28)
            {
                echo 'Email Sent to: ' . $student->primary_email . '<br>';
                echo 'Not logged in for: ' . $not_logged_days . '<br>';
                echo 'Last Logged in: ' . $date_to_check->format('d/m/Y H:i:s') . '<br>';
                echo '<p>=======================================</p>';
		$totalEmailsSent++;
            }
        }
	echo '<p>Total Emails Sent: ' . $totalEmailsSent . '</p>';
        //return view('test');
    }

    public function storeMedia(Request $request)
    {
        $path = storage_path('tmp/uploads');

        if(!file_exists($path))
        {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name' => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function removeMedia(Request $request)
    {
        if(is_null($request->name) || !isset($request->name))
        {
            return response()->json([
                'name' => 'No file name given',
                'message' => 'Nothing to delete',
                'path' => '',
            ]);
        }
        $path = storage_path('tmp\\uploads\\'.$request->name);

        \File::delete($path);

        return response()->json([
            'name' => $request->name,
            'message' => 'file deleted from server',
            'path' => $path,
        ]);
    }


}
