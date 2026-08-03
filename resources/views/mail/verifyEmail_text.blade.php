<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email verification</title>
</head>
<body>
<h1>Hello, {{ $user->name }}</h1>
<p>You need to verify your email to use all of the BookSwap's functionality</p>
<p>Follow the link bellow to do that:</p>
<a href="{{ URL::signedRoute('api.auth.verifyEmail', ['userId' => $user->id]) }}">Verify email address</a>
</body>
</html>
