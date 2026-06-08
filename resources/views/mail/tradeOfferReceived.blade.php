<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trade Offer Received</title>
</head>
<body>
<h1>Hello, {{ $receiver->name }}</h1>
<p>You've got an new trade offer!</p>
<p>Follow the link to see the details:</p>
<a href="{{ URL::signedRoute('api.trade_offers.show', ['trade_offer' => $tradeOffer->id]) }}">Show the details</a>
</body>
</html>
