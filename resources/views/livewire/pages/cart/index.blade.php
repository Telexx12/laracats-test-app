
<div>
    <div class="container mt-5">
        <div class="row d-flex justify-content-center">
            <div class="col-5">
                @if(session()->has('message'))
                    <div id="alert" class="alert alert-warning alert-dismissible fade show" role="alert">
                        <p class="text-center">{{  session('message')  }}</p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="container">
        <h1>Shopping Cart</h1>
        <div class="row d-flex justify-content-center">
            <div class="col-sm-12 col-md-7 col-lg-9">
                <div class="card mt-5">
                    <div class="card-body">
                        @if(count($cart_items) == 0)
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <img src="{{ asset('images/empty-cart.png') }}" width="200" alt="empty-cart" class="img-fluid">
                                <p>You cart is empty. Keep shopping to find a product </p>
                                <button wire:navigate href="{{ route('products.index') }}" class="btn btn-dark btn-lg">Continue Shopping</button>
                            </div>

                        @else
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <th>id</th>
                                    <th>Item Name</th>
                                    <th>quantity</th>
                                    <th>price</th>
                                    <th>action</th>
                                    </thead>
                                    <tbody>
                                    @if(!empty($cart_items))
                                        @foreach($cart_items as $cart_item)
                                            <tr wire:key="{{ $cart_item->id }}">
                                                <td>{{$cart_item->id}}</td>
                                                <td>{{$cart_item->product}}</td>
                                                <td>{{{ $cart_item->quantity }}}</td>
                                                <td>{{ $cart_item->price }}</td>
                                                <td>
                                                    <button class="btn btn-link" wire:confirm="Are You Sure You Want To Delete This Item" wire:click="remove({{ $cart_item }})">remove</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>

                                </table>
                            </div>
                        @endif
                    </div>
                    <div class="container">
                        {{ $cart_items->links() }}
                    </div>
                </div>
            </div>
            @if(count($cart_items) > 0)
                <div class="col-sm-12 col-md-5 col-lg-3 mt-sm-3 mt-md-0"  style="height: 300px">
                    <div class="card mt-5">
                        <div class="card-body">
                            <p class="fs-5 fw-bold">Total:</p>
                            <p class="fs-2 fw-bold m-0">E£{{ $total_price  }}</p>
                            <p class="m-0   ">E£400</p>
                            <p class="mb-3">Saved 20% </p>
                            <div class="d-grid gap-2">
                                @auth()
                                    <button wire:click="Pay" class="btn btn-lg btn-dark">Pay</button>
                                @endauth
                                @guest()
                                    <button wire:click="checkout" class="btn btn-lg btn-dark">Checkout</button>
                                @endguest

                            </div>
                            <hr class=""></hr>
                            <p class="fs-5 fw-bold">Promotion:</p>
                            <form>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Enter promo code">
                                    <button class="btn btn-primary">apply</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            @endif

        </div>
    </div>
</div>

{{--@script
   <script>
       let alert = document.getElementsByClassName('alert')
       if(alert != null)
       {
           setInterval(function (e){alert.item(0).re}, 3000);
       }

   </script>
@endscript--}}


