@extends('master')
@section('content')
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-12">
                <form action="#!" id="createMerchant">
                    @csrf
                    <div class="mb-3">
                        <h3>Create Merchant</h3>
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
                    <div class="mb-3">
                        <label for="domain" class="form-label">Domain</label>
                        <input type="text" class="form-control" id="domain" name="domain" placeholder="jhonsmith.co">
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
            var domain = $('input[name="domain"]').val();
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
            if (name != '' && name.trim().split(' ').length < 2) {
                $('input[name="name"]').after(
                    '<small class="field-error">Please enter a Full Name (first and last name).</small>');
                validation = false
            }
            if (name == '') {
                $('input[name="name"]').after(
                    '<small class="field-error">This is a required field..</small>');
                validation = false
            }
            if (domain == '') {
                $('input[name="domain"]').after(
                    '<small class="field-error">This is a required field..</small>');
                validation = false
            }
            if (validation == true) {
                form = $('#createMerchant').serializeArray()
                form.push({
                    name: 'name',
                    value: name
                }, {
                    name: 'email',
                    value: email_address
                }, {
                    name: 'domain',
                    value: domain
                }, {
                    name: 'api_key',
                    value: '12345678'
                }, {
                    name: '_token',
                    value: csrf
                }, )
                $.ajax({
                    url: '/save-merchant-user',
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
                            $.Toast('', 'User created successfully.', 'success', {
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

        // if (email != '' && IsEmail(email) == false) {
        //     $('input[name="new_email"]').closest('.form-group').append(
        //         '<small class="field-error">Please enter a valid email address.</small>'
        //     );
        //     return false;
        // }
    </script>
@endpush
