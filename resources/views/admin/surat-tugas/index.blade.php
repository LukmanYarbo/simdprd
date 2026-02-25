@extends('layouts.admin')

@section('title', 'Surat Tugas Anggota')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'bi-house-door-fill'],
    ['label' => 'Surat Tugas', 'icon' => 'bi-file-earmark-text-fill']
]" />
@endsection

@section('content')
    <livewire:admin.surat-tugas.manage-surat-tugas />
@endsection
