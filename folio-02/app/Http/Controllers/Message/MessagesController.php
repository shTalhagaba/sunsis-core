<?php

namespace App\Http\Controllers\Message;

use App\Mail\NewMessageEmail;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\MessageHandler;
use App\Models\Messager\Message;
use App\Http\Controllers\Controller;
use App\Models\Lookups\UserTypeLookup;
use App\Models\Student;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class MessagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $orders = ['date' => 'created_at', 'from' => 'from_id', 'subject' => 'subject', 'to' => 'to_id'];
        $tab = $request->tab ?? 'inbox';
        $tab = !in_array($tab, ['inbox', 'sent', 'draft', 'archive']) ? 'inbox' : $tab;
        $order_by = $request->order_by ?? 'date';
        $order_by = !in_array($order_by, ['date', 'from', 'subject', 'to']) ? 'date' : $order_by;
        $desc = $order_by == 'date' ? 'DESC' : 'ASC';

        $inbox_messages = \Auth::user()
            ->received()
            ->where('archive_for_receiver', 0)
            ->where('delete_for_receiver', 0)
            ->orderBy($orders[$order_by], $desc)
            ->paginate(50, ['*'], 'i');

        $sent_messages = \Auth::user()
            ->sent()
            ->where('archive_for_sender', 0)
            ->where('delete_for_sender', 0)
            ->orderBy($orders[$order_by], $desc)
            ->paginate(50, ['*'], 's');

        $archive_messages = Message::
            where(function ($query){
                $query->where('from_id', '=', \Auth::user()->id)->where('delete_for_sender', 0)->where('archive_for_sender', 1);
            })
            ->orWhere(function ($query){
                $query->where('to_id', '=', \Auth::user()->id)->where('delete_for_receiver', 0)->where('archive_for_receiver', 1);
            })
            ->orderBy($orders[$order_by], $desc)
            ->paginate(50, ['*'], 's');


        return view('messages.index', compact('inbox_messages', 'sent_messages', 'archive_messages', 'order_by', 'tab'));
    }

    private function shouldAllow(Message $message)
    {
        if( !\Auth::user()->isStudent() )
        {
            return true;
        }
        if( in_array($message->id, \Auth::user()->sent()->pluck('id')->toArray()) )
        {
            return true;
        }
        if( in_array($message->id, \Auth::user()->received()->pluck('id')->toArray()) )
        {
            return true;
        }
        return false;
    }

    private function addCaseloadCondition(Builder &$query)
    {
        switch( auth()->user()->user_type )
        {
            case UserTypeLookup::TYPE_ADMIN:
            case UserTypeLookup::TYPE_ASSESSOR:
            case UserTypeLookup::TYPE_TUTOR:
            case UserTypeLookup::TYPE_VERIFIER:
                break;

            case UserTypeLookup::TYPE_STUDENT:
                $userIds = [];
                $result = DB::table('tr')
                    ->where('student_id', auth()->user()->id)
                    ->select('primary_assessor', 'secondary_assessor')
                    ->get();
                foreach($result AS $row)
                {
                    $userIds[] = $row->primary_assessor;
                    if(!is_null($row->secondary_assessor))
                    {
                        $userIds[] = $row->secondary_assessor;
                    }
                }
                $query->whereIn('users.id', $userIds);
                break;    
                
            case UserTypeLookup::TYPE_EMPLOYER_USER:
                $userIds = [];
                $result = DB::table('tr')
                    ->select('primary_assessor', 'secondary_assessor')
                    ->where('employer_location', auth()->user()->employer_location)
                    ->get();
                foreach($result AS $row)
                {
                    $userIds[] = $row->primary_assessor;
                    if(!is_null($row->secondary_assessor))
                    {
                        $userIds[] = $row->secondary_assessor;
                    }
                }
                $query->where(function($q) use ($userIds) {
                    return $q->where('employer_location', auth()->user()->employer_location)
                        ->orWhereIn('id', $userIds);
                });
                break;
            
            default:
                $query->where('id', false);
                break;
        }
    }

    public function compose(Message $message = null, Request $request)
    {
        $mode = $request->mode ?? 'new';

	    if(!is_null($message))
        {
            if( !$this->shouldAllow($message) )
            {
                abort(401);
            }
        }

        $query = User::select(\DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                        ->withActiveAccess()
                        ->where('id', '!=', auth()->user()->id)
                        ->where('is_support', '!=', 1)
                        ->orderBy('firstnames')
                        ;

        $this->addCaseloadCondition($query);
        $recipients = $query->pluck('name', 'id')->toArray();

	    if(auth()->user()->isStudent())
        {
		    //$_student = \Auth::user();
		    $_student = Student::findOrFail(auth()->user()->id);
            $recipients = [];
            foreach($_student->training_records AS $_tr)
            {
                if(isset($_tr->primaryAssessor->id) && $_tr->primaryAssessor->id != '' && !in_array($_tr->primaryAssessor->id, $recipients))
                {
                    $recipients[$_tr->primaryAssessor->id] = $_tr->primaryAssessor->full_name;
                }
                if(isset($_tr->secondaryAssessor->id) && $_tr->secondaryAssessor->id != '' && !in_array($_tr->secondaryAssessor->id, $recipients))
                {
                    $recipients[$_tr->secondaryAssessor->id] = $_tr->secondaryAssessor->full_name;
                }
            }
	    /*
            $recipients = \App\Models\User::select(\DB::raw("CONCAT(firstnames, ' ', surname) AS name"), "id")
                ->where('web_access', 1)
                ->where('id', '!=', \Auth::user()->id)
                ->where('is_support', '!=', 1)
                ->where('user_type', '!=', \App\Models\User::TYPE_STUDENT)
		->where('id', '!=', 5)
                ->orderBy('firstnames')
                ->pluck('name', 'id')->toArray();
	    */
        }

        return view('messages.compose', compact('recipients', 'message', 'mode'));
    }

    public function show(Message $message)
    {
	    if(!is_null($message))
        {
            if( !$this->shouldAllow($message) )
            {
                abort(401);
            }
        }	
        $isReceived = true;
        $person = \Auth::user();

        if($message->isSentBy($person))
        {
            $isReceived = false;
        }
        if($isReceived)
        {
            $message->setAsRead();
            $message->save();
            $person = $message->sender;
        }
        else
        {
            $person = $message->receiver;
        }

        $avatar_url = $person->avatar_url;

        return view('messages.show', compact('message', 'isReceived', 'avatar_url', 'person'));
    }

    public function setSingleMessageAsUnread(Message $message)
    {
        $message->setAsUnRead();
        $message->save();
        return redirect()->route('messages.index');
    }

    public function setSingleMessageAsRead(Message $message)
    {
        $message->setAsRead();
        $message->save();
        return redirect()->route('messages.index');
    }

    public function setSingleMessageAsArchive(Message $message, $multiple = false)
    {
        if( $message->isSentBy(\Auth::user()) )
        {
            $message->setAsArchiveForSender();
        }
        else
        {
            $message->setAsArchiveForReceiver();
        }
        $message->save();

        return !$multiple ? redirect()->route('messages.index') : '';
    }

    public function setSingleMessageAsDelete(Message $message, $multiple = false)
    {
        if($message->isDraft())
        {
            if($message->isSentBy(\Auth::user()))
                $message->delete();
        }
        else
        {
            if( $message->isSentBy(\Auth::user()) )
            {
                $message->setAsDeleteForSender();
            }
            else
            {
                $message->setAsDeleteForReceiver();
            }
            $message->save();
        }

        return !$multiple ? redirect()->route('messages.index') : '';
    }

    public function setMultipleMessageAsDelete(Request $request)
    {
        if(!isset($request->message_ids))
        {
            return;
        }

        foreach($request->message_ids as $message_id)
        {
            $message = Message::findOrFail($message_id);
            $this->setSingleMessageAsDelete($message, true);
        }

        return redirect()->route('messages.index');
    }


    public function setMultipleMessageAsUnread(Request $request)
    {
        if(!isset($request->message_ids))
        {
            return;
        }

        Message::whereIn('id', $request->message_ids)->update(['state' => MessageHandler::AVAILABLE]);

        return;
    }

    public function setMultipleMessageAsRead(Request $request)
    {
        if(!isset($request->message_ids))
        {
            return;
        }

        Message::whereIn('id', $request->message_ids)->update(['state' => MessageHandler::READ]);

        return;
    }

    public function setMultipleMessageAsArchive(Request $request)
    {
        if(!isset($request->message_ids))
        {
            return;
        }

        foreach($request->message_ids as $message_id)
        {
            $message = Message::findOrFail($message_id);
            $this->setSingleMessageAsArchive($message, true);
        }

        return redirect()->route('messages.index');
    }

    public function saveAsDraft(Request $request)
    {
        $sender = \Auth::user();

        list($message, $receiver) = User::createFromRequest($request->all());

        $cc = $request->cc;

        $cc = is_array($cc) ? array_map('intval', $cc) : [];

        $message->subject = $message->subject ?? 'Subject';
        $message->content = $message->content ?? 'Message Body';

        $draft = $sender->writes($message)
                ->to($receiver)
                ->draft()
                ->keep();

        return redirect()->route('messages.index');
    }

    public function send(Request $request)
    {
        $sender = \Auth::user();

        list($message, $receiver) = User::createFromRequest($request->all());

        $cc = $request->cc;

        $cc = is_array($cc) ? array_map('intval', $cc) : [];

        $sent = $sender->writes($message)
                ->to($receiver)
		        ->cc($cc)
                ->send();

	    Mail::to($receiver->primary_email)
            ->send(new NewMessageEmail($receiver, $sender));

        return redirect()->route('messages.index');
    }

    public function respond(Message $message, Request $request)
    {
        $sender = $message->sender;

        list($newMessage, $receiver) = User::createFromRequest($request->all());

        $sent = \Auth::user()->writes($newMessage)
                ->to($sender)
		        ->responds($message)
                ->send();

        return redirect()->route('messages.index');
    }

    public function draft_send(Message $message, Request $request)
    {
        $message->delete();

        $sender = \Auth::user();

        list($message, $receiver) = User::createFromRequest($request->all());

        $cc = $request->cc;

        $cc = is_array($cc) ? array_map('intval', $cc) : [];

        $sent = $sender->writes($message)
                ->to($receiver)
		        ->cc($cc)
                ->send();

        return redirect()->route('messages.index');
    }
}
