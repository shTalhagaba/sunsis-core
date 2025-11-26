<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;

class DocumentSignature extends Model
{
    protected $table = 'document_signatures';

    protected $guarded = ['model_id', 'model_type'];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public static function logSignatures($model, $signatorySystemId = '', $signatoryName = '')
    {
        $model->signatures()->create([
            'signatory_system_id' => $signatorySystemId == '' ? auth()->user()->id : $signatorySystemId,
            'signatory_name' => $signatoryName == '' ? auth()->user()->full_name : $signatoryName,
            'signatory_system_user_type' => $signatorySystemId == '' ? auth()->user()->user_type : DB::table('users')->where('id', $signatorySystemId)->value('user_type'),
            'signatory_ip_address' => request()->ip(),
            'signatory_user_agent' => request()->userAgent(),
        ]);
    }

}