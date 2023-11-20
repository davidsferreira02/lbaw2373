@extends('layouts.app')

@section('content')
    <div class="profile">
        <h1>Edit Profile</h1>

        <form method="POST" action="{{ route('profile.update', ['id' => $user->id]) }}">
            @csrf
            @method('PUT')

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="{{ $user->name }}" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ $user->email }}" required>

   

            <button type="submit">Save</button>
        </form>
    </div>
@endsection
