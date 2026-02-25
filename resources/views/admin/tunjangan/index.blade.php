@extends('layouts.admin')

@section('title', 'Master Data Tunjangan')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'bi-house-door-fill'],
    ['label' => 'Master Tunjangan', 'icon' => 'bi-credit-card-fill']
]" />
@endsection

@section('content')
    <livewire:admin.tunjangan.manage-tunjangan />
@endsection
