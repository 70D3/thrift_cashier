@extends('layouts.admin')

@section('content')
    <div class="flex-centerbetween mb-4">
        <h2 class="text-dark fw-bold mb-0">Produk</h2>
        <a href="{{ route('produk.create') }}" class="btn btn-primary">
            <i class="bx bx-plus"></i> Tambah Produk
        </a>
    </div>
    <div class="card border-0">
        <div class="card-body">
            @if ($produks->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Nama Produk</th>
                                <th>Kategori Produk</th>
                                <th>Harga</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produks as $item)
                                <tr class="align-middle">
                                    <td>
                                        <img src="{{ url('storage/' . $item->image) }}" alt=""
                                            class="rounded object-fit-cover" width="40">
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td>{{ number_format($item->price) }}</td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('produk.edit', $item->id) }}"
                                                class="btn btn-warning btn-sm py-1 px-3 rounded-1 text-white">
                                                <i class="bx bx-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('produk.destroy', $item->id) }}" method="post">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-light btn-sm py-1 px-3 rounded-1" type="submit"
                                                    onclick="return confirm('Anda yakin ingin menghapus produk ini?')">
                                                    <i class="bx bx-trash"></i> Hapus
                                            </form>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-secondary text-center">No Data</p>
            @endif
        </div>
    </div>
@endsection
