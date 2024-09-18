@extends('admin.layout.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Shipping Management</h1>
                </div>
                <div class="col-sm-6 text-right">
                   
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @include('admin.message')
            <form action="{{ route('shipping.store') }}" method="POST" id="shippingform" name="shippingform">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <select name="country" id="country" class="form-control">
                                        <option value="">Select A Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                        <option value="rest_of_world">Rest Of the world</option>
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <input class="form-control" name="amount" id="amount" placeholder="Amount"
                                        type="text">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                </div>
            </form>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                                @if ($shippingCharges->isNotEmpty())
                                    @foreach ($shippingCharges as $shippingCharge)
                                        <tr>
                                            <td>{{ $shippingCharge->id }}</td>
                                            <td>{{ $shippingCharge->country_id == 'rest_of_world' ? 'Rest of the world ' : $shippingCharge->name }}
                                            </td>
                                            <td>${{ $shippingCharge->amount }}</td>
                                            <td>
                                                <a href="{{ route('shipping.edit', $shippingCharge->id) }}"
                                                    class="btn btn-primary"> Edit</a>
                                                <a href="javascript:void(0);" onclick="deleteRecord({{ $shippingCharge->id }})" class="btn btn-danger"> Delete</a>
                                            </td>

                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </section>
@endsection

@section('customjs')
    <script>
        $('#shippingform').submit(function(event) {
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);

            $.ajax({
                url: '{{ route('shipping.store') }}',
                type: 'POST',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    if (response.status === true) {
                        window.location.href = "{{ route('shipping.create') }}";
                    } else {
                        var errors = response.errors;
                        if (errors) {
                            if (errors.country) {
                                $('#country').addClass('is-invalid')
                                    .siblings('p')
                                    .addClass('invalid-feedback')
                                    .html(errors.country);
                            } else {
                                $('#country').removeClass('is-invalid')
                                    .siblings('p')
                                    .removeClass('invalid-feedback')
                                    .html('');
                            }
                            if (errors.amount) {
                                $('#amount').addClass('is-invalid')
                                    .siblings('p')
                                    .addClass('invalid-feedback')
                                    .html(errors.amount);
                            } else {
                                $('#amount').removeClass('is-invalid')
                                    .siblings('p')
                                    .removeClass('invalid-feedback')
                                    .html('');
                            }
                        }
                    }
                },
                error: function(jqXHR, exception) {
                    console.log('Something went wrong');
                }
            });
        });

     


        function deleteRecord(id) {

            var url = '{{ route("shipping.delete", "ID") }}'
            var newUrl = url.replace('ID', id);

            if (confirm("Are You Sure You Want To Delete")) {
                $.ajax({
                    url: newUrl,
                    type: 'delete',
                    data: {},
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response["status"]) {
                            window.location.href = "{{ route('shipping.create') }}";
                        }
                    }
                });
            }
        }
    </script>
@endsection
