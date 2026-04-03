@extends('layouts.admin')

@section('title', 'Form Kertas Kerja')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Kertas Kerja', 'url' => route('admin.kertas-kerja.index'), 'icon' => 'ti ti-file-analytics'],
    ['label' => 'Form Kertas Kerja', 'icon' => 'ti ti-pencil']
]" />
@endsection

@section('content')
    <livewire:admin.kertas-kerja.kertas-kerja-form :id="$id" />
@endsection
