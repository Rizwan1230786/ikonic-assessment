@extends('master')
@section('content')
    <div class="container mt-5 mb-5">
        @include('layouts.header-guest')
        <div class="row">
            <div class="col-12">
                <form action="#!" id="loginMerchant">
                    @csrf
                    <div class="mb-3">
                        <h3>Login User</h3>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Jhon@smith.com">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Enter Pssword">
                    </div>
                    <div class="mb-3">
                        <a href="/create-merchant">Sign Up as a merchant</a>
                        <br>
                        <a href="/create-affilate">Sign Up as a affilate</a>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary" id="submit-merchant-form">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('footer_scripts')
    <script>
        $(document).on('submit', '#loginMerchant', function(event) {
            event.preventDefault()
            $('.field-error').remove()
            validation = true
            var email_address = $('input[name="email"]').val();
            var password = $('input[name="password"]').val();
            let csrf = $('input[name="_token"]').val();
            if (IsEmail(email_address) == false && email_address != undefined && email_address != '') {
                $('input[name="email"]').after(
                    '<small class="field-error">Please enter a valid email address.</small>');
                validation = false;
            }
            if (email_address == '') {
                $('input[name="email"]').after(
                    '<small class="field-error">This is a required field.</small>');
                validation = false;
            }
            if (password == '') {
                $('input[name="password"]').after(
                    '<small class="field-error">This is a required field..</small>');
                validation = false
            }
            if (validation == true) {
                form = $('#loginMerchant').serializeArray()
                $.ajax({
                    url: '/login-user',
                    method: 'POST',
                    data: form,
                    dataType: 'JSON',
                    success: function(data) {
                        if (data.status == false) {
                            $.Toast('', data.message, 'warning', {
                                has_icon: false,
                                has_close_btn: true,
                                stack: true,
                                fullscreen: false,
                                timeout: 5000,
                                sticky: true,
                                has_progress: true,
                                rtl: false,
                            });
                        } else {
                            $.Toast('', data.message, 'success', {
                                has_icon: false,
                                has_close_btn: true,
                                stack: true,
                                fullscreen: false,
                                timeout: 5000,
                                sticky: true,
                                has_progress: true,
                                rtl: false,
                            });
                            setTimeout(
                                function() {
                                    window.location.href = '/merchant/dashboard';
                                }, 1500)
                            return false;
                        }
                    }
                })
            }
        })
    </script>
@endpush
