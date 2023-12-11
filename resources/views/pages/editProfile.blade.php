@extends('layouts.app')

@section('content')
    <div class="profile">
        <h1>Edit Profile</h1>

        <form method="POST" action="{{ route('profile.update', ['id' => $user->id]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="{{ $user->name }}" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ $user->email }}" required>

            <img src="{{ $user->getProfileImage() }}">

            <label for="photo">Change Photo:</label>
            <input name="photo" type="file">

            @if ($errors->has('photo'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('photo') }}</strong>
                </span>
            @endif

            <button type="submit">Save</button>
        </form>
    </div>
@endsection
