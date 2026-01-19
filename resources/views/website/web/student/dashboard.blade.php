@extends('website.web.student.layouts.app')

@section('content')

name : {{ Auth::user()->name }}

<h1><a href="{{ route('student.mbti.index') }}">MBTI</a></h1>
@endsection
