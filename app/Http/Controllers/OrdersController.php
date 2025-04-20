<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrdersController extends Controller
{
    public function checkout()
    {
        $cartItems = Auth::user()->cart()->get();
        return view('orders.checkout', compact('cartItems'));
    }

    public function store(Request $request)
    {
        // Valideer het formulier zodat alle velden verplicht zijn.
        // Vul het formulier terug in, en toon de foutmeldingen.
        $validated = $request->validate([
            'address' => 'required|string',
            'payment_method' => 'required|string',
        ]);
        // Maak een nieuw "order" met de gegevens uit het formulier in de databank
        // Zorg ervoor dat hett order gekoppeld is aan de ingelogde gebruiker.
        $order = new Order();
        $order->user_id = Auth::id();  // Koppel de order aan de ingelogde gebruiker
        $order->address = $validated['address'];
        $order->payment_method = $validated['payment_method'];
        $order->save();
        // Zoek alle producten op die gekoppeld zijn aan de ingelogde gebruiker (shopping cart)
        // Overloop alle gekoppelde producten van een user (shopping cart)
        // Attach het product, met bijhorende quantity en size, aan het order
        // https://laravel.com/docs/9.x/eloquent-relationships#retrieving-intermediate-table-columns
        // Detach tegelijk het product van de ingelogde gebruiker zodat de shopping cart terug leeg wordt
        $cartItems = Auth::user()->cart()->get();
        foreach ($cartItems as $cartItem) {
            $order->products()->attach($cartItem->id, [
                'quantity' => $cartItem->pivot->quantity,  // We nemen aan dat de 'cart' tabel een tussenliggende tabel is met 'quantity' en 'size'
                'size' => $cartItem->pivot->size,
            ]);
        }
        Auth::user()->cart()->detach();
        // BONUS: Als er een discount code in de sessie zit koppel je deze aan het discount_code_id in het order model
        // Verwijder nadien ook de discount code uit de sessie
        if ($discountCode = session('discount_code')) {
            $order->discount_code_id = $discountCode->id;
            $order->save();

            session()->forget('discount_code');
        }
        // BONUS: Stuur een e-mail naar de gebruiker met de melding dat zijn bestelling gelukt is,
        // samen met een knop of link naar de show pagina van het order
        Mail::to(Auth::user()->email)->send(new OrderConfirmation($order));

        // Redirect naar de show pagina van het order en pas de functie daar aan
        return redirect()->route('orders.show', $order->id);
    }

    public function index()
    {
        // Zoek alle orders van de ingelogde gebruiker op. Vervang de "range" hieronder met de juiste code
        $orders = Auth::user()->orders;

        // Pas de views aan zodat de juiste info van een order getoond word in de "order" include file
        return view('orders.index', [
            'orders' => $orders
        ]);
    }

    public function show($id)
    { // Order $order
        // Beveilig het order met een GATE zodat je enkel jouw eigen orders kunt bekijken.
        $order = Order::findOrFail($id);

        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        // In de URL wordt het id van een order verstuurd. Zoek het order uit de url op.
        // Zoek de bijbehorende producten van het order hieronder op.
        $products = $order->products;

        // Geef de juiste data door aan de view
        // Pas de "order-item" include file aan zodat de gegevens van het order juist getoond worden in de website
        return view('orders.show', [
            'order' => $order,
            'products' => $products
        ]);
    }
}
