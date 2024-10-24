<?php

namespace App\Observers;

use App\Models\Cart;
use App\Services\CartService;
use JetBrains\PhpStorm\NoReturn;

class CartObserver
{
    private CartService $cartService;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }



    #[NoReturn]
    public function creating(Cart $cart): bool
    {
        if($cart->getAttribute('total') == null)
        {
            $price = $cart->getAttribute('price');
            $quantity = $cart->getAttribute('quantity');
            $cart->setAttribute('total', $price * $quantity );
        }

        $cart_item = $this->cartService
            ->getCartItems(session('session_id'),'product')
            ->where('product_id', $cart->getAttribute('product_id'))->first();

        if($cart_item)
        {
            $this->cartService
                ->update($cart_item, $cart_item->quantity + 1,$cart->getAttribute('price'));
            return false;
        }

        return true;
    }

    public function deleting(cart $cart):bool
    {
        $cart_items = $this
            ->cartService
            ->getCartItems(session('session_id'))
            ->where('product_id', $cart->getAttribute('product_id'))
            ->first();

        if($cart_items && $cart_items->quantity > 1) {
            $this->cartService->update($cart_items, $cart->getAttribute('quantity') - 1 , $cart->getAttribute('price'));
            return false;
        }

        if($cart_items->quantity <= 1)
        {
            return true;
        }
    }
}
