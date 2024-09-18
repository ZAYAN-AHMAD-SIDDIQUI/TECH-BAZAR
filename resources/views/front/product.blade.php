@extends('front.layout.app')
@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                    <li class="breadcrumb-item">{{ $product->title }}</li>
                </ol>
            </div>
        </div>
        <style>
            .product-image {
                object-fit: contain;
                width: 100%;
                height: auto;
                max-height: 400px;
                /* Set a max height for laptop/desktop view */
            }

            @media (max-width: 992px) {

                /* For tablet and smaller devices */
                .product-image {
                    max-height: 300px;
                    /* Slightly smaller for tablet view */
                }
            }

            @media (max-width: 576px) {

                /* For mobile devices */
                .product-image {
                    max-height: 200px;
                    /* Even smaller for mobile view */
                }
            }

            #related-products {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }

            #related-products .col-md-3 {
                display: flex;
                justify-content: center;
            }

            .product-card {
                width: 100%;
                max-width: 300px;
                /* Adjust the max-width as needed */
            }
        </style>
    </section>
    <section class="section-7 pt-3 mb-3">
        <div class="container">
            <div class="row">
                @include('front.account.parts.message')
                <div class="col-md-5">
                    <div id="product-carousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @if ($product->product_images)
                                @foreach ($product->product_images as $key => $productImages)
                                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                        <img class="img-fluid product-image"
                                            src="{{ asset('uploads/product/large/' . $productImages->image) }}"
                                            alt="Product Image">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <a class="carousel-control-prev" href="#product-carousel" data-bs-slide="prev">
                            <i class="fa fa-2x fa-angle-left text-dark"></i>
                        </a>
                        <a class="carousel-control-next" href="#product-carousel" data-bs-slide="next">
                            <i class="fa fa-2x fa-angle-right text-dark"></i>
                        </a>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="right">
                        <h1>{{ $product->title }}</h1>
                        <div class="d-flex mb-3">
                            {{-- <div class="text-primary mr-2">
                                <small class="fas fa-star"></small>
                                <small class="fas fa-star"></small>
                                <small class="fas fa-star"></small>
                                <small class="fas fa-star-half-alt"></small>
                                <small class="far fa-star"></small>
                            </div> --}}
                            <div class="star-rating product mt-2" title="">
                                <div class="back-stars">
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>

                                    <div class="front-stars" style="width: {{ $avgRatingPer }}%">
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                            <small class="pt-1 ps-2">({{ ($product->product_ratings_count > 1 ) ? 
                                            $product->product_ratings_count.'  Reviews'  : 
                                            $product->product_ratings_count.'  Review' }})</small>
                        </div>

                        @if ($product->compare_price > 0)
                            <h2 class="price text-secondary"><del>${{ $product->compare_price }}</del></h2>
                        @endif

                        <h2 class="price ">${{ $product->price }}</h2>
                        {!! $product->short_description !!}
                        <div class="product-action">
                            @if ($product->track_qty == 'Yes')
                                @if ($product->qty > 0)
                                    <a class="btn btn-dark" href="javascript:void(0);"
                                        onclick="addToCart({{ $product->id }})">
                                        <i class="fa fa-shopping-cart"></i> &nbsp; Add To Cart
                                    </a>
                                @else
                                    <a class="btn btn-dark" href="javascript:void(0);">Out Of Stock</a>
                                @endif
                            @else
                                <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }})">
                                    <i class="fa fa-shopping-cart"></i> &nbsp;Add To Cart
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-5">
                    <div>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                                    data-bs-target="#description" type="button" role="tab" aria-controls="description"
                                    aria-selected="true">Description</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping"
                                    type="button" role="tab" aria-controls="shipping" aria-selected="false">Shipping &
                                    Returns</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                                    type="button" role="tab" aria-controls="reviews"
                                    aria-selected="false">Reviews</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="description" role="tabpanel"
                                aria-labelledby="description-tab">
                                {!! $product->description !!}
                            </div>
                            <div class="tab-pane fade" id="shipping" role="tabpanel" aria-labelledby="shipping-tab">
                                {!! $product->shipping_returns !!}
                            </div>
                            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                <div class="col-md-12">
                                    <div class="row">

                                        <form action="" name="ProductRatingForm" id="ProductRatingForm"
                                            method="POST">

                                            <h3 class="h4 pb-3">Write a Review</h3>
                                            <div class="form-group col-md-6 mb-3">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" name="name" id="name"
                                                    placeholder="Name">
                                                <p></p>
                                            </div>
                                            <div class="form-group col-md-6 mb-3">
                                                <label for="email">Email</label>
                                                <input type="text" class="form-control" name="email" id="email"
                                                    placeholder="Email">
                                                <p></p>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="rating">Rating</label>
                                                <br>
                                                <div class="rating" style="width: 10rem">
                                                    <input id="rating-5" type="radio" name="rating"
                                                        value="5" /><label for="rating-5"><i
                                                            class="fas fa-3x fa-star"></i></label>
                                                    <input id="rating-4" type="radio" name="rating"
                                                        value="4" /><label for="rating-4"><i
                                                            class="fas fa-3x fa-star"></i></label>
                                                    <input id="rating-3" type="radio" name="rating"
                                                        value="3" /><label for="rating-3"><i
                                                            class="fas fa-3x fa-star"></i></label>
                                                    <input id="rating-2" type="radio" name="rating"
                                                        value="2" /><label for="rating-2"><i
                                                            class="fas fa-3x fa-star"></i></label>
                                                    <input id="rating-1" type="radio" name="rating"
                                                        value="1" /><label for="rating-1"><i
                                                            class="fas fa-3x fa-star"></i></label>
                                                </div>
                                                <p class="product-rating-error text-danger" style="font-size:14px;"></p>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="">How was your overall experience?</label>
                                                <textarea name="comment" id="comment" class="form-control" cols="30" rows="10"
                                                    placeholder="How was your overall experience?"></textarea>
                                                <p></p>
                                            </div>
                                            <div>
                                                <button class="btn btn-dark">Submit</button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                                <div class="col-md-12 mt-5">
                                    <div class="overall-rating mb-3">
                                        <div class="d-flex">
                                            <h1 class="h3 pe-3">{{ $avgRating }}</h1>
                                            <div class="star-rating mt-2 pt-1" title="">
                                                <div class="back-stars">
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>

                                                    <div class="front-stars" style="width: {{ $avgRatingPer }}%">
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pt-1 ps-2">({{ ($product->product_ratings_count > 1 ) ? 
                                            $product->product_ratings_count.'  Reviews'  : 
                                            $product->product_ratings_count.'  Review' }})</div>
                                        </div>

                                    </div>

                                    @if ($product->product_ratings->isNotEmpty())
                                        @foreach ($product->product_ratings as $rating)
                                            @php
                                                $ratingPer = ($rating->rating * 100) / 5;
                                            @endphp
                                            <div class="rating-group mb-4">
                                                <span> <strong> {{ $rating->username }}</strong></span>
                                                <div class="star-rating mt-2" title="">
                                                    <div class="back-stars">
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>
                                                        <i class="fa fa-star" aria-hidden="true"></i>

                                                        <div class="front-stars" style="width: {{ $ratingPer }}%">
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                            <i class="fa fa-star" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="my-3">
                                                    <p>{{ $rating->comment }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif



                                </div>
                            </div>
                            <!-- Reviews content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>


    @if (!empty($relatedProducts))
        <section class="pt-5 section-8">
            <div class="container">
                <div class="section-title">
                    <h2>Related Products</h2>
                </div>
                <div id="related-products" class="row d-flex justify-content-center">

                    @foreach ($relatedProducts as $relProduct)
                        <div class="col-6 col-sm-4 col-md-2 col-lg-2 col-xl-2 mb-4">
                            @php
                                $productImage = $relProduct->product_images->first();
                            @endphp
                            <a href="{{ route('front.product', $relProduct->slug) }}">
                                <div class="card product-card" style="height: 320px;">
                                    <div class="product-image position-relative">
                                        <a href="{{ route('front.product', $relProduct->slug) }}" class="product-img">
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
                                        <a class="whishlist position-absolute top-0 right-0 p-2"
                                            href="javascript:void(0);">
                                            <i class="far fa-heart"></i>
                                        </a>
                                        <div class="product-action mt-2 text-center">
                                            @if ($relProduct->track_qty == 'Yes')
                                                @if ($relProduct->qty > 0)
                                                    <a class="btn btn-sm btn-dark" href="javascript:void(0);"
                                                        onclick="addToCart({{ $relProduct->id }})">
                                                        <i class="fa fa-shopping-cart"></i> Add To Cart
                                                    </a>
                                                @else
                                                    <a class="btn btn-sm btn-dark" href="javascript:void(0);">Out Of
                                                        Stock</a>
                                                @endif
                                            @else
                                                <a class="btn btn-sm btn-dark" href="javascript:void(0);"
                                                    onclick="addToCart({{ $relProduct->id }})">
                                                    <i class="fa fa-shopping-cart"></i> Add To Cart
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-body text-center mt-3" style="padding: 10px;">
                                        <a class="t-1 link d-block mb-1"
                                            href="{{ route('front.product', $relProduct->slug) }}"
                                            style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $relProduct->title }}
                                        </a>
                                        <div class="price mt-2">
                                            <span class="h5"><strong>${{ $relProduct->price }}</strong></span>
                                            @if ($relProduct->compare_price > 0)
                                                <span
                                                    class="h6 text-muted"><del>${{ $relProduct->compare_price }}</del></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach

                </div>
            </div>
        </section>
    @endif

@endsection
@section('customjs')
    <script type="text/javascript">
        $("#ProductRatingForm").submit(function(event) {
            event.preventDefault();

            $.ajax({
                url: "{{ route('front.saveRating', $product->id) }}",
                type: "POST",
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    var errors = response.errors;

                    if (response.status == false) {
                        if (errors.name) {
                            $("#name").addClass('is-invalid').siblings('p')
                                .addClass('invalid-feedback').html(errors.name);
                        } else {
                            $("#name").removeClass('is-invalid').siblings('p')
                                .removeClass('invalid-feedback').html('');
                        }

                        if (errors.email) {
                            $("#email").addClass('is-invalid').siblings('p')
                                .addClass('invalid-feedback').html(errors.email);
                        } else {
                            $("#email").removeClass('is-invalid').siblings('p')
                                .removeClass('invalid-feedback').html('');
                        }

                        if (errors.comment) {
                            $("#comment").addClass('is-invalid').siblings('p')
                                .addClass('invalid-feedback').html(errors.comment);
                        } else {
                            $("#comment").removeClass('is-invalid').siblings('p')
                                .removeClass('invalid-feedback').html('');
                        }

                        if (errors.rating) {
                            $(".product-rating-error").html(errors.rating);
                        } else {
                            $(".product-rating-error").html('');
                        }
                    } else {
                        window.location.href = "{{ route('front.product', $product->slug) }}";
                    }
                }
            });
        });
    </script>
@endsection