<a href="{{ route('favorites.toggle', $product) }}" class="text-2xl bg-white px-4 py-1">

    @if (Auth::user() && Auth::user()->favorites()->where('product_id', $product->id)->exists())
        <i class="fa-solid fa-heart text-red-500"></i>
        @else
        <i class="fa-regular fa-heart text-gray-500"></i>
        @endif
</a>
