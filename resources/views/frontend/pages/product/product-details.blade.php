
@extends('frontend.layout.template')

@section('title')
   <title>Ecommerce | {{ $productDetails->slug }}</title>
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
                        <h2>product</h2>
                    </div>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="theme-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('homepage') }}">Home</a></li>
                            <li class="breadcrumb-item" aria-current="page"><a href="{{ route('allProduct') }}">Product</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $productDetails->slug }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb End -->


    <!-- section start -->
    <section>
        <div class="collection-wrapper">
            <div class="container">
                <div class="row">

                    <!-- image slider start -->
                    <div class="col-lg-6">
                        <div class="product-slick">
                            @foreach ( $thumbNails = App\Models\ProductImage::where('product_id', $productDetails->id)->get() as $thumbNail )
                                <div>
                                    <img src="{{ asset('uploads/products/' . $thumbNail->name ) }}" alt="" class="img-fluid blur-up lazyload image_zoom_cls-0" style="width: 100%; height: 100%; object-fit: contain;">
                                </div>
                            @endforeach
                        </div>

                        <div class="row">
                            <div class="col-12 p-0">
                                <div class="slider-nav">
                                @foreach ( $thumbNails = App\Models\ProductImage::where('product_id', $productDetails->id)->get() as $thumbNail )
                                    <div>
                                        <img src="{{ asset('uploads/products/' . $thumbNail->name ) }}" alt="" class="img-fluid blur-up lazyload" style="width: 100%; hight: 100%">
                                    </div>
                                @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- image slider end -->


                    <!-- product details start -->
                    <div class="col-lg-6 rtl-text">
                        <div class="product-right">
                            <div class="product-count">
                                <ul>
                                    <li>
                                        <img src="{{ asset('frontend/assets/images/fire.gif') }}" class="img-fluid" alt="image">
                                        <span class="p-counter">37</span>
                                        <span class="lang">orders in last 24 hours</span>
                                    </li>
                                    <li>
                                        <img src="{{ asset('frontend/assets/images/person.gif') }}" class="img-fluid user_img" alt="image">
                                        <span class="p-counter">44</span>
                                        <span class="lang">active view this</span>
                                    </li>
                                </ul>
                            </div>
                            <h2>{{ $productDetails->title }}</h2>
                            <p>{{ $productDetails->short_details }}</p>

                            <div class="rating-section">
                                <div class="rating">
                                    <i class="fa fa-star"></i> 
                                    <i class="fa fa-star"></i> 
                                    <i class="fa fa-star"></i> 
                                    <i class="fa fa-star"></i> 
                                    <i class="fa fa-star"></i>
                                </div>
                                <h6>120 ratings</h6>
                            </div>

                            <div class="label-section">
                                <span class="badge badge-grey-color">#1 Best seller</span>
                                <span class="label-text badge bg-primary">
                                    {{ $productDetails->category->name }} category
                                </span>
                            </div>

                            @if ( !is_null( $productDetails->offer_price ) )
                              <h3 class="price-detail">৳{{ $productDetails->offer_price }} BDT <del>৳{{ $productDetails->regular_price }} BDT</del><span>55% off</span></h3> 
                            @else
                                <h3 class="price-detail">৳{{ $productDetails->regular_price }} BDT</h3>
                            @endif

                            <h6 class="product-title sixe-text">Available stock quantity</h6>
                            @if ( $productDetails->quantity == 0 )
                                <span class="bg-light1">Out of stock</span>
                            @else
                                <span class="bg-light0">{{ $productDetails->quantity }} Pcs</span>
                            @endif

                            <div id="selectSize" class="addeffect-section product-description border-product">
                                <h6 class="product-title size-text">select size <span>
                                    <a href="" data-bs-toggle="modal" data-bs-target="#sizemodal">size chart</a></span>
                                </h6>
                                <div class="modal fade" id="sizemodal" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Sheer  Straight Kurta</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="{{ asset('frontend/assets/images/size-chart.jpg') }}" alt="" class="img-fluid blur-up lazyload">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="error-message">please select size</h6>
                                <div class="size-box">
                                    <ul>
                                        <li><a href="javascript:void(0)">s</a></li>
                                        <li><a href="javascript:void(0)">m</a></li>
                                        <li><a href="javascript:void(0)">l</a></li>
                                        <li><a href="javascript:void(0)">xl</a></li>
                                    </ul>
                                </div>


                                <h6 class="product-title">quantity</h6>
                            </div>

                            
                            <!-- add to cart and wishlist start -->
                            <div class="product-buttons">
                               <div class="d-flex align-items-end gap-3">
                                    @include('frontend.pages.product.carts_button.cart_button')

                                    <form action="" method="post" class="mt-3">
                                        <button type="submit" class="btn btn-solid"
                                            @if ( $productDetails->quantity == 0 )
                                                disabled
                                            @endif
                                        >
                                            <i class="fa fa-bookmark fz-16 me-2" aria-hidden="true"></i>wishlist
                                        </button>
                                    </form>
                               </div>
                            </div>
                            <!-- add to cart and wishlist end -->

                            <div class="product-count">
                                <ul>
                                    <li>
                                        <img src="{{ asset('frontend/assets/images/icon/truck.png') }}" class="img-fluid" alt="image">
                                        <span class="lang">Free shipping for orders above $500 USD</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="border-product">
                                <h6 class="product-title">Sales Ends In</h6>
                                <div class="timer">
                                    <p id="demo"></p>
                                </div>
                            </div>

                            <div class="border-product">
                                <h6 class="product-title">shipping info</h6>
                                <ul class="shipping-info">
                                    <li>100% Original Products</li>
                                    <li>Free Delivery on order above Rs. 799</li>
                                    <li>Pay on delivery is available</li>
                                    <li>Easy 30 days returns and exchanges</li>
                                </ul>
                            </div>

                            <div class="border-product">
                                <h6 class="product-title">share it</h6>
                                <div class="product-icon">
                                    <ul class="product-social">
                                        <li><a href="#"><i class="fa fa-facebook-f"></i></a></li>
                                        <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                                        <li><a href="#"><i class="fa fa-rss"></i></a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="border-product">
                                <h6 class="product-title">100% secure payment</h6>
                                <img src="{{ asset('frontend/assets/images/payment.png') }}" class="img-fluid mt-1" alt="">
                            </div>
                        </div>
                    </div>
                    <!-- product details end -->
                </div>
            </div>
        </div>
    </section>
    <!-- Section ends -->


    <!-- product-tab starts -->
    <section class="tab-product m-0">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                        <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-bs-toggle="tab"
                                href="#top-home" role="tab" aria-selected="true"><i
                                    class="icofont icofont-ui-home"></i>Details</a>
                            <div class="material-border"></div>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="profile-top-tab" data-bs-toggle="tab"
                                href="#top-profile" role="tab" aria-selected="false"><i
                                    class="icofont icofont-man-in-glasses"></i>Specification</a>
                            <div class="material-border"></div>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="contact-top-tab" data-bs-toggle="tab"
                                href="#top-contact" role="tab" aria-selected="false"><i
                                    class="icofont icofont-contacts"></i>Video</a>
                            <div class="material-border"></div>
                        </li>
                        <li class="nav-item"><a class="nav-link" id="review-top-tab" data-bs-toggle="tab"
                                href="#top-review" role="tab" aria-selected="false"><i
                                    class="icofont icofont-contacts"></i>Write Review</a>
                            <div class="material-border"></div>
                        </li>
                    </ul>
                    <div class="tab-content nav-material" id="top-tabContent">
                        <div class="tab-pane fade show active" id="top-home" role="tabpanel"
                            aria-labelledby="top-home-tab">
                            <div class="product-tab-discription">
                                <div class="part">
                                    <p>The Model is wearing a white blouse from our stylist's collection, see the image
                                        for a mock-up of what the actual blouse would look like.it has text written on
                                        it in a black cursive language which looks great on a white color.</p>
                                </div>
                                <div class="part">
                                    <h5 class="inner-title">fabric:</h5>
                                    <p>Art silk is manufactured by synthetic fibres like rayon. It's light in weight and
                                        is soft on the skin for comfort in summers.Art silk is manufactured by synthetic
                                        fibres like rayon. It's light in weight and is soft on the skin for comfort in
                                        summers.</p>
                                </div>
                                <div class="part">
                                    <h5 class="inner-title">size & fit:</h5>
                                    <p>The model (height 5'8") is wearing a size S</p>
                                </div>
                                <div class="part">
                                    <h5 class="inner-title">Material & Care:</h5>
                                    <p>Top fabric: pure cotton</p>
                                    <p>Bottom fabric: pure cotton</p>
                                    <p>Hand-wash</p>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="top-profile" role="tabpanel" aria-labelledby="profile-top-tab">
                            <p>The Model is wearing a white blouse from our stylist's collection, see the image for a
                                mock-up of what the actual blouse would look like.it has text written on it in a black
                                cursive language which looks great on a white color.</p>
                            <div class="single-product-tables">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Sleeve Length</td>
                                            <td>Sleevless</td>
                                        </tr>
                                        <tr>
                                            <td>Neck</td>
                                            <td>Round Neck</td>
                                        </tr>
                                        <tr>
                                            <td>Occasion</td>
                                            <td>Sports</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Fabric</td>
                                            <td>Polyester</td>
                                        </tr>
                                        <tr>
                                            <td>Fit</td>
                                            <td>Regular Fit</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="top-contact" role="tabpanel" aria-labelledby="contact-top-tab">
                            <div class="">
                                <iframe width="560" height="315" src="https://www.youtube.com/embed/BUWzX78Ye_8"
                                    allow="autoplay; encrypted-media" allowfullscreen></iframe>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="top-review" role="tabpanel" aria-labelledby="review-top-tab">
                            <form class="theme-form">
                                <div class="form-row row">
                                    <div class="col-md-12">
                                        <div class="media">
                                            <label>Rating</label>
                                            <div class="media-body ms-3">
                                                <div class="rating three-star"><i class="fa fa-star"></i> <i
                                                        class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                                        class="fa fa-star"></i> <i class="fa fa-star"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" placeholder="Enter Your name"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" id="email" placeholder="Email" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="review">Review Title</label>
                                        <input type="text" class="form-control" id="review"
                                            placeholder="Enter your Review Subjects" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="review">Review Title</label>
                                        <textarea class="form-control" placeholder="Wrire Your Testimonial Here"
                                            id="exampleFormControlTextarea1" rows="6"></textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <button class="btn btn-solid" type="submit">Submit YOur
                                            Review</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- product-tab ends -->


    <!-- product section start -->
    <section class="section-b-space ratio_asos">
        <div class="container">
            <div class="row">
                <div class="col-12 product-related">
                    <h2>related products</h2>
                </div>
            </div>
            <div class="row search-product">
                <div class="col-xl-2 col-md-4 col-6">
                    <div class="product-box">
                        <div class="img-wrapper">
                            <div class="front">
                                <a href="#"><img src="{{ asset('frontend/assets/images/pro3/33.jpg') }}" class="img-fluid blur-up lazyload bg-img" alt=""></a>
                            </div>
                            <div class="back">
                                <a href="#"><img src="{{ asset('frontend/assets/images/pro3/34.jpg') }}" class="img-fluid blur-up lazyload bg-img" alt=""></a>
                            </div>
                            <div class="cart-info cart-wrap">
                                <button data-bs-toggle="modal" data-bs-target="#addtocart" title="Add to cart"><i
                                        class="ti-shopping-cart"></i></button> <a href="javascript:void(0)"
                                    title="Add to Wishlist"><i class="ti-heart" aria-hidden="true"></i></a> <a href="#"
                                    data-bs-toggle="modal" data-bs-target="#quick-view" title="Quick View"><i
                                        class="ti-search" aria-hidden="true"></i></a> <a href="compare.html"
                                    title="Compare"><i class="ti-reload" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <div class="product-detail">
                            <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                    class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                            </div>
                            <a href="product-page(no-sidebar).html">
                                <h6>Slim Fit Cotton Shirt</h6>
                            </a>
                            <h4>$500.00</h4>
                            <ul class="color-variant">
                                <li class="bg-light0"></li>
                                <li class="bg-light1"></li>
                                <li class="bg-light2"></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xl-2 col-md-4 col-6">
                    <div class="product-box">
                        <div class="img-wrapper">
                            <div class="front">
                                <a href="#"><img src="{{ asset('frontend/assets/images/pro3/1.jpg')}}" class="img-fluid blur-up lazyload bg-img" alt=""></a>
                            </div>
                            <div class="back">
                                <a href="#"><img src="{{ asset('frontend/assets/images/pro3/2.jpg') }}"class="img-fluid blur-up lazyload bg-img" alt=""></a>
                            </div>
                            <div class="cart-info cart-wrap">
                                <button data-bs-toggle="modal" data-bs-target="#addtocart" title="Add to cart"><i
                                        class="ti-shopping-cart"></i></button> <a href="javascript:void(0)"
                                    title="Add to Wishlist"><i class="ti-heart" aria-hidden="true"></i></a> <a href="#"
                                    data-bs-toggle="modal" data-bs-target="#quick-view" title="Quick View"><i
                                        class="ti-search" aria-hidden="true"></i></a> <a href="compare.html"
                                    title="Compare"><i class="ti-reload" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <div class="product-detail">
                            <div class="rating"><i class="fa fa-star"></i> <i class="fa fa-star"></i> <i
                                    class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i>
                            </div>
                            <a href="product-page(no-sidebar).html">
                                <h6>Slim Fit Cotton Shirt</h6>
                            </a>
                            <h4>$500.00</h4>
                            <ul class="color-variant">
                                <li class="bg-light0"></li>
                                <li class="bg-light1"></li>
                                <li class="bg-light2"></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- product section end -->

 @endsection



 @section('footer-content')

    <!-- recently purchase product -->
    <div class="media recently-purchase">
        <img src="{{ asset('frontend/') }}assets/images/pro3/sm.jpg" alt="Floral Dress ">
        <div class="media-body">
            <div>
                <div class="title">
                    Some recently purchase this item
                </div>
                <a href="#">
                    <span class="product-name">
                        Floral Dress
                    </span>
                </a>
                <small class="timeAgo">50 minutes ago</small>
            </div>
        </div>
        <a href="javascript:void(0)" class="close-popup fa fa-times"></a>
    </div>
    <!-- recently purchase product -->


    <!-- sticky cart bottom start -->
    <div class="sticky-bottom-cart d-sm-block d-none">
        <div class="container">
            <div class="cart-content">
                <div class="product-image">
                    <img src="{{ asset('frontend/') }}assets/images/pro3/1.jpg" class="img-fluid" alt="">
                    <div class="content d-lg-block d-none">
                        <h5>WOMEN PINK SHIRT</h5>
                        <h6>$32.96<del>$459.00</del><span>55% off</span></h6>
                    </div>
                </div>
                <div class="selection-section">
                    <div class="form-group mb-0">
                        <select id="inputState" class="form-control">
                            <option selected>Choose color...</option>
                            <option>pink</option>
                            <option>blue</option>
                            <option>grey</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <select id="inputState" class="form-control">
                            <option selected>Choose size...</option>
                            <option>small</option>
                            <option>medium</option>
                            <option>large</option>
                            <option>extra large</option>
                        </select>
                    </div>
                </div>
                <div class="add-btn">
                    <a data-bs-toggle="modal" data-bs-target="#addtocart" href="" class="btn btn-solid btn-sm">add to
                        cart</a>
                </div>
            </div>
        </div>
    </div>
    <!-- sticky cart bottom end -->


    <!-- added to cart notification -->
    <div class="added-notification">
        <img src="{{ asset('frontend/') }}assets/images/fashion/pro/1.jpg" class="img-fluid" alt="">
        <h3>added to cart</h3>
    </div>
    <!-- added to cart notification -->

 @endsection









