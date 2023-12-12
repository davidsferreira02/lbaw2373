@extends('layouts.app')

@section('content') 
    <header>
        <h1>LBAW Tutorial 02 - File Storage</h1>
    </header>
    <main>
       @include('partials.feedback')
        <div class="content">
            <h3>Profile</h3>
            <h4>&#64;{{ $user->username }}</h4>
            <img src="{{ $user->getProfileImage() }}">
            <form method="POST" action="/file/upload" enctype="multipart/form-data">
                @csrf
                <input name="file" type="file" required>
                <input name="id" type="number" value="{{ $user->id }}" hidden>
                <input name="type" type="text" value="profile" hidden>
                <button type="submit">Submit</button>
            </form>
        </div>
    </main>
    <footer>
        <p>LBAW @ 2023</p>
    </footer>
@endsection
