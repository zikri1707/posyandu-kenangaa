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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'required|string|max:500',
            'posyandu_id' => 'required|exists:posyandus,id',
            'status' => 'nullable|in:upcoming,ongoing,completed,cancelled',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul jadwal wajib diisi.',
            'title.max' => 'Judul jadwal maksimal 255 karakter.',
            'start_time.required' => 'Waktu mulai wajib diisi.',
            'start_time.date' => 'Format waktu mulai tidak valid.',
            'end_time.required' => 'Waktu selesai wajib diisi.',
            'end_time.after' => 'Waktu selesai harus setelah waktu mulai.',
            'location.required' => 'Lokasi kegiatan wajib diisi.',
            'posyandu_id.required' => 'Posyandu wajib dipilih.',
            'posyandu_id.exists' => 'Posyandu yang dipilih tidak valid.',
            'status.in' => 'Status tidak valid.',
        ];
    }
}
