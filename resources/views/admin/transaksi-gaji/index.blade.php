@extends('layouts.admin')

@section('title', 'Proses Gaji')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'bi-house-door-fill'],
    ['label' => 'Proses Gaji', 'icon' => 'bi-cash-coin']
]" />
@endsection

@section('content')
    <livewire:admin.gaji.proses-gaji />
@endsection
