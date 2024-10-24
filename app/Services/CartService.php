<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;


class CartService
{

    public function create(int $product_id,int $quantity,float $price): Cart
    {
        return Cart::create([
            'user_id' => auth()->user()->id ?? null,
            'product_id' => $product_id,
            'session_id' => session()->getId(),
            'quantity' => $quantity,
            'price' => $price
        ]);
    }

    public function update(Cart $cart, $quantity = null, float $price = null): bool
    {
        return  $cart->update([
            'quantity' => $quantity ?? $cart->getAttribute('quantity'),
            'price' => $price ?? $cart->getAttribute('price'),
        ]);
    }

    public function delete(Cart $cart): int
    {
        return $cart->delete();
    }

    public function getCartItems(array $session = null, ...$relations): Collection
    {
        $user = auth()->user();

        return Cart::with($relations)->when($user, function ($query, $user) {
            $query->where('user_id', $user->id);
        })->orWhere('session_id',$session)->get();
    }

    public function getCartItemsWithPaginator(int $limit, array $session = null, ...$relations): LengthAwarePaginator
    {
        $user = auth()->user();

        return Cart::when($user, function ($query, $user) {
            $query->where('user_id', $user->id);
        })->orWhere('session_id',$session)->paginate($limit);
    }

    public function totalPrice(array $session = null,...$relations): int
    {

        return $this->getCartItems($session,...$relations)->pluck('total')->sum();
    }

    public function countCartItemsPerPage(int $limit, array $session = null,...$relations): int
    {
        $user = auth()->user();

        $cart_items = Cart::with($relations)->when($user, function ($query, $user) {
            $query->where('user_id', $user->id);
        })->orWhere('session_id',$session)->paginate($limit)->items();

        return count($cart_items);
    }

    public function count(array $session = null, ...$relations): int
    {
        $user = auth()->user();

        return Cart::with($relations)->when($user, function ($query, $user) {
            $query->where('user_id', $user->id);
        })->orWhere('session_id',$session)->count();
    }


}
