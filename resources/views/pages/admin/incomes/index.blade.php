@extends('layouts.admin')

@section('content')
    <div class="flex-centerbetween mb-4">
        <h2 class="text-dark fw-bold mb-0">{{ $title }}</h2>
        {{-- <a href="{{ route('kasir.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Tambah Kasir
        </a> --}}
    </div>
    <div class="card border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Kode Pesanan</th>
                            <th>Nama Kasir</th>
                            <th>Nama Pelanggan</th>
                            <th>Item</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @foreach ($incomes as $item)
                            <tr class="align-middle">
                                <td>{{ $loop->iteration }}</td>
                                <td>#00{{ $item->id }}</td>
                                <td>{{ $item->cashier->name }}</td>
                                <td>{{ $item->customer_name }}</td>
                                <td>{{ $item->details->count() }}</td>
                                <td>Rp. {{ number_format($item->total) }}</td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-primary btn-sm py-1 px-3 rounded-1"
                                            data-bs-toggle="modal" data-bs-target="#detail{{ $item->id }}">
                                            <i class="bx bx-info-circle"></i> Detail Pesanan
                                        </button>
                                        <a href="{{ route('cashier.print', $item->id) }}" target="_blank"
                                            class="btn btn-success btn-sm py-1 px-3 rounded-1">
                                            <i class="bx bx-printer"></i> Cetak
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <div class="modal fade" id="detail{{ $item->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header border-0">
                                            <h5 class="modal-title">Detail Pesanan</h5>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="mb-1 text-secondary text-uppercase fw-medium fs-7">Detail Produk</p>
                                            @php
                                                $subtotal = 0;
                                            @endphp
                                            @foreach ($item->details as $detail)
                                                <div class="row mt-2">
                                                    <div class="col-7">
                                                        <p class="mb-0 text-dark fw-semibold">{{ $detail->produk->name }}
                                                        </p>
                                                        <p class="mb-0 text-secondary fs-7">Rp.
                                                            {{ number_format($detail->price) }}</p>
                                                    </div>
                                                    <div class="col-5">
                                                        <p class="mb-0 text-dark text-end fw-semibold">Rp.
                                                            {{ number_format($detail->price * $detail->quantity) }}</p>
                                                        <p class="mb-0 text-secondary text-end fs-7">
                                                            {{ $detail->quantity }}x</p>
                                                    </div>
                                                    @php
                                                        $subtotal += $detail->price * $detail->quantity;
                                                    @endphp
                                            @endforeach
                                        </div>
                                        <hr class="my-4" style="border-style: dashed;">
                                        <div class="d-flex align-items-center justify-content-between mt-2">
                                            <p class="mb-0 text-secondary">Subtotal</p>
                                            <p class="mb-0 text-dark fw-semibold">Rp. {{ number_format($subtotal) }}
                                            </p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-2">
                                            <p class="mb-0 text-secondary">Pajak</p>
                                            <p class="mb-0 text-dark fw-semibold">Rp. 12,000</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-2">
                                            <p class="mb-0 text-secondary">Total</p>
                                            <p class="mb-0 text-dark fw-semibold">Rp. {{ number_format($item->total) }}</p>
                                        </div>
                                        <hr class="my-4" style="border-style: dashed;">
                                        <div class="d-flex align-items-center justify-content-between mt-2">
                                            <p class="mb-0 text-secondary">Cash</p>
                                            <p class="mb-0 text-dark fw-semibold">Rp. {{ number_format($item->pay) }}</p>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-2">
                                            <p class="mb-0 text-secondary">Kembali</p>
                                            <p class="mb-0 text-dark fw-semibold">Rp. {{ number_format($item->return) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
            </div>
            @php
                $total += $item->total;
            @endphp
            @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-end">Total</td>
                    <td class="fw-semibold">Rp. {{ number_format($total) }}</td>
                    <td></td>
                </tr>
            </tfoot>
            </table>
        </div>
    </div>
    </div>
@endsection
