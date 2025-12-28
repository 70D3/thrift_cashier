@extends('layouts.cashier')
@section('content')
<div class="row g-4">
    <h2 class="text-dark fw-bold mb-4">Order List</h2>
    @forelse($orders as $order)
    <div class="col-6 col-lg-3">
        <div class="card" type="button" data-bs-toggle="modal" data-bs-target="#changeStatus-{{ $order->id }}">
            <div class="card-body">
                <p class="mb-0 text-secondary text-end fs-7">#{{ $order->id }}</p>
                <h5 class="text-dark mb-0">{{ $order->customer_name }}</h5>
                <p class="mb-2 text-secondary fs-8">
                    {{ $order->details->count() }} Items
                </p>
                @php
                $badge = 'bg-secondary';
                if ($order->status === 'Completed') {
                $badge = 'bg-primary';
                } elseif ($order->status === 'Cancelled') {
                $badge = 'bg-danger';
                } elseif ($order->status === 'Waiting') {
                $badge = 'bg-warning';
                } elseif ($order->status === 'Cart') {
                $badge = 'bg-info';
                }
                @endphp
                <span class="badge {{ $badge }}">{{ $order->status }}</span>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="changeStatus-{{ $order->id }}">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Pesanan #{{ $order->id }}</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('cashier.order-update', $order->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="status-{{ $order->id }}">Ubah Status</label>
                            <select id="status-{{ $order->id }}" name="status" class="form-select">
                                <option value="Waiting" {{ $order->status == 'Waiting' ? 'selected' : '' }}>Waiting
                                </option>
                                <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>
                                    Paid</option>
                                <option value="cancel" {{ $order->status == 'cancel' ? 'selected' : '' }}>
                                    Cancelled</option>
                                <option value="cart" {{ $order->status == 'cart' ? 'selected' : '' }}>Cart
                                </option>
                            </select>
                        </div>
                        <button class="btn btn-primary" type="submit">Ubah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <p class="text-muted">Belum ada pesanan.</p>
    </div>
    @endforelse
</div>
@endsection