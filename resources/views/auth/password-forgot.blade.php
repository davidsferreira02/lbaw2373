@extends('layouts.app')

@section('page_title', 'Forgot Password')

@section('content')

<a href="{{ route('login') }}" class="btn btn-primary">
    <i class="fas fa-arrow-left" style="color: black;"></i> 
</a>
    <div class=" p-5 mw-10 d-flex align-items-center justify-content-around">

        <body>
            <form method="POST" action="{{ url('/reset_password_without_token') }}" style="max-width: 40em">
                {{ csrf_field() }}

                <h1 class="h3 mb-4 mt-5 font-weight-normal">Forgot your password?</h1>

              <p>If you forget your password, you can recover your account by providing the email address associated with it. If the entered email address is linked to an account, an email will be sent to reset the password.
                </p>

                <fieldset>
                    

                    <label for="inputEmail" data-toggle="tooltip" data-placement="top"
                        title="Email associated to your account">Email Address <small>(Required)</small></label>
                    <input type="email" id="inputEmail" class="form-control mb-3" placeholder="Email" name="email"
                        required>

                    @if (session('error'))
                        <div class="alert alert-danger">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
                        </div>
                    @endif

                </fieldset>

                <button class="btn btn-lg btn-primary btn-block mt-4 w-100" type="submit">Send Email</button>


               
            </form>


    </div>
    </div>
@endsection
