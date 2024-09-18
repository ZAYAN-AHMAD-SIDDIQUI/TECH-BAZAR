@extends('front.layout.app')
@section('content')
    <style>
        .cat-card {
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .cat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .cat-data h2 {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        @media (max-width: 576px) {
            .row {
                display: flex;
                overflow-x: auto;
                padding-bottom: 10px;
            }

            .col-12 {
                flex: 0 0 auto;
                width: 80%;
                margin-right: 15px;
            }

            .cat-card {
                width: 100%;
            }
        }

        @media (min-width: 768px) and (max-width: 992px) {
            .col-md-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (min-width: 576px) and (max-width: 767px) {
            .row {
                justify-content: space-between;
            }

            .col-sm-6 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            .cat-data {
                margin-left: -70px
            }
        }

        .cat-card {
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            width: 100%;
            /* Ensures the card takes the full width */
        }

        .cat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .cat-data h2 {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            text-align: left;
            /* Ensures text alignment is left */
        }

        @media (max-width: 576px) {
            .col-12 {
                flex: 0 0 100%;
                /* Ensures the column takes full width on mobile */
                max-width: 100%;
                margin-bottom: 15px;
            }

            .cat-card {
                width: 100%;
                /* Card takes full width of its container */
            }

            .cat-data {
                margin-left: -70px
            }
        }

        .box {
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            width: 100%;
            /* Ensures the box takes the full width */
        }

        .box:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .text h2 {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            text-align: left;
            /* Ensures text alignment is left */
        }

        @media (max-width: 576px) {
            .col-12 {
                flex: 0 0 100%;
                /* Ensures the column takes full width on mobile */
                max-width: 100%;
                margin-bottom: 15px;
            }

            .box {
                width: 100%;
                /* Box takes full width of its container */
            }
        }



        .banner-container {
            position: relative;
            display: inline-block;
            width: 100%;
            max-width: 100%;
            /* Ensure the image is fully responsive */
        }

        .banner-image {
            width: 100%;
            height: auto;
            display: block;
        }

        .button {
            position: absolute;
            top: 75%;
            /* Adjust this value to position the button vertically */
            left: 20%;
            /* Adjust this value to position the button horizontally */
            transform: translate(-50%, -50%);
            width: 140px;
            height: 40px;
            /* background-image: linear-gradient(rgb(214, 202, 254), rgb(158, 129, 254)); */
            background-color: #000000;
            border: 2px solid #fff;
            border-radius: 50px;
            color: rgb(255, 255, 255);
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            cursor: pointer;
            transition-duration: .3s;
        }

        .cartIcon {
            width: 14px;
            height: fit-content;
        }

        .cartIcon path {
            fill: white;
        }

        .button:hover {
            background-color: #f7ca0d;
            transition: 3sec ease all
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .button {
                display: none;

            }
        }

        @media (max-width: 576px) {
            .button {
                display: none;
            }
        }
    </style>
    <section class="">
        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="{{ asset('front-assets/images/banners/5.png') }}" class="d-block w-100" alt="...">
              </div>
              <div class="carousel-item">
                <img src="{{ asset('front-assets/images/banners/4.png') }}" class="d-block w-100" alt="...">
              </div>
              <div class="carousel-item">
                <img src="{{ asset('front-assets/images/banners/6.png') }}" class="d-block w-100" alt="...">
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
    </section>



    <section class="section-2 py-5">
        <div class="container">
            <div class="row g-3 text-center">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="box shadow-lg d-flex align-items-center p-3"
                        style="background-color: #f8f9fa; border-radius: 10px;">
                        <div class="icon me-3">
                            <i class="fa fa-check text-primary" style="font-size: 30px;"></i>
                        </div>
                        <div class="text">
                            <h2 class="h5 font-weight-semi-bold m-0">Quality Product</h2>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="box shadow-lg d-flex align-items-center p-3"
                        style="background-color: #f8f9fa; border-radius: 10px;">
                        <div class="icon me-3">
                            <i class="fa fa-shipping-fast text-primary" style="font-size: 30px;"></i>
                        </div>
                        <div class="text">
                            <h2 class="h5 font-weight-semi-bold m-0">Free Shipping</h2>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="box shadow-lg d-flex align-items-center p-3"
                        style="background-color: #f8f9fa; border-radius: 10px;">
                        <div class="icon me-3">
                            <i class="fa fa-exchange-alt text-primary" style="font-size: 30px;"></i>
                        </div>
                        <div class="text">
                            <h2 class="h5 font-weight-semi-bold m-0">14-Day Return</h2>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="box shadow-lg d-flex align-items-center p-3"
                        style="background-color: #f8f9fa; border-radius: 10px;">
                        <div class="icon me-3">
                            <i class="fa fa-phone-volume text-primary" style="font-size: 30px;"></i>
                        </div>
                        <div class="text">
                            <h2 class="h5 font-weight-semi-bold m-0">24/7 Support</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-3 py-5">
        <div class="container">
            <div class="section-title mb-4">
                <h2>Categories</h2>
            </div>
            <div class="row g-3">
                @if (getCategories()->isNotEmpty())
                    @foreach (getCategories() as $category)
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                            <div class="cat-card d-flex align-items-center p-3"
                                style="background-color: #f8f9fa; border-radius: 10px;">
                                <div class="left">
                                    @if ($category->image != '')
                                        <img src="{{ asset('uploads/category/thumb/' . $category->image) }}"
                                            alt="{{ $category->name }}" class="img-fluid"
                                            style="width: 70px; height: 70px; object-fit: cover; border-radius: 50%;">
                                    @else
                                        <img src="{{ asset('default-category-image.png') }}" alt="{{ $category->name }}"
                                            class="img-fluid"
                                            style="width: 70px; height: 70px; object-fit: cover; border-radius: 50%;">
                                    @endif
                                </div>
                                <div class="right flex-grow-1">
                                    <div class="cat-data" style="">
                                        <h2 class="h5 m-0">{{ $category->name }}</h2>
                                        {{-- <p>100 Products</p> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>




    <section class="section-4 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>Featured Products</h2>
            </div>
            <div class="row pb-3">
                @if ($featuredProducts->isNotEmpty())
                    @foreach ($featuredProducts as $product)
                        @php
                            $productImage = $product->product_images->first();
                        @endphp
                        <div class="col-6 col-sm-4 col-md-2 col-lg-2 col-xl-2 mb-4">
                            <div class="card product-card" style="height: 320px;">
                                <div class="product-image position-relative">
                                    <a href="{{ route('front.product', $product->slug) }}" class="product-img">
                                        @if (!empty($productImage->image))
                                            <div class="d-flex justify-content-center align-items-center"
                                                style="height: 200px;">
                                                <img class="card-img-top img-fluid pt-4"
                                                    style="max-height: 100%; max-width: 100%; object-fit: contain;"
                                                    src="{{ asset('uploads/product/small/' . $productImage->image) }}">
                                            </div>
                                        @else
                                            <div class="d-flex justify-content-center align-items-center"
                                                style="height: 200px;">
                                                <img class="card-img-top img-fluid"
                                                    style="max-height: 100%; max-width: 100%; object-fit: contain;"
                                                    src="{{ asset('admin-assets/img/default-150x150.png') }}">
                                            </div>
                                        @endif
                                    </a>
                                    <a onclick="addToWishlist({{ $product->id }})"
                                        class="whishlist position-absolute top-0 right-0 p-2" href="javascript:void(0);">
                                        <i class="far fa-heart"></i>
                                    </a>
                                    <div class="product-action mt-2 text-center">
                                        @if ($product->track_qty == 'Yes')
                                            @if ($product->qty > 0)
                                                <a class="btn btn-sm btn-dark" href="javascript:void(0);"
                                                    onclick="addToCart({{ $product->id }})">
                                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                                </a>
                                            @else
                                                <a class="btn btn-sm btn-dark" href="javascript:void(0);">Out Of Stock</a>
                                            @endif
                                        @else
                                            <a class="btn btn-sm btn-dark" href="javascript:void(0);"
                                                onclick="addToCart({{ $product->id }})">
                                                <i class="fa fa-shopping-cart"></i> Add To Cart
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body text-center mt-3" style="padding: 10px;">
                                    <a class="t-1 link d-block mb-1 " href="{{ route('front.product', $product->slug) }}"
                                        style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size:12.9px">
                                        {{ $product->short_title }}
                                    </a>
                                    <div class="price mt-2">
                                        <span class="h5"><strong>${{ $product->price }}</strong></span>
                                        @if ($product->compare_price > 0)
                                            <span class="h6 text-muted"><del>${{ $product->compare_price }}</del></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>
    </section>
    <section class="section-4 pt-5" >
        <div class="container">
            <div class="banner-container">
                <img src="{{ asset('front-assets/images/banners/1.png') }}" alt="Gaming Laptop Banner"
                    class="banner-image">
                <a href="/product/hp-laptop-eq2180au-156-inches-amd-ryzen-5-8gb-ram-512gbssd"> <button class="button shop-now-btn">
                        Shop now
                        <svg class="cartIcon" viewBox="0 0 576 512">
                            <path
                                d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z">
                            </path>
                        </svg>
                    </button></a>
            </div>
        </div>
    </section>

    <section class="section-4 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>Latest Products</h2>
            </div>
            <div class="row pb-3">
                @if ($latestProducts->isNotEmpty())
                    @foreach ($latestProducts as $product)
                        @php
                            $productImage = $product->product_images->first();
                        @endphp
                        <div class="col-6 col-sm-4 col-md-2 col-lg-2 col-xl-2 mb-4">
                            <div class="card product-card" style="height: 320px;">
                                <div class="product-image position-relative">
                                    <a href="{{ route('front.product', $product->slug) }}" class="product-img">
                                        @if (!empty($productImage->image))
                                            <div class="d-flex justify-content-center align-items-center"
                                                style="height: 200px;">
                                                <img class="card-img-top img-fluid pt-4"
                                                    style="max-height: 100%; max-width: 100%; object-fit: contain;"
                                                    src="{{ asset('uploads/product/small/' . $productImage->image) }}">
                                            </div>
                                        @else
                                            <div class="d-flex justify-content-center align-items-center"
                                                style="height: 200px;">
                                                <img class="card-img-top img-fluid"
                                                    style="max-height: 100%; max-width: 100%; object-fit: contain;"
                                                    src="{{ asset('admin-assets/img/default-150x150.png') }}">
                                            </div>
                                        @endif
                                    </a>
                                    <a onclick="addToWishlist({{ $product->id }})"
                                        class="whishlist position-absolute top-0 right-0 p-2" href="javascript:void(0);">
                                        <i class="far fa-heart"></i>
                                    </a>
                                    <div class="product-action mt-2 text-center">
                                        @if ($product->track_qty == 'Yes')
                                            @if ($product->qty > 0)
                                                <a class="btn btn-sm btn-dark" href="javascript:void(0);"
                                                    onclick="addToCart({{ $product->id }})">
                                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                                </a>
                                            @else
                                                <a class="btn btn-sm btn-dark" href="javascript:void(0);">Out Of Stock</a>
                                            @endif
                                        @else
                                            <a class="btn btn-sm btn-dark" href="javascript:void(0);"
                                                onclick="addToCart({{ $product->id }})">
                                                <i class="fa fa-shopping-cart"></i> Add To Cart
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body text-center mt-3" style="padding: 10px;">
                                    <a class="t-1 link d-block mb-1" href="{{ route('front.product', $product->slug) }}"
                                        style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size:12.9px">
                                        {{ $product->short_title }}
                                    </a>
                                    <div class="price mt-2">
                                        <span class="h5"><strong>${{ $product->price }}</strong></span>
                                        @if ($product->compare_price > 0)
                                            <span class="h6 text-muted"><del>${{ $product->compare_price }}</del></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>
    </section>

    <section class="section-4 pt-5" >
        <div class="container">
            <div class="banner-container">
                <img src="{{ asset('front-assets/images/banners/2.png') }}" alt="Gaming Laptop Banner"
                    class="banner-image">
            </div>
        </div>
    </section>

    <section class="section-4 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>Trending Products</h2>
            </div>
            <div class="row pb-3">
                @if ($trendProducts->isNotEmpty())
                    @foreach ($trendProducts as $product)
                        @php
                            $productImage = $product->product_images->first();
                        @endphp
                        <div class="col-6 col-sm-4 col-md-2 col-lg-2 col-xl-2 mb-4">
                            <div class="card product-card" style="height: 320px;">
                                <div class="product-image position-relative">
                                    <a href="{{ route('front.product', $product->slug) }}" class="product-img">
                                        @if (!empty($productImage->image))
                                            <div class="d-flex justify-content-center align-items-center"
                                                style="height: 200px;">
                                                <img class="card-img-top img-fluid pt-4"
                                                    style="max-height: 100%; max-width: 100%; object-fit: contain;"
                                                    src="{{ asset('uploads/product/small/' . $productImage->image) }}">
                                            </div>
                                        @else
                                            <div class="d-flex justify-content-center align-items-center"
                                                style="height: 200px;">
                                                <img class="card-img-top img-fluid"
                                                    style="max-height: 100%; max-width: 100%; object-fit: contain;"
                                                    src="{{ asset('admin-assets/img/default-150x150.png') }}">
                                            </div>
                                        @endif
                                    </a>
                                    <a onclick="addToWishlist({{ $product->id }})"
                                        class="whishlist position-absolute top-0 right-0 p-2" href="javascript:void(0);">
                                        <i class="far fa-heart"></i>
                                    </a>
                                    <div class="product-action mt-2 text-center">
                                        @if ($product->track_qty == 'Yes')
                                            @if ($product->qty > 0)
                                                <a class="btn btn-sm btn-dark" href="javascript:void(0);"
                                                    onclick="addToCart({{ $product->id }})">
                                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                                </a>
                                            @else
                                                <a class="btn btn-sm btn-dark" href="javascript:void(0);">Out Of Stock</a>
                                            @endif
                                        @else
                                            <a class="btn btn-sm btn-dark" href="javascript:void(0);"
                                                onclick="addToCart({{ $product->id }})">
                                                <i class="fa fa-shopping-cart"></i> Add To Cart
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body text-center mt-3" style="padding: 10px;">
                                    <a class="t-1 link d-block mb-1" href="{{ route('front.product', $product->slug) }}"
                                        style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size:12.9px">
                                        {{ $product->short_title }}
                                    </a>
                                    <div class="price mt-2">
                                        <span class="h5"><strong>${{ $product->price }}</strong></span>
                                        @if ($product->compare_price > 0)
                                            <span class="h6 text-muted"><del>${{ $product->compare_price }}</del></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>
    </section>
@endsection
