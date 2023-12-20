@extends('layouts.app')

@section('page_title', 'Email Sent')

@section('content')

<a href="{{ route('login') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left" style="color: black;"></i> 
</a>
    <div class="p-5 mw-10 m-3">

        <body class="text-center">
            <h1 class="mb-4">Email Sent</h1>
            <p>There was an email sent. To proceed, please check your inbox. You can now close this page.</p>
        </body>

    </div>
@endsection
