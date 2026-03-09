@extends('layouts.admin')

@section('title', 'Data Potongan')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'bi-house-door-fill'],
    ['label' => 'Master Gaji dan Tunjangan', 'icon' => 'bi-wallet2'],
    ['label' => 'Data Potongan', 'icon' => 'bi-scissors']
]" />
@endsection

@section('content')
    <livewire:admin.potongan.manage-potongan />
@endsection
