@extends('master')
@section('content')
    <div class="container">
        @include('layouts.header')
        <div class="d-flex mt-5 mb-5 gap-5">
            <h3>Merchant Profile</h3>
            <a href="/add-affilates/{{ $user->id ?? '' }}" class="btn btn-success">Add Affiliates</a>
        </div>
        {{-- <form action="/search-merchant">
            <label for="email" class="form-label">Search Merchant Using Email</label>
            <div class="mb-3 d-flex w-50 gap-5">
                <input type="email" class="form-control" id="email" name="email" placeholder="jhonsmith@gmail.com"
                    autocomplete="off" required>
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form> --}}

        <table class="table table-striped">
            <thead>
                <th scope="col">Display Name</th>
                <th scope="col">Domain</th>
                <th scope="col">Turn Customers into Affiliates</th>
                <th scope="col">Commission Rate</th>
                <th scope="col">Action</th>
            </thead>
            <tbody>
                <td>{{ $user->merchant->display_name ?? '' }}</td>
                <td>{{ $user->merchant->domain ?? '' }}</td>
                <td>{{ $user->merchant->turn_customers_into_affiliates ?? '' }}</td>
                <td>{{ $user->merchant->default_commission_rate ?? '' }}</td>
                <td><a href="/edit-merchant/{{ $user->id ?? '' }}" class="btn btn-success">Edit</a></td>
            </tbody>
        </table>
    </div>
@endsection
@push('footer_scripts')
@endpush
