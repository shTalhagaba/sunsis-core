<?php

namespace App\Models\IQA;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Filterable;

class IqaPlan extends Model
{
    use Filterable;

    protected $table = 'iqa_plans';

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }

    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function entries()
    {
        return $this->hasMany(IqaPlanEntry::class, 'iqa_plan_id');
    }
}
