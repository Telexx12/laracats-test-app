<?php

namespace App\Livewire\Pages\Cart;

use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Component;
use Livewire\WithPagination;
use Psy\VersionUpdater\Downloader\Factory;

class Index extends Component
{
    use WithPagination;

    protected CartService $cartService;

    #[NoReturn]
    public function boot(CartService $cartService): void
    {
        $this->cartService = $cartService;
    }

    #[NoReturn]
    public function remove(Cart $cart): void
    {
        Log::info('Remove method triggered for cart item ID: ' . $cart->id);

        $this->cartService->delete($cart);

        $this->dispatch('$refresh')->self();

    }
    #[NoReturn]
    public function checkout(): void
    {
        $cart_items = $this->cartService->getCartItems(session('session_id'));

        session()->put('cart', $cart_items);

        event(new Login('web', Auth::user(),false));

        $this->redirectRoute('login');
    }



    public function render(): Application|Factory|\Illuminate\Contracts\View\View|View
    {
        $cart_items = $this
            ->cartService
            ->getCartItemsWithPaginator(100,session('session_id'));

        $total_price = $this->cartService->totalPrice(session('session_id'));

        return view('livewire.pages.cart.index',['cart_items'=>$cart_items,'total_price'=>$total_price]);

    }
}
