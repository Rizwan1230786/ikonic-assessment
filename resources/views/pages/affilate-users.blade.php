@extends('master')
@section('content')
    <div class="container">
        @include('layouts.header')
        <div class="d-flex mt-5 mb-5 gap-5">
            <h3>Affilated Users List</h3>
        </div>
        <table class="table table-striped">
            <thead>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Commission rate</th>
                <th scope="col">Discount Code</th>
            </thead>
            @foreach ($affiliates as $affiliate)
                <tbody>
                    <td>{{ $affiliate->user->name ?? '' }}</td>
                    <td>{{ $affiliate->user->email ?? '' }}</td>
                    <td>{{ $affiliate->commission_rate ?? '' }}</td>
                    <td>{{ $affiliate->discount_code ?? '' }}</td>
                </tbody>
            @endforeach

        </table>
    </div>
@endsection
@push('footer_scripts')
@endpush
