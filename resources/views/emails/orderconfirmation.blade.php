<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestelling Bevestigd</title>
</head>
<body>
    <h1>Beste {{ $order->voornaam }} {{ $order->naam }},</h1>

    <p>Bedankt voor je bestelling! We hebben je bestelling succesvol ontvangen en zullen deze zo snel mogelijk verwerken.</p>

    <h2>Bestellingsdetails:</h2>
    <p><strong>Ordernummer:</strong> {{ $order->id }}</p>
    <p><strong>Datum:</strong> {{ $order->created_at->format('d-m-Y') }}</p>

    <h3>Gegevens voor verzending:</h3>
    <p><strong>Adres:</strong> {{ $order->straat }} {{ $order->huisnummer }}, {{ $order->postcode }} {{ $order->woonplaats }}</p>

    <h3>Bestelde producten:</h3>
    <ul>
        @foreach ($order->products as $product)
            <li>
                {{ $product->name }} - Aantal: {{ $product->pivot->quantity }} - Maat: {{ $product->pivot->size }}
            </li>
        @endforeach
    </ul>

    <h3>Betalingsmethode:</h3>
    <p>{{ $order->payment_method ?? 'Niet opgegeven' }}</p>

    <p>Je kunt je bestelling volgen in je account. Als je vragen hebt, neem dan gerust contact met ons op.</p>

    <p>Met vriendelijke groet,<br>
    Het Team</p>
</body>
</html>
