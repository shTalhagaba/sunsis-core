<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedLogin extends Model
{
	protected $table = 'failed_logins';

    protected $fillable = [
    	'user_id', 'email_address', 'ip_address', 'user_agent'
	];

	public static function record($user = null, $email, $ip, $user_agent)
	{
		return static::create([
			'user_id' => is_null($user) ? null : $user->id,
			'email_address' => $email,
			'ip_address' => $ip,
			'user_agent' => $user_agent
		]);
	}

	public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d/m/Y H:i:s');
    }
}
