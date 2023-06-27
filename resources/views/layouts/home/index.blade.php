@extends('layouts.app-master')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.6.0/font/bootstrap-icons.css" rel="stylesheet">

@section('content')
    <div class="bg-light p-5 rounded">
        @auth
        <h1>Dashboard <i class="bi bi-emoji-smile-fill"></i></h1>
        <p class="lead">Only authenticated users can access this section.</p>
        @endauth

        @guest
        <h1>Homepage <i class="bi bi-emoji-frown-fill"></i></h1>
        <p class="lead">You're viewing the home page. Please login to view the restricted data.</p>
        @endguest
    </div>
@endsection