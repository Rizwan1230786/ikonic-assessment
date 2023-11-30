@extends('master')
@section('content')
    <div class="container">
        @include('layouts.header')
        <div class="d-flex mt-5 mb-5 gap-5">
            <h3>Merchants List</h3>
            <a href="/add-affilates/{{ $merchant->user_id ?? '' }}" class="btn btn-success">Add Affiliates</a>
        </div>

        <table class="table table-striped">
            <thead>
                <th scope="col">Display Name</th>
                <th scope="col">Domain</th>
                <th scope="col">Turn Customers into Affiliates</th>
                <th scope="col">Commission Rate</th>
                <th scope="col">Action</th>
            </thead>
            <tbody>
                <td>{{ $merchant->display_name ?? '' }}</td>
                <td>{{ $merchant->domain ?? '' }}</td>
                <td>{{ $merchant->turn_customers_into_affiliates ?? '' }}</td>
                <td>{{ $merchant->default_commission_rate ?? '' }}</td>
                <td><a href="/edit-merchant/{{ $merchant->id ?? '' }}" class="btn btn-success">Edit</a></td>
            </tbody>
        </table>
    </div>
@endsection
@push('footer_scripts')
@endpush
