@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST">
    @csrf

    @if($method == 'edit')
    <div class="form-group mb-3">
        <label>Kode Category</label>
        <input type="text" class="form-control" value="{{ $category->kode ?? '' }}" readonly>
        <small class="text-muted">Kode tidak dapat diubah</small>
    </div>
    @else
    <div class="alert alert-info">
        <small><i class="bi bi-info-circle"></i> Kode category akan di-generate otomatis (contoh: CAT0001)</small>
    </div>
    @endif

    <div class="form-group mb-3">
        <label>Nama Category <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('nama') is-invalid @enderror" 
            name="nama" required value="{{ old('nama', $category->nama ?? '') }}">
        @error('nama')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button class="btn btn-primary">Simpan</button>
</form>