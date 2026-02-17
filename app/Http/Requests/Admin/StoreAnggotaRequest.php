<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnggotaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nik' => ['required', 'string', 'unique:anggota,nik'],
            'nokk' => ['required', 'string'],
            'nama_anggota' => ['required', 'string', 'max:255'],
            'tempat_lahir' => ['required', 'string'],
            'tgl_lahir' => ['required', 'date'],
            'id_agama' => ['required', 'exists:agama,id'],
            'jk' => ['required', 'in:L,P'],
            'id_status_kawin' => ['required', 'exists:status_kawin,id'],
            'jmlh_istri' => ['required', 'integer', 'min:0'],
            'jmlh_anak' => ['required', 'integer', 'min:0'],
            'no_telp' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:anggota,email'],
            'no_rekening' => ['required', 'string'],
            'prov' => ['required', 'string'],
            'kab' => ['required', 'string'],
            'kec' => ['required', 'string'],
            'desa' => ['required', 'string'],
            'alamat_lengkap' => ['required', 'string'],
            'id_status_keanggotaan' => ['required', 'exists:status_keanggotaan,id'],
            'id_dprd' => ['required', 'exists:jabatan_dprd,id'],
            'tgl_mulai' => ['required', 'date'],
            'tgl_berhenti' => ['nullable', 'date'],
            'status_bpjs' => ['required', 'in:Y,T'],
            'no_bpjs' => ['nullable', 'string', 'required_if:status_bpjs,Y'],
            'status_jkk' => ['required', 'in:Y,T'],
            'no_jkk' => ['nullable', 'string', 'required_if:status_jkk,Y'],
            'status_jkm' => ['required', 'in:Y,T'],
            'no_jkm' => ['nullable', 'string', 'required_if:status_jkm,Y'],
            'status_tjgn_perum' => ['required', 'in:Y,T'],
            'status_tjgn_transport' => ['required', 'in:Y,T'],
            'foto_anggota' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }
}
