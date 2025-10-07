@extends('website.web.student.layouts.app')

@section('content')

name : {{ Auth::user()->name }}

@endsection
