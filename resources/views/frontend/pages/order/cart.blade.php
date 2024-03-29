
@extends('frontend.layout.template')

@section('title')
   <title>Ecommerce | Manage cart page</title>
@endsection

@section('css')
    
@endsection



 @section('body-content')

    <!-- breadcrumb start -->
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="page-title">
                        <h2>cart</h2>
                    </div>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="theme-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('homepage') }}">Home</a></li>
                            <li class="breadcrumb-item active">cart</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->


    <!--section start-->
    <section class="cart-section section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="cart_counter">
                        <div class="countdownholder">
                            Your cart will be expired in<span id="timer"></span> minutes!
                        </div>
                        <a href="checkout.html" class="cart_checkout btn btn-solid btn-xs">check out</a>
                    </div>
                </div>
                
                <div class="col-sm-12 table-responsive-xs">
                    <table class="table cart-table">

                        <thead>
                            <tr class="table-head">
                                <th scope="col">image</th>
                                <th scope="col">product name</th>
                                <th scope="col">price</th>
                                <th scope="col">quantity</th>
                                <th scope="col">action</th>
                                <th scope="col">total</th>
                            </tr>
                        </thead>

                    @foreach ($carts as $cart)
                        <tbody>
                            <tr>
                                <td>
                                    <a href="#"><img src="{{ asset('frontend/assets/images/pro3/2.jpg') }}" alt=""></a>
                                </td>
                                <td>
                                    <a href="{{ route('productDetails', $cart->product->slug) }}">{{ $cart->product->title }}</a>
                                    <div class="mobile-cart-content row">
                                        <div class="col">
                                            <div class="qty-box">
                                                <div class="input-group">
                                                   <input type="text" name="quantity" class="form-control input-number" value="1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <h2 class="td-color">$63.00</h2>
                                        </div>
                                        <div class="col">
                                            <h2 class="td-color"><a href="#" class="icon"><i class="ti-close"></i></a>
                                            </h2>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <h2>
                                        @if( !is_null( $cart->product->offer_price ) )
                                        ৳ {{ $cart->product->offer_price }}
                                        @else
                                        ৳ {{ $cart->product->regular_price }}
                                        @endif
                                    </h2>
                                </td>
                                <td>
                                    <div class="qty-box">
                                        <div class="input-group">
                                            <input type="number" name="quantity" class="form-control input-number" value={{ $cart->product_quantity }}>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('cart.destroy', $cart->id) }}" class="icon">
                                        <i class="ti-close"></i>
                                    </a>
                                </td>
                                <td>
                                    <h2 class="td-color">
                                        @if( !is_null( $cart->product->offer_price ) )
                                        ৳ {{ $cart->product->offer_price * $cart->product_quantity }}
                                        @else
                                        ৳ {{ $cart->product->regular_price * $cart->product_quantity }}
                                        @endif
                                    </h2>
                                </td>
                            </tr>
                        </tbody>
                      @endforeach

                    </table>

                    <div class="table-responsive-md">
                        <table class="table cart-table ">
                            <tfoot>
                                <tr>
                                    <td>total price :</td>
                                    <td>
                                        <h2>৳ {{ App\Models\Cart::totalAmount() }}</h2>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row cart-buttons">
                <div class="col-6"><a href="{{ route('allProduct') }}" class="btn btn-solid">continue shopping</a></div>
                 <div class="col-6">
                    @if ( $carts->count() > 0 )
                      <a href="{{ route('checkout') }}" class="btn btn-solid">check out</a>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!--section end-->

@endsection




@section('script')

    <!-- timer js-->
    <script src="{{ asset('frontend/assets/js/timer1.js') }}"></script>

@endsection
