<?php

namespace App\Models\Training;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PortfolioUnitEqa extends Model
{
    protected $table = 'portfolio_units_eqa';

    protected $guarded = [];

    public function unit()
    {
        return $this->belongsTo(PortfolioUnit::class, 'portfolio_unit_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

}
