@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="form-group mb-2">
                <a href="{{url('categories')}}" class="btn btn-secondary">Kembali ke Daftar Category</a>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detail Category</span>
                    <!-- Tombol Download PDF -->
                    <a href="{{ url('categories/export-pdf/' . $category->kode) }}" class="btn btn-danger btn-sm">
                        <i class="bi bi-file-pdf"></i> Download PDF
                    </a>
                </div>

                <div class="card-body">
                    <!-- Info Category -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="120">Kode</th>
                                    <td width="10">:</td>
                                    <td><strong>{{ $category->kode }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>:</td>
                                    <td><strong>{{ $category->nama }}</strong></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6 text-end">
                            <a class="btn btn-info" href="{{url('categories/form/edit')}}/{{$category->id}}">Edit</a>
                            <a class="btn btn-danger" href="{{url('categories/delete')}}/{{$category->id}}" 
                                onclick="return confirm('Yakin ingin menghapus category ini?');">Delete</a>
                        </div>
                    </div>

                    <hr>

                    <!-- List Item yang Memiliki Category Ini -->
                    <h5>Daftar Item dengan Category Ini ({{ $items->count() }} item)</h5>
                    
                    @if($items->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Item</th>
                                    <th>Nama Item</th>
                                    <th>Jenis</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->jenis }}</td>
                                    <td>Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ url('master-items/view/' . $item->kode) }}" class="btn btn-sm btn-primary">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-info">
                            Belum ada item yang memiliki category ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection