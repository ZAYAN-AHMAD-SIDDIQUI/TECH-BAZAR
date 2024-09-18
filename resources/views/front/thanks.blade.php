@extends('front.layout.app')

@section('content')
    <section class="container">
        <div class="col-md-12 text-center py-5">
            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif

            <!-- Increased font size for the THANK YOU text -->
            <h1 style="font-size: 72px; font-weight: bold;">THANK YOU!</h1>

            <!-- Increased size for the checkmark -->
            <div style="font-size: 100px; color: #28a745; margin-bottom: 20px;">
                ✔
            </div>

            <p>Your Order Id Is: <strong>{{ $id }}</strong></p>
            <p style="width:300;" >Thanks a bunch for placing your order with us. We really appreciate you giving us a moment of your time today. Thanks for being you.</p>

            <!-- "Go Back to Site" Button -->
            <a href="/" class="btn btn-dark mt-4" style="font-size: 18px; padding: 10px 20px;">
                Go Back to Site
            </a>
        </div>
    </section>
@endsection
