@extends('master')
@section('content')
    <div class="container">
        @include('layouts.header')
        <div class="d-flex mt-5 mb-5 gap-5">
            <h3>Affilated Users Commission</h3>
            <a href="/payout" class="btn btn-success">Payout</a>

        </div>
        <table class="table table-striped">
            <thead>
                <th scope="col">Name</th>
                <th scope="col">Sub Total</th>
                <th scope="col">Commission Owed</th>
                <th scope="col">Payout Status</th>
            </thead>
            @foreach ($affiliates as $affiliate)
                @if (isset($affiliate->orders) && count($affiliate->orders) > 0)
                    @foreach ($affiliate->orders as $order)
                        <tbody>
                            <td>{{ $affiliate->user->name ?? '' }}</td>
                            <td>{{ $order->subtotal ?? '' }}</td>
                            <td>{{ $order->commission_owed ?? '' }}</td>
                            <td>{{ $order->payout_status ?? '' }}</td>
                        </tbody>
                    @endforeach
                @endif
            @endforeach

        </table>
    </div>
@endsection
@push('footer_scripts')
@endpush
