@php use App\Enums\TradeOfferStatus; @endphp
    <!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trade Offer Updated</title>
</head>
<body>
<h1>Hello, {{ $receiver->name }}</h1>
<p>Trade offer's №{{ $tradeOffer->id }} status has been updated to "{{ $tradeOffer->status->label() }}"!</p>
<p>Follow the link to see the details:</p>
<a href="{{ URL::signedRoute('api.trade_offers.show', ['trade_offer' => $tradeOffer->id]) }}">Show the details</a>
</body>
</html>
