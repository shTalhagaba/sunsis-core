<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreOtjRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isActive();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:500',
            'type' => 'required|numeric|in:' . DB::table('lookup_otj_types')->pluck('id')->implode(','),
            'date' => 'required|date_format:"Y-m-d"',
            'start_time' => 'required|date_format:"H:i"',
            'duration' => 'required|date_format:"H:i"',
            'details' => 'nullable|max:1200',
            'assessor_comments' => 'nullable|max:1200',
            'otj_evidence' => 'nullable',
            'ksbElements' => 'nullable|array',
        ];
    }
}
