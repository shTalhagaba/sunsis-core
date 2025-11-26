<?php

namespace App\Models\FSAssessment;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TestSessionAssessment extends Model
{
    protected $fillable = ['test_session_id', 'assessor_id', 'status', 'comments'];

    /**
     * Relationship: An assessment belongs to a test session.
     */
    public function testSession()
    {
        return $this->belongsTo(TestSession::class);
    }

    /**
     * Relationship: An assessment is done by an assessor (User).
     */
    public function assessor()
    {
        return $this->belongsTo(User::class, 'assessor_id');
    }
}
