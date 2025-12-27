<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <style>
        @media print {
            @page {
                margin: 0;
                size: 58mm auto;
            }

            body {
                margin: 0;
                padding: 5px;
                font-size: 10px;
                width: 100%;
            }

            .logo img {
                max-width: 100px;
            }

            p {
                margin: 0;
                padding: 0;
            }

            .row {
                display: flex;
                justify-content: space-between;
                width: 100%;
            }

            hr {
                border: 1px dashed #000;
            }
        }

        .body {
            font-family: Arial, sans-serif;
        }

        .logo {
            text-align: center;
        }

        .details {
            margin-top: 10px;
        }

        .d-flex {
            display: flex;
            justify-content: space-between;
        }

        .text-right {
            text-align: right;
        }

        .fw-semibold {
            font-weight: bold;
        }

        .text-uppercase {
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="logo">
        <img src="{{ url('assets/images/logo.png') }}" alt="Logo">
        <p>Cashier</p>
    </div>
    <p class="text-uppercase fw-semibold">Detail Produk</p>
    @php
        $subtotal = 0;
    @endphp
    @foreach ($transaction->details as $item)
        <div class="details">
            <div class="row">
                <div class="left">
                    <p class="fw-semibold">{{ $item->produk->name }}</p>
                    <p class="fw-semibold">Rp. {{ number_format($item->price) }}</p>
                </div>
                <div class="right text-right">
                    <p class="fw-semibold">Rp. {{ number_format($item->price * $item->quantity) }}</p>
                    <p>{{ number_format($item->quantity) }}</p>
                </div>
            </div>
        </div>
        @php
            $subtotal += $item->price * $item->quantity;
        @endphp
    @endforeach
    <hr>
    <div class="d-flex">
        <p>Subtotal</p>
        <p class="fw-semibold">Rp. {{ number_format($subtotal) }}</p>
    </div>
    <div class="d-flex">
        <p>Pajak</p>
        <p class="fw-semibold">Rp. 12,000</p>
    </div>
    <div class="d-flex">
        <p>Total</p>
        <p class="fw-semibold">Rp. {{ number_format($transaction->total) }}</p>
    </div>
    <hr>
    <div class="d-flex">
        <p>Cash</p>
        <p class="fw-semibold">Rp. {{ number_format($transaction->pay) }}</p>
    </div>
    <div class="d-flex">
        <p>Kembali</p>
        <p class="fw-semibold">Rp. {{ number_format($transaction->return) }}</p>
    </div>
    <script>
        window.print()
    </script>
</body>

</html>
