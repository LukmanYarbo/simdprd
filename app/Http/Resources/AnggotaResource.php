<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnggotaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_anggota' => $this->nama_anggota,
            'nik' => $this->nik,
            'email' => $this->email,
            'no_telp' => $this->no_telp,
            'foto_url' => $this->foto_anggota ? asset('storage/' . $this->foto_anggota) : null,
            'status_keanggotaan' => $this->statusKeanggotaan?->nama,
            'jabatan' => $this->jabatan?->nama,
            'komisi' => $this->nama_komisi,
            // Tambahkan field lain yang dibutuhkan Flutter
        ];
    }
}
