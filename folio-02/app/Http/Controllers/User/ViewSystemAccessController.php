<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lookups\UserTypeLookup;

class ViewSystemAccessController extends Controller
{
    public function showLogins()
    {
	if(\Auth::user()->isStudent())
        {
            abort(403, '403. Unauthorised');
        }
	if(\Auth::user()->getOriginal('user_type') != UserTypeLookup::TYPE_ADMIN)
        {
            abort(403, 'Forbidden.');
        }
        $logs = \App\Models\AuthenticationLog::orderBy('login_at', 'desc')->paginate(20);

        return view('admin.successful-logins', compact('logs'));
    }

    public function showFailedLogins()
    {
	if(\Auth::user()->isStudent())
        {
            abort(403, '403. Unauthorised');
        }
	if(\Auth::user()->getOriginal('user_type') != UserTypeLookup::TYPE_ADMIN)
        {
            abort(403, 'Forbidden.');
        }
        $logs = \DB::table('failed_logins')
        		->select('user_id',
        			\DB::raw('(SELECT CONCAT(users.firstnames, " ", users.surname) FROM users WHERE users.id = failed_logins.user_id) AS user_name'),
        			'email_address',
        			'ip_address',
        			'user_agent',
        			'created_at' )
        		->orderBy('created_at', 'DESC')
        		->paginate(20);

        return view('admin.failed-logins', compact('logs'));
    }
}
