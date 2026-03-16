@extends('layouts.admin')

@section('title', 'Data Potongan')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Master Gaji dan Tunjangan', 'icon' => 'ti ti-wallet'],
    ['label' => 'Data Potongan', 'icon' => 'ti ti-scissors']
]" />
@endsection

@section('content')
    <livewire:admin.potongan.manage-potongan />
@endsection
