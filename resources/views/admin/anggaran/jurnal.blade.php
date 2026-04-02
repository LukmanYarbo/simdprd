@extends('layouts.admin')

@section('title', 'Jurnal LRA')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Jurnal LRA', 'icon' => 'ti ti-history']
]" />
@endsection

@section('content')
    <livewire:admin.anggaran.jurnal-lra-index />
@endsection
