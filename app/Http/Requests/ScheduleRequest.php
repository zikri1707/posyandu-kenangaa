<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'posyandu_id' => 'required|exists:posyandus,id',
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required|string|max:255',
            'activity_type' => 'required|in:imunisasi,penimbangan,pemeriksaan,edukasi,lainnya',
            'participant_limit' => 'nullable|integer|min:1',
            'status' => 'sometimes|in:draft,published,cancelled'
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->input('end_time') <= $this->input('start_time')) {
                $validator->errors()->add('end_time', 'End time must be after start time');
            }
        });
    }
}