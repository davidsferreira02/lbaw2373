@extends('layouts.app')

@section('content')

<style>
    .profile {
    max-width: 350px; /* Adjust the width of the square */
    margin: 50px auto; /* Center the square horizontally */
    padding: 20px;
    border: 1px solid #ccc; /* Border styling */
    border-radius: 10px; /* Rounded corners */
    text-align: center;
    background-color: none; /* Background color */
}

.profile h1 {
    margin-bottom: 20px;
}

.profile form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.profile label {
    margin-bottom: 10px;
}

.profile input[type="text"],
.profile input[type="email"],
.profile input[type="file"] {
    width: 100%;
    padding: 8px;
    margin-bottom: 15px;
    border-radius: 25px;
    border: 1px solid #ccc;
}

.profile button[type="submit"] {
    border-radius: 5px;
    border: none;
    background-color: #51A3A3;
    color: #fff;
    cursor: pointer;
    text-align: center;
}
</style>

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
