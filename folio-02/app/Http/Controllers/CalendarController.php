<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;


class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();

        if(request()->ajax())
        {
            $start = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
            $end = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');
            $data = CalendarEvent::where('user_id', $user->id)
                ->whereDate('start', '>=', $start)
                ->whereDate('end',   '<=', $end)->get(['id','title','start', 'end', 'description']);

            foreach($data AS &$entry)
            {
                $entry->start = \Carbon\Carbon::parse($entry->start)->format('Y-m-d') . 'T' . \Carbon\Carbon::parse($entry->start)->format('H:i:s');
                $entry->end = \Carbon\Carbon::parse($entry->end)->format('Y-m-d') . 'T' . \Carbon\Carbon::parse($entry->end)->format('H:i:s');
                $entry->allDay = false;
            }
            return response()->json($data);
        }
    }

    public function create(Request $request)
    {
        $insertArr = [ 'title' => $request->title,
                       'start' => $request->start,
                       'end' => $request->end,
                       'description' => $request->description,
                       'user_id' => $request->user_id,
                    ];
        $event = CalendarEvent::insert($insertArr);

        return response()->json([
            'event' => $event
        ]);
    }

    public function update(Request $request)
    {
        $where = array('id' => $request->id);
        $updateArr = ['title' => $request->title,
                        'start' => $request->start,
                        'end' => $request->end,
                        'description' => $request->description
                    ];
        $event  = CalendarEvent::where($where)->update($updateArr);

        return response()->json([
            'event' => $event
        ]);
    }


    public function destroy(Request $request)
    {
        $event = CalendarEvent::where('id',$request->id)->delete();

        return response()->json([
            'event' => $event
        ]);
    }
}
