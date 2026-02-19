@extends('website.web.admin.layouts.app')

@section('title', 'نەخشەی سەنتەر')

@section('content')
    @include('website.web.shared.dashboard-gis', [
        'dashboardTitle' => 'نەخشەی سەنتەر',
        'homeRoute' => route('center.dashboard'),
        'quickRoute' => route('center.students.index'),
        'quickLabel' => 'لیستی قوتابی',
        'quickIcon' => 'bi bi-people',
        'mapScope' => $mapScope ?? [],
    ])
@endsection
