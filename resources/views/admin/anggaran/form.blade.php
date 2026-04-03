@extends('layouts.admin')

@section('title', 'Form Anggaran')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Master Anggaran', 'url' => route('admin.anggaran.index'), 'icon' => 'ti ti-report-money'],
    ['label' => 'Form Anggaran', 'icon' => 'ti ti-pencil']
]" />
@endsection

@section('content')
    <livewire:admin.anggaran.anggaran-form :id="$id" />
@endsection
