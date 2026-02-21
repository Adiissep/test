@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="form-group mb-2">
                <a href="{{url('master-items')}}" class="btn btn-secondary">Kembali ke Daftar Item</a>
            </div>
            <div class="card">
                <div class="card-header">Master Item View</div>

                <div class="card-body">
                    <!-- Tampilan Foto -->
                    <div class="text-center mb-4">
                        @if($data->foto)
                            <img src="{{ url('storage/items/' . $data->foto) }}" 
                                alt="{{ $data->nama }}" 
                                style="max-width: 250px; max-height: 250px; object-fit: cover; border-radius: 8px;"
                                class="img-thumbnail">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                style="width: 250px; height: 250px; margin: 0 auto; border-radius: 8px;">
                                <span class="text-muted">No Image</span>
                            </div>
                        @endif
                    </div>

                    <table class="table">
                        <tr>
                            <th width="150">Kode</th>
                            <td width="10">:</td>
                            <td>{{ $data->kode }}</td>
                        </tr>
                        <tr>
                            <th>Nama</th>
                            <td>:</td>
                            <td>{{ $data->nama }}</td>
                        </tr>
                        <tr>
                            <th>Jenis</th>
                            <td>:</td>
                            <td>{{ $data->jenis }}</td>
                        </tr>
                        <tr>
                            <th>Harga Beli</th>
                            <td>:</td>
                            <td>Rp {{ number_format($data->harga_beli, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Laba</th>
                            <td>:</td>
                            <td>{{ $data->laba }}%</td>
                        </tr>
                        <tr>
                            <th>Harga Jual</th>
                            <td>:</td>
                            <td>Rp {{ number_format($data->harga_jual, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Supplier</th>
                            <td>:</td>
                            <td>{{ $data->supplier }}</td>
                        </tr>
                        <tr>
                            <th>Categories</th>
                            <td>:</td>
                            <td>
                                @if($data->categories->count() > 0)
                                    @foreach($data->categories as $category)
                                        <span class="badge bg-primary me-1">{{ $category->nama }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">Tidak ada category</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                    
                    <div class="mt-3">
                        <a class="btn btn-info" href="{{ url('master-items/form/edit') }}/{{ $data->id }}">Edit</a>
                        <a class="btn btn-danger" href="{{ url('master-items/delete') }}/{{ $data->id }}" 
                            onclick="return confirm('Yakin ingin menghapus item ini?');">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection