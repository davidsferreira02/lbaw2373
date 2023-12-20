@extends('layouts.app')

@section('content')



    <a href="{{ route('profile', ['id'=>$user->id]) }}" class="btn btn-primary">
        <i class="fas fa-arrow-left"></i> Back
    </a>

    <h1>Edit Profile</h1>
    <div class="profile">

        <form method="POST" action="{{ route('profile.update', ['id' => $user->id]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="{{ $user->name }}" required>
            <span class="error">
                {{ $errors->first('name') }}
            </span>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ $user->email }}" required>
            <span class="error">
                {{ $errors->first('email') }}
            </span>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="{{ $user->username }}" required>
            <span class="error">
                {{ $errors->first('username') }}
            </span>

            <img src="{{ $user->getProfileImage() }}" alt="profile_image">

            <label for="image">Change Photo:</label>
            <input name="image" type="file" id = "image" class="from-control-file">
            @if ($errors->has('profile_image'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('profile_image') }}</strong>
                </span>
            @endif

            <button type="submit">Save</button>
        </form>
    </div>
@endsection
