<div>
    <div class="row g-4">
        <div class="col-md-7">
            <h2 class="text-dark fw-bold mb-4">Kasir</h2>

            <ul class="nav nav-pills gap-1 pb-3 mb-4 border-bottom">
                <li class="nav-item">
                    <a class="nav-link {{ $selectedCategory === 'Semua' ? 'active' : '' }}" href="#"
                        wire:click.prevent="selectCategory('Semua')">Semua</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $selectedCategory === 'Baju' ? 'active' : '' }}" href="#"
                        wire:click.prevent="selectCategory('Baju')">Baju</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $selectedCategory === 'Celana' ? 'active' : '' }}" href="#"
                        wire:click.prevent="selectCategory('Celana')">Celana</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $selectedCategory === 'Jaket' ? 'active' : '' }}" href="#"
                        wire:click.prevent="selectCategory('Jaket')">Jaket</a>
                </li>
            </ul>

            <div class="row g-3">
                @foreach ($produks as $item)
                    <div class="col-6 col-lg-4 d-flex">
                        <div class="card product-card w-100" style="cursor: pointer;"
                            wire:click="addToCart({{ $item->id }})">
                            <div class="card-body p-4">
                                <img src="{{ url('storage/' . $item->image) }}" class="product-img"
                                    alt="{{ $item->name }}">
                                <h4 class="card-title mt-4 mb-2">{{ $item->name }}</h4>
                                <div
                                    class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-1">
                                    <p class="mb-0 text-secondary fs-7">{{ $item->category }}</p>
                                    <p class="mb-0 text-primary fw-semibold">Rp. {{ number_format($item->price) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-5">
            <div class="card border-0">
                <div class="card-body px-4">
                    @if ($transaction != null)
                        <h4 class="text-dark fw-semibold mb-3">Order #{{ $transaction->id }}</h4>

                        @php
                            $subtotal = 0;
                        @endphp
                        @foreach ($transaction->details as $item)
                            <div class="row align-items-center g-3 mt-3">
                                <div class="col-3 col-lg-2">
                                    <img src="{{ url('storage/' . $item->produk->image) }}" alt=""
                                        class="rounded-2">
                                </div>
                                <div class="col-9 col-lg-4">
                                    <p class="mb-0 fw-semibold text-dark">{{ $item->produk->name }}</p>
                                    <p class="mb-0 fw-semibold text-secondary fs-7">Rp.
                                        {{ number_format($item->produk->price) }}</p>
                                </div>
                                <div class="col-4 col-lg-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <button class="btn btn-sm btn-quantity rounded-circle"
                                            wire:click="quantity_minus({{ $item->id }})">
                                            <i class="bx bx-minus"></i>
                                        </button>
                                        <p class="mb-0 text-dark">
                                            {{ $item->quantity }}
                                        </p>
                                        <button class="btn btn-sm btn-quantity rounded-circle"
                                            wire:click="addToCart({{ $item->produk_id }})">
                                            <i class="bx bx-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <p class="mb-0 text-dark fw-bold text-end">Rp.
                                        {{ number_format($item->produk->price * $item->quantity) }}
                                    </p>
                                </div>
                                <div class="col-2 col-lg-1">
                                    <button class="btn btn-sm btn-light btn-delete" type="button"
                                        wire:click="delete({{ $item->id }})"><i class="bx bx-trash"></i></button>
                                </div>
                            </div>

                            @php
                                $subtotal += $item->produk->price * $item->quantity;
                            @endphp
                        @endforeach


                        <hr class="mt-5 mb-4">

                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <p class="mb-0 text-secondary">Subtotal</p>
                            <p class="mb-0 text-dark fw-bold">Rp. {{ number_format($subtotal) }}</p>
                        </div>
                        <hr class="my-4" style="border-style: dashed;">

                        <div class="d-flex align-items-center justify-content-between mb-5">
                            <p class="mb-0 text-secondary">Total</p>
                            <p class="mb-0 text-dark fw-bold fs-5">Rp. {{ number_format($subtotal) }}</p>
                        </div>
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal"
                                    data-bs-target="#checkoutModal">Tunai</button>

                            </div>
                            <div class="col">
                                <button class="btn btn-warning w-100" data-bs-toggle="modal"
                                    data-bs-target="#checkoutNonTunai">
                                    Non Tunai
                                </button>
                            </div>
                        </div>
                    @else
                        <p class="mb-0 text-center text-secondary">Pilih untuk membeli</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="checkoutModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Checkout</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="checkout">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="customer_name"><i class="bx bx-user"></i></span>
                            <input type="text" class="form-control" wire:model="customer_name" id="name">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="nominalInput">Rp.</span>
                            <input type="text" class="form-control" wire:model="pay">
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Proses</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="checkoutNonTunai">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Non Tunai</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="nonTunai">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bx bx-user"></i></span>
                            <input type="text" class="form-control" wire:model="customer_name"
                                placeholder="Nama Customer">
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Proses</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
