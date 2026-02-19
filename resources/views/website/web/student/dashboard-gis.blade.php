@extends('website.web.admin.layouts.app')

@section('title', 'نەخشەی قوتابی')

@section('content')
    @include('website.web.shared.dashboard-gis', [
        'dashboardTitle' => 'نەخشەی قوتابی',
        'homeRoute' => route('student.dashboard'),
        'quickRoute' => route('student.departments.selection'),
        'quickLabel' => 'هەڵبژاردنی بەش',
        'quickIcon' => 'bi bi-list-check',
        'mapScope' => $mapScope ?? [],
    ])
@endsection
