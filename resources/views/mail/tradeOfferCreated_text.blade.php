<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trade Offer Created</title>
</head>
<body>
<h1>Hello, {{ $receiver->name }}</h1>
<p>There is new Trade offer №{{ $tradeOffer->id }} for you created by {{ $sender->name }}!</p>
<p>Follow the link to see the details:</p>
<a href="{{ URL::signedRoute('api.trade_offers.show', ['tradeOffer' => $tradeOffer->id]) }}">Show the details</a>
</body>
</html>
