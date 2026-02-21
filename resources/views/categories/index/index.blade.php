@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="form-group mb-2">
                <a href="{{url('categories/form/new')}}" class="btn btn-secondary">+ Category Baru</a>
            </div>
            <div class="card">
                <div class="card-header">Daftar Categories</div>

                <div class="card-body">
                    @include('categories.index.filter')
                    @include('categories.index.table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@include('categories.index.js')
@endsection