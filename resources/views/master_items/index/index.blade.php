@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="form-group mb-2 d-flex gap-2">
                <a href="{{url('master-items/form/new')}}" class="btn btn-secondary">+ Item Baru</a>
                <a href="{{url('master-items/export-excel')}}" class="btn btn-success">
                    <i class="bi bi-file-excel"></i> Export Excel
                </a>
            </div>
            <div class="card">
                <div class="card-header">Master Items</div>

                <div class="card-body">
                    @include('master_items.index.filter')
                    @include('master_items.index.table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@include('master_items.index.js')
@endsection