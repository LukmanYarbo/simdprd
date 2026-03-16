@extends('layouts.admin')

@section('title', 'Surat Tugas Anggota')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Surat Tugas', 'icon' => 'ti ti-file-certificate']
]" />
@endsection

@section('content')
    <livewire:admin.surat-tugas.manage-surat-tugas />
@endsection
