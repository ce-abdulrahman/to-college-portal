@extends('website.web.admin.layouts.app')

@section('title', 'نەخشەی مامۆستا')

@section('content')
    @include('website.web.shared.dashboard-gis', [
        'dashboardTitle' => 'نەخشەی مامۆستا',
        'homeRoute' => route('teacher.dashboard'),
        'quickRoute' => route('teacher.departments.index'),
        'quickLabel' => 'بەشەکان',
        'quickIcon' => 'bi bi-diagram-3',
        'mapScope' => $mapScope ?? [],
    ])
@endsection
