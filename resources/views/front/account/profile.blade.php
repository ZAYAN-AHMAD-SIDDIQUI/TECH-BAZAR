@extends('front.layout.app')
@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                    <li class="breadcrumb-item">Settings</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-12">
                    @include('front.account.parts.message')
                </div>
                <div class="col-md-3">
                    @include('front.account.parts.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                        </div>
                        <form action="" name="profileform" id="profileform">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="name">Name</label>
                                        <input value="{{ $user->name }}" type="text" name="name" id="name"
                                            placeholder="Enter Your Name" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email">Email</label>
                                        <input value="{{ $user->email }}" type="text" name="email" id="email"
                                            placeholder="Enter Your Email" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone">Phone</label>
                                        <input value="{{ $user->phone }}" type="text" name="phone" id="phone"
                                            placeholder="Enter Your Phone" class="form-control">
                                        <p></p>
                                    </div>


                                    <div class="d-flex">
                                        <button class="btn btn-dark">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card mt-5">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Address</h2>
                        </div>
                        <form action="" name="addressform" id="addressform">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class=" col-md-6 mb-3">
                                        <label for="name">First Name</label>
                                        <input value="{{ (!empty($address)) ? $address->first_name : '' }}" type="text" name="first_name" id="first_name"
                                            placeholder="Enter Your First Name" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class=" col-md-6  mb-3">
                                        <label for="name">Last Name</label>
                                        <input value="{{ (!empty($address)) ? $address->last_name : '' }}" type="text" name="last_name" id="last_name"
                                            placeholder="Enter Your Last Name" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6  mb-3">
                                        <label for="email">Email</label>
                                        <input value=" {{ (!empty($address)) ? $address->email : '' }}" type="text" name="email" id="email"
                                            placeholder="Enter Your Email" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6  mb-3">
                                        <label for="mobile">Mobile</label>
                                        <input value="{{ (!empty($address)) ? $address->mobile : '' }}" type="text" name="mobile" id="mobile"
                                            placeholder="Enter Your Mobile No." class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="country">Country</label>
                                     <select name="country_id" id="country_id" class="form-control">
                                        <option value="">Select Country</option>
                                        @if ($countries->isNotEmpty())
                                        @foreach ($countries as $country)
                                        <option {{ (!empty($address) && $address->country_id == $country->id ) ? 'selected' : '' }} value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                            
                                        @endif
                                     </select>
                                        <p></p>
                                    </div>
                                    <div class= "mb-3">
                                        <label for="address">Address</label>
                                     <textarea name="address" id="address" cols="30" rows="10" class="form-control">{{ (!empty($address)) ? $address->address : '' }}</textarea>
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="address">Appartment</label>
                                        <input value="{{ (!empty($address)) ? $address->apartment : '' }}"  class="form-control" type="text" name="apartment" id="partment" placeholder="Apartment">
                                        <p></p>
                                    </div>


                                    <div class="col-md-6 mb-3">
                                        <label for="address">City</label>
                                        <input value="{{ (!empty($address)) ? $address->city : '' }}"  class="form-control" type="text" name="city" id="city" placeholder="City">
                                        <p></p>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="address">State</label>
                                        <input value="{{ (!empty($address)) ? $address->state : '' }}"  class="form-control" type="text" name="state" id="state" placeholder="State">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="address">Zip</label>
                                        <input value="{{ (!empty($address)) ? $address->zip : '' }}"  class="form-control" type="text" name="zip" id="zip" placeholder="Zip">
                                        <p></p>
                                    </div>




                                    <div class="d-flex">
                                        <button class="btn btn-dark">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('customjs')
    <script>
       $('#profileform').submit(function(event) {
        event.preventDefault();
        $.ajax({
            url: '{{ route('account.updateProfile') }}',
            type: 'POST',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response) {
                if (response.status === true) {
                    // Success: Redirect to profile
                    window.location.href = '{{ route("account.profile") }}';
                } else {
                    // Validation Errors: Show them in the form
                    var errors = response.errors;

                    if (errors.name) {
                        $('#profileform #name').addClass('is-invalid').siblings('p').html(errors.name).addClass(
                            'invalid-feedback');
                    } else {
                        $('#profileform #name').removeClass('is-invalid').siblings('p').html('').removeClass(
                            'invalid-feedback');
                    }
                    if (errors.email) {
                        $('#profileform #email').addClass('is-invalid').siblings('p').html(errors.email).addClass(
                            'invalid-feedback');
                    } else {
                        $('#profileform #email').removeClass('is-invalid').siblings('p').html('').removeClass(
                            'invalid-feedback');
                    }
                    if (errors.phone) {
                        $('#profileform #phone').addClass('is-invalid').siblings('p').html(errors.phone).addClass(
                            'invalid-feedback');
                    } else {
                        $('#profileform #phone').removeClass('is-invalid').siblings('p').html('').removeClass(
                            'invalid-feedback');
                    }
                }
            }
        });
    });

    $('#addressform').submit(function(event) {
        event.preventDefault();
        $.ajax({
            url: '{{ route("account.updateAddress") }}',
            type: 'POST',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response) {
                if (response.status === true) {
                    // Success: Redirect to profile
                    window.location.href = '{{ route("account.profile") }}';
                } else {
                    var errors = response.errors;

                    if (errors.first_name) {
                        $('#first_name').addClass('is-invalid').siblings('p').html(errors.first_name).addClass(
                            'invalid-feedback');
                    } else {
                        $('#first_name').removeClass('is-invalid').siblings('p').html('').removeClass(
                            'invalid-feedback');
                    }
                    if (errors.last_name) {
                        $('#last_name').addClass('is-invalid').siblings('p').html(errors.last_name).addClass(
                            'invalid-feedback');
                    } else {
                        $('#last_name').removeClass('is-invalid').siblings('p').html('').removeClass(
                            'invalid-feedback');
                    }
                    if (errors.email) {
                        $('#addressform #email').addClass('is-invalid').siblings('p').html(errors.email).addClass(
                            'invalid-feedback');
                    } else {
                        $( '#addressform #email').removeClass('is-invalid').siblings('p').html('').removeClass(
                            'invalid-feedback');
                    }
                    if (errors.country_id) {
                        $('#country_id').addClass('is-invalid').siblings('p').html(errors.country_id).addClass(
                            'invalid-feedback');
                    } else {
                        $('#country_id').removeClass('is-invalid').siblings('p').html('').removeClass(
                            'invalid-feedback');
                    }
                    if (errors.address) {
                        $('#address').addClass('is-invalid').siblings('p').html(errors.address).addClass(
                            'invalid-feedback');
                    } else {
                        $('#address').removeClass('is-invalid').siblings('p').html('').removeClass(
                            'invalid-feedback');
                    }
                    if (errors.mobile) {
                        $('#mobile').addClass('is-invalid').siblings('p').html(errors.mobile).addClass(
                            'invalid-feedback');
                    } else {
                        $('#mobile').removeClass('is-invalid').siblings('p').html('').removeClass(
                            'invalid-feedback');
                    }
                    if (errors.apartment) {
                        $('#Apartment').addClass('is-invalid').siblings('p').html(errors.apartment).addClass(
                            'invalid-feedback');
                    } else {
                        $('#apartment').removeClass('is-invalid').siblings('p').html('').removeClass(
                            'invalid-feedback');
                    }
                    if (errors.city) {
                        $('#city').addClass('is-invalid').siblings('p').html(errors.city).addClass(
                            'invalid-feedback');
                    } else {
                        $('#city').removeClass('is-invalid').siblings('p').html('').removeClass(
                            'invalid-feedback');
                    }
                    if (errors.state) {
                        $('#state').addClass('is-invalid').siblings('p').html(errors.state).addClass(
                            'invalid-feedback');
                    } else {
                        $('#state').removeClass('is-invalid').siblings('p').html('').removeClass(
                            'invalid-feedback');
                    }
                    if (errors.zip) {
                        $('#zip').addClass('is-invalid').siblings('p').html(errors.zip).addClass(
                            'invalid-feedback');
                    } else {
                        $('#zip').removeClass('is-invalid').siblings('p').html('').removeClass(
                            'invalid-feedback');
                    }
                    
                }
            }
        });
    });
    </script>
@endsection
