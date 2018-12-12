@extends('layouts.master')
@section('content')
    <h1>This is the post number {{$id}} and password {{$password}}</h1>
    @if(count($pepole))
        <ul>
            <h2>My frindes</h2>
            @foreach ($pepole as $person)
                <li>{{$person}}</li>
            @endforeach
        </ul>
    @endif
@endsection
@section('footer')
    <script>alert('This post for');</script>
@endsection