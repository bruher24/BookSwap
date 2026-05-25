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
{{--TODO: добавить функцию подтверждения почты--}}
<a href="https://www.google.com">Verify email address</a>
</body>
</html>
