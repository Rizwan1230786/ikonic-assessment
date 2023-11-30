@extends('master')
@section('content')
    <div class="container mt-5 mb-5">
        @include('layouts.header-guest')
        <div class="row">
            <div class="col-12">
                <form action="#!" id="createMerchant">
                    @csrf
                    <div class="mb-3">
                        <h3>Create an Order</h3>
                    </div>
                    <div class="mb-3">
                        <label for="merchant" class="form-label">Select Merchant</label>
                        <select name="merchant_domain" id="merchant" class="form-control">
                            @foreach ($merchants as $merchant)
                                <option value="{{ $merchant->domain ?? '' }}">{{ $merchant->domain ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Jhon Smith"
                            value="{{ auth()->check() ? auth()->user()->name : '' }}">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Jhon@smith.com" value="{{ auth()->check() ? auth()->user()->email : '' }}">
                    </div>
                    <div class="mb-3">
                        <label for="discount_code" class="form-label">Discount Code</label>
                        <input type="text" class="form-control" id="discount_code" name="discount_code"
                            placeholder="sahd-asd-fgas">
                    </div>
                    <div class="mb-3">
                        <label for="subtotal_price" class="form-label">Sub Total Price</label>
                        <input type="number" class="form-control" id="subtotal_price" name="subtotal_price"
                            placeholder="Total Price" value="100">
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
        $(document).ready(function() {
            $('input[type=text][name=name]').keyup(function() {
                var $th = $(this);
                $th.val($th.val().replace(/\s{2,}/g, ' ')); // Replace multiple spaces with a single space
                $th.val($th.val().replace(/^\s*/, '')); // Trim leading spaces

                // Prevent more than one space
                var words = $th.val().split(' ');
                if (words.length > 2) {
                    words = words.slice(0, 2); // Keep only the first two words
                    $th.val(words.join(' '));
                }

                // Remove any digits
                $th.val($th.val().replace(/\d+/g, '')); // Removes any numbers
            });
        });

        $(document).on('submit', '#createMerchant', function(event) {
            event.preventDefault()
            $('.field-error').remove()
            validation = true
            var email_address = $('input[name="email"]').val();
            var name = $('input[name="name"]').val();
            var merchant_domain = $('input[name="merchant_domain"]').val();
            var discount_code = $('input[name="discount_code"]').val();
            var subtotal_price = $('input[name="subtotal_price"]').val();
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
            if (name == '') {
                $('input[name="name"]').after(
                    '<small class="field-error">This is a required field..</small>');
                validation = false
            }
            if (merchant_domain == '') {
                $('input[name="merchant_domain"]').after(
                    '<small class="field-error">This is a required field..</small>');
                validation = false
            }
            if (discount_code == '') {
                $('input[name="discount_code"]').after(
                    '<small class="field-error">This is a required field..</small>');
                validation = false
            }
            if (subtotal_price == '') {
                $('input[name="subtotal_price"]').after(
                    '<small class="field-error">This is a required field..</small>');
                validation = false
            }
            if (validation == true) {
                form = $('#createMerchant').serializeArray()
                $.ajax({
                    url: '/create-order',
                    method: 'POST',
                    data: form,
                    dataType: 'JSON',
                    success: function(data) {
                        if (data.errors) {
                            $.each(data.errors, function(key, value) {
                                $('input[name="' + key + '"]').addClass(
                                        'errorinput')
                                    .after('<small class="field-error">' + value +
                                        '</small>');
                            })
                        } else {
                            $.Toast('', 'Order created successfully.', 'success', {
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
                                    window.location.href = '/';
                                }, 1500)
                            return false;
                        }
                    }
                })
            }
        })
    </script>
@endpush
