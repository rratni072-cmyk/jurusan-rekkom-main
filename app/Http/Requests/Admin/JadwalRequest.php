<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class JadwalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // Derive `tahun_ajaran` (string YYYY/YYYY) dari 2 input number split.
        // Format ini yang disimpan di DB — backward compat dengan kode existing.
        $tm = $this->input('tahun_mulai');
        $ta = $this->input('tahun_akhir');
        if ($tm !== null && $ta !== null && $tm !== '' && $ta !== '') {
            $this->merge([
                'tahun_ajaran' => sprintf('%04d/%04d', (int) $tm, (int) $ta),
            ]);
        }

        $this->merge([
            'is_active' => $this->has('is_active'),
        ]);
    }

    public function rules(): array
    {
        $rules = [
            'program_studi_id' => 'nullable|exists:program_studis,id',
            'program_studi' => 'required|string|max:255',
            // Tahun ajaran via 2 input number (split, auto-link di JS).
            // Range wajar: 2015–2050 (mulai), 2016–2051 (akhir).
            'tahun_mulai' => 'required|integer|min:2015|max:2050',
            'tahun_akhir' => 'required|integer|min:2016|max:2051|gt:tahun_mulai',
            // tahun_ajaran sudah di-derive di prepareForValidation;
            // tetap divalidasi sebagai safety check terhadap derived value.
            'tahun_ajaran' => ['required', 'string', 'regex:/^\d{4}\/\d{4}$/', 'max:9'],
            'semester' => 'required|in:Ganjil,Genap',
            'is_active' => 'boolean',
        ];

        if ($this->isMethod('post')) {
            $rules['file_path'] = 'required|file|mimes:pdf|max:10240';
        } else {
            $rules['file_path'] = 'nullable|file|mimes:pdf|max:10240';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'tahun_mulai.required' => 'Tahun mulai wajib diisi.',
            'tahun_mulai.integer' => 'Tahun mulai harus berupa angka.',
            'tahun_mulai.min' => 'Tahun mulai minimal 2015.',
            'tahun_mulai.max' => 'Tahun mulai maksimal 2050.',
            'tahun_akhir.required' => 'Tahun akhir wajib diisi.',
            'tahun_akhir.integer' => 'Tahun akhir harus berupa angka.',
            'tahun_akhir.gt' => 'Tahun akhir harus lebih besar dari tahun mulai.',
            'tahun_ajaran.regex' => 'Format tahun ajaran tidak valid.',
            'semester.required' => 'Semester wajib dipilih (Ganjil/Genap).',
            'semester.in' => 'Semester harus Ganjil atau Genap.',
            'file_path.required' => 'File jadwal wajib diunggah.',
            'file_path.mimes' => 'File harus berformat PDF.',
            'file_path.max' => 'Ukuran file maksimal 10MB.',
        ];
    }
}
