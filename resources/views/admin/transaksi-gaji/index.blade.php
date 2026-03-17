@extends('layouts.admin')

@section('title', 'Proses Gaji')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Proses Gaji', 'icon' => 'ti ti-coins']
]" />
@endsection

@section('content')
    <livewire:admin.gaji.proses-gaji />
@endsection
