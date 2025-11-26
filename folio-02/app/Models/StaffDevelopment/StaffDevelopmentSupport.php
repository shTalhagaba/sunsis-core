<?php

namespace App\Models\StaffDevelopment;

use App\Models\User;
use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;

class StaffDevelopmentSupport extends Model 
{
    use Filterable;
    
    protected $table = 'staff_development_support';

    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $dates = [
        'provision_date',
        'support_to_sign_date',
        'support_from_sign_date',
    ];

    public function supportTo()
    {
        return $this->belongsTo(User::class, 'support_to_id');
    }

    public function supportFrom()
    {
        return $this->belongsTo(User::class, 'support_from_id');
    }

    public function signedBySupportPersonnel()
    {
        return $this->support_from_sign;
    }

    public function fullySigned()
    {
        return $this->support_from_sign && $this->support_to_sign;
    }

    public function canBeSignedBy(User $user)
    {
        return $this->signedBySupportPersonnel() && 
            !$this->fullySigned() && 
            $user->id === $this->support_to_id;
    }

}
