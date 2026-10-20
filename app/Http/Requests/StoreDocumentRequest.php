<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isAdmin();
    }

    /**
     * Prepare inputs for validation (Defense-in-depth against XSS & whitespace pollution).
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => is_string($this->title) ? strip_tags(trim($this->title)) : $this->title,
            'document_number' => is_string($this->document_number) ? strip_tags(trim($this->document_number)) : $this->document_number,
            'description' => is_string($this->description) ? strip_tags(trim($this->description)) : $this->description,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'document_number' => [
                Rule::requiredIf(fn () => $this->input('type') !== 'Berkas'),
                'nullable',
                'string',
                'max:100',
                Rule::unique('documents', 'document_number'),
            ],
            'document_date' => ['required', 'date'],
            'tanggal_acara' => ['nullable', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', Rule::in(['Surat Masuk', 'Surat Keluar', 'Berkas', 'Laporan'])],
            'sub_type' => [
                Rule::requiredIf(fn () => $this->input('type') === 'Laporan'),
                'nullable',
                Rule::in(['Laporan BPN', 'Laporan Dispenda']),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'file' => [
                'required',
                'file',
                'mimes:pdf,docx,jpg,jpeg,png',
                'max:20480', // max 20MB
            ],
        ];
    }

    /**
     * Custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'document_number.required' => 'Nomor Dokumen wajib diisi untuk kategori ini.',
            'document_number.required_if' => 'Nomor Dokumen wajib diisi untuk kategori ini.',
            'document_number.unique' => 'Nomor Dokumen sudah terdaftar dalam sistem.',
            'document_date.required' => 'Tanggal Dokumen wajib diisi.',
            'title.required' => 'Perihal / Judul Dokumen wajib diisi.',
            'type.required' => 'Tipe Dokumen wajib dipilih.',
            'type.in' => 'Tipe Dokumen tidak valid.',
            'sub_type.required_if' => 'Sub-Kategori Laporan (BPN / Dispenda) wajib dipilih jika tipe Laporan.',
            'file.required' => 'File dokumen arsip wajib diunggah.',
            'file.file' => 'Berkas yang diunggah harus berupa file yang valid.',
            'file.mimes' => 'Format file tidak valid. Hanya menerima format: .pdf, .docx, .jpg, .jpeg, .png.',
            'file.max' => 'Ukuran file maksimal 20 MB.',
        ];
    }
}
