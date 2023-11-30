@extends('master')
@section('content')
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-12">
                <form action="#!" id="editMerchant">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id ?? '' }}">
                    <div class="mb-3">
                        <h3>Edit Merchant</h3>
                    </div>
                    <div class="mb-3">
                        <label for="display_name" class="form-label">Display Name</label>
                        <input type="text" class="form-control" id="display_name" name="display_name"
                            placeholder="Jhon Smith" value="{{ $user->merchant->display_name ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label for="domain" class="form-label">Domain</label>
                        <input type="text" class="form-control" id="domain" name="domain" placeholder="jhonsmith.co"
                            value="{{ $user->merchant->domain ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary" id="submit-merchant-form">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('footer_scripts')
    <script>
        $(document).ready(function() {
            $('input[type=text][name=display_name]').keyup(function() {
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

        $(document).on('submit', '#editMerchant', function(event) {
            event.preventDefault()
            $('.field-error').remove()
            validation = true
            var display_name = $('input[name="display_name"]').val();
            var id = $('input[name="id"]').val();
            var domain = $('input[name="domain"]').val();
            let csrf = $('input[name="_token"]').val();
            if (display_name == '') {
                $('input[name="display_name"]').after(
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
                    name: 'display_name',
                    value: display_name
                }, {
                    name: 'domain',
                    value: domain
                }, {
                    name: '_token',
                    value: csrf
                }, {
                    name: 'id',
                    value: id,
                })
                $.ajax({
                    url: '/edit-merchant-user',
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
                            $.Toast('', 'Merchant updated successfully.', 'success', {
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

        // if (email != '' && IsEmail(email) == false) {
        //     $('input[name="new_email"]').closest('.form-group').append(
        //         '<small class="field-error">Please enter a valid email address.</small>'
        //     );
        //     return false;
        // }
    </script>
@endpush
