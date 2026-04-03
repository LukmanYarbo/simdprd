@extends('layouts.admin')

@section('title', 'Kertas Kerja Anggaran')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Kertas Kerja', 'icon' => 'ti ti-file-analytics']
]" />
@endsection

@section('content')
    <livewire:admin.kertas-kerja.daftar-kertas-kerja />
@endsection
