<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    
</head>

<style>
    

    body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 2em;
    }

    a {
        background-color: #3B3561;
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        border-radius: 1em;
    }

    a:hover {
        filter: brightness(130%);
    }
</style>

<body>
    <h1>Account Recovery</h1>

    <p>At your request, this email serves to recover your password for your <b>Task Squad</b> account.</p>

    <p><b>Username: </b> {{ $mailData['name'] }}</p>
    <p><b>Email: </b> {{ $mailData['email'] }}</p>
    <a href="{{ $mailData['token_link'] }}" style="margin-top:3em"><b>Recover Password</b></a>

</body>

</html>
