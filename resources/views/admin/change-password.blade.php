@extends('admin.layout.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Change Password</h1>
                </div>
                <div class="col-sm-6 text-right">
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            @include('admin.message')
            <form action="" method="post" id="changePasswordForm" old_password="changePasswordForm">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="old_password">Old Password</label>
                                    <input type="password" name="old_password" id="old_password" class="form-control"
                                        placeholder="Old Password">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="new_password">New Password</label>
                                    <input id="new_password"  name="new_password" type="password" class="form-control" name="password" placeholder="New Password">
                                    <span toggle="#new_password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                    {{-- <label for="new_passowrd">New Password</label>
                                    <input type="password"  name="new_passowrd" id="new_password" class="form-control"
                                        placeholder="New Passowrd"> --}}
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="confirm_passowrd">Confirm Password</label>
                                    <input type="password"  name="confirm_password" id="confirm_password" class="form-control"
                                        placeholder="Confirm Password">
                                    <p></p>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customjs')
<style>
    .field-icon {
  float: right;
  margin-right:10px;
  margin-top: -25px;
  z-index: 2;
}
</style>

    <script>
        $('#changePasswordForm').submit(function(event) {
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: '{{ route("admin.processChangePassword") }}',
                type: 'post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {

                    $("button[type=submit]").prop('disabled', false);
                    if (response["status"] == true) {
                        window.location.href = "{{ route('admin.showChangePasswordForm') }}";

                        // $('#old_password').removeClass('is-invalid')
                        //     .siblings('p')
                        //     .removeClass('invalid-feedback')
                        //     .html('');

                        // $('#new_password').removeClass('is-invalid')
                        //     .siblings('p')
                        //     .removeClass('invalid-feedback')
                        //     .html('');
                            
                        // $('#confirm_password').removeClass('is-invalid')
                        //     .siblings('p')
                        //     .removeClass('invalid-feedback')
                        //     .html('');


                    } else {
                        var errors = response['errors']
                        if (errors) {
                            if (errors['old_password']) {
                                $('#old_password').addClass('is-invalid')
                                    .siblings('p')
                                    .addClass('invalid-feedback')
                                    .html(errors['old_password']);
                            } else {
                                $('#old_password').removeClass('is-invalid')
                                    .siblings('p')
                                    .removeClass('invalid-feedback')
                                    .html('');
                            }
                            if (errors['new_password']) {
                                $('#new_password').addClass('is-invalid')
                                    .siblings('p')
                                    .addClass('invalid-feedback')
                                    .html(errors['new_password']);
                            } else {
                                $('#new_password').removeClass('is-invalid')
                                    .siblings('p')
                                    .removeClass('invalid-feedback')
                                    .html('');
                            }
                            if (errors['confirm_password']) {
                                $('#confirm_password').addClass('is-invalid')
                                    .siblings('p')
                                    .addClass('invalid-feedback')
                                    .html(errors['confirm_password']);
                            } else {
                                $('#confirm_password').removeClass('is-invalid')
                                    .siblings('p')
                                    .removeClass('invalid-feedback')
                                    .html('');
                            }
                        }
                    }
                },
                error: function(jqXHR, exception) {
                    // Handle error response
                    console.log('Something went wrong');
                }
            });
        });




        $(".toggle-password").click(function() {

$(this).toggleClass("fa-eye fa-eye-slash");
var input = $($(this).attr("toggle"));
if (input.attr("type") == "password") {
  input.attr("type", "text");
} else {
  input.attr("type", "password");
}
});

    </script>
@endsection
