@extends('layouts.admin')

@section('title', 'Perubahan Status Anggota')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'bi-house-door-fill'],
    ['label' => 'Data Anggota', 'url' => route('admin.anggota.index'), 'icon' => 'bi-person-badge'],
    ['label' => 'Perubahan Status', 'icon' => 'bi-person-gear']
]" />
@endsection

@section('content')
    <livewire:admin.anggota.manage-status-change />
@endsection
