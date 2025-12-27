@extends('layouts.admin')

@section('content')
    <div class="flex-centerbetween mb-4">
        <h2 class="text-dark fw-bold mb-0">Edit Produk</h2>
    </div>
    <div class="card border-0">
        <div class="card-body">
            <form action="{{ route('produk.update', $item->id) }}" method="post" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label for="name">Nama Produk</label>
                    <input type="text" name="name" value="{{ $item->name }}" class="form-control" id="name"
                        autofocus>
                </div>
                <div class="mb-3">
                    <label for="category">Kategori</label>
                    <select id="category" name="category" class="form-select">
                        <option value="Baju" {{ $item->category == 'Baju' ? 'SELECTED' : '' }}>Baju</option>
                        <option value="Celana" {{ $item->category == 'Celana' ? 'SELECTED' : '' }}>Celana</option>
                        <option value="Jaket" {{ $item->category == 'Jaket' ? 'SELECTED' : '' }}>Jaket</option>
                        <option value="Aksesoris" {{ $item->category == 'Aksesoris' ? 'SELECTED' : '' }}>Aksesoris</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="price">Harga Produk</label>
                    <input type="number" name="price" value="{{ $item->price }}" id="price" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="image">Gambar Produk</label>
                    <input type="file" name="image" accept="image/*" id="image" class="form-control">
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class="bx bx-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('produk.index') }}" class="btn btn-light">
                        <i class="bx bx-arrow-back"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
