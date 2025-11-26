<?php

namespace App\Http\Requests;

use App\Helpers\AppHelper;
use App\Models\UserEvents\UserEvent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserEventRequest extends FormRequest
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
        $validTypes = AppHelper::getUserEventsTypes();
        $event =  $this->route('event');;

        $rules = [
            'title' => 'required|min:3|max:255',
            'type' => 'nullable',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i',
            'event_type' => [
                'required_if:type,Event',
                'nullable',
                'numeric',
                'in:' . implode(',', array_keys($validTypes)),
            ],
            'event_status' => 'nullable|numeric|in:' . implode(',', [
                UserEvent::STATUS_BOOKED,
                UserEvent::STATUS_CANCELLED,
                UserEvent::STATUS_CLOSED,
                UserEvent::STATUS_ASSIGNED,
                UserEvent::STATUS_COMPLETED,
                UserEvent::STATUS_IN_PROGRESS,
                UserEvent::STATUS_SIGNOFF,
            ]),
            'personal' => ['required_if:type,Event', 'boolean'],
            'description' => 'max:800',
            'color' => 'nullable|string|max:8',
            'location' => 'nullable|string|max:250',
            'task_type' => [
                'required_if:type,Task',
                'nullable',
                'string',
                Rule::in(['rag_rating', 'deep_dive', 'otla', '4_week_audit', 'iqa_sample_plan']),
            ],
            'assign_iqa_id' => [
                'required_if:type,Task',
                'nullable',
                'exists:users,id'
            ],
        ];

        // Add stricter rules only when creating (POST)
        if ($this->isMethod('post')) {
            $rules['start_date'] .= '|after_or_equal:today';
        }

        // Only check changed end_date on update
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            if ($event && $this->end_date !=  \Carbon\Carbon::parse($event->end)->toDateString()) {
                $rules['end_date'] .= '|after_or_equal:today';
            }
            
            if ($event && $this->start_date !=  \Carbon\Carbon::parse($event->start)->toDateString()) {
                $rules['start_date'] .= '|after_or_equal:today';
            }
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->start_date && $this->end_date && $this->start_time && $this->end_time) {
                $start = \Carbon\Carbon::parse($this->start_date . ' ' . $this->start_time);
                $end   = \Carbon\Carbon::parse($this->end_date . ' ' . $this->end_time);

                if ($end->lessThanOrEqualTo($start)) {
                    $validator->errors()->add('end_time', 'The end datetime must be after the start datetime.');
                }
            }
        });
    }
}