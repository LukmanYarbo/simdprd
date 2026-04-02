@extends('layouts.admin')

@section('title', 'Master Anggaran')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Master Anggaran', 'icon' => 'ti ti-report-money']
]" />
@endsection

@section('content')
    <livewire:admin.anggaran.daftar-anggaran />
@endsection
