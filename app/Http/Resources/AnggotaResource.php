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
        $data = parent::toArray($request);
        
        $data['foto_url'] = $this->foto_anggota ? asset('storage/' . $this->foto_anggota) : null;
        $data['status_keanggotaan'] = $this->statusKeanggotaan?->nama;
        $data['jabatan'] = $this->jabatan?->nama;
        $data['komisi'] = $this->nama_komisi;
        
        return $data;
    }
}
