@extends('layouts.admin')

@section('title', 'Master Data Tunjangan')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Master Tunjangan', 'icon' => 'ti ti-credit-card']
]" />
@endsection

@section('content')
    <livewire:admin.tunjangan.manage-tunjangan />
@endsection
