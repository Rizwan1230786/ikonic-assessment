@extends('master')
@section('content')
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-12">
                <form action="#!" id="addAffilates">
                    @csrf
                    <div class="mb-3">
                        <h3>Add Merchant Affilates</h3>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Jhon Smith">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Jhon@smith.com">
                    </div>
                    @guest
                        <div class="mb-3">
                            <label for="merchant" class="form-label">Select Merchant</label>
                            <select name="merchant_id" id="merchant" class="form-control">
                                @foreach ($users as $user)
                                    <option value="{{ $user->merchant->id ?? '' }}">{{ $user->merchant->domain ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endguest
                    @auth
                        <input type="hidden" name="merchant_id" value="{{ $user->merchant->id ?? '' }}">
                    @endauth
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
                // Remove any digits
                $th.val($th.val().replace(/\d+/g, '')); // Removes any numbers
            });
        });

        $(document).on('submit', '#addAffilates', function(event) {
            event.preventDefault()
            $('.field-error').remove()
            validation = true
            var email_address = $('input[name="email"]').val();
            var name = $('input[name="name"]').val();
            var merchant_id = $('input[name="merchant_id"]').val();
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
            if (validation == true) {
                form = $('#addAffilates').serializeArray()
                $.ajax({
                    url: '/save-affilate-user',
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
                            $.Toast('', 'User affilated successfully.', 'success', {
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
                                    window.location.href = '/affilate-users';
                                }, 1500)
                            return false;
                        }
                    }
                })
            }
        })
    </script>
@endpush
