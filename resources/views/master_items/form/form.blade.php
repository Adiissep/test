@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" enctype="multipart/form-data">
    @csrf

    @if($method == 'edit')
    <div class="form-group mb-3">
        <label>Kode Barang</label>
        <input type="text" class="form-control" name="kode_barang" readonly value="{{ $item->kode ?? '' }}">
    </div>
    @endif

    <div class="form-group mb-3">
        <label>Nama <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('nama') is-invalid @enderror" 
            name="nama" required value="{{ old('nama', $item->nama ?? '') }}">
        @error('nama')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label>Harga Beli <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('harga_beli') is-invalid @enderror" 
            name="harga_beli" required value="{{ old('harga_beli', $item->harga_beli ?? '') }}">
        @error('harga_beli')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group mb-3">
        <label>Laba (dalam persen) <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('laba') is-invalid @enderror" 
            name="laba" required value="{{ old('laba', $item->laba ?? '') }}">
        @error('laba')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @php $selected = old('supplier', $item->supplier ?? ''); @endphp
    <div class="form-group mb-3">
        <label>Supplier <span class="text-danger">*</span></label>
        <select class="form-control @error('supplier') is-invalid @enderror" required name="supplier">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Tokopaedi') selected @endif value="Tokopaedi">Tokopaedi</option>
            <option @if($selected == 'Bukulapuk') selected @endif value="Bukulapuk">Bukulapuk</option>
            <option @if($selected == 'TokoBagas') selected @endif value="TokoBagas">TokoBagas</option>
            <option @if($selected == 'E Commurz') selected @endif value="E Commurz">E Commurz</option>
            <option @if($selected == 'Blublu') selected @endif value="Blublu">Blublu</option>
        </select>
        @error('supplier')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @php $selected = old('jenis', $item->jenis ?? ''); @endphp
    <div class="form-group mb-3">
        <label>Jenis <span class="text-danger">*</span></label>
        <select class="form-control @error('jenis') is-invalid @enderror" required name="jenis">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Obat') selected @endif value="Obat">Obat</option>
            <option @if($selected == 'Alkes') selected @endif value="Alkes">Alkes</option>
            <option @if($selected == 'Matkes') selected @endif value="Matkes">Matkes</option>
            <option @if($selected == 'Umum') selected @endif value="Umum">Umum</option>
            <option @if($selected == 'ATK') selected @endif value="ATK">ATK</option>
        </select>
        @error('jenis')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Field Categories (Many-to-Many) -->
    <div class="form-group mb-3">
        <label>Categories</label>
        <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
            @if($categories->count() > 0)
                @foreach($categories as $category)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                            name="categories[]" 
                            value="{{ $category->id }}" 
                            id="category-{{ $category->id }}"
                            @if(in_array($category->id, old('categories', $selectedCategories ?? []))) checked @endif>
                        <label class="form-check-label" for="category-{{ $category->id }}">
                            {{ $category->nama }} <small class="text-muted">({{ $category->kode }})</small>
                        </label>
                    </div>
                @endforeach
            @else
                <p class="text-muted mb-0">Belum ada category. <a href="{{ url('categories/form/new') }}">Buat Category Baru</a></p>
            @endif
        </div>
        <small class="text-muted">Pilih satu atau lebih category</small>
    </div>

    <div class="form-group mb-3">
        <label>Foto</label>
        <input type="file" class="form-control @error('foto') is-invalid @enderror" 
            name="foto" accept="image/*" id="foto-input">
        <small class="text-muted">Format: JPEG, PNG, JPG, GIF. Maksimal 2MB.</small>
        @error('foto')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        
        <!-- Preview foto baru -->
        <div class="mt-2" id="preview-container" style="display: none;">
            <img id="foto-preview" src="#" alt="Preview" style="max-width: 150px; max-height: 150px; object-fit: cover;">
            <p class="text-muted">Preview foto baru</p>
        </div>

        <!-- Foto saat ini (untuk edit) -->
        @if($method == 'edit' && !empty($item->foto))
            <div class="mt-2" id="current-foto">
                <img src="{{ url('storage/items/' . $item->foto) }}" alt="Foto Item" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                <p class="text-muted">Foto saat ini</p>
            </div>
        @endif
    </div>
    
    <button class="btn btn-primary">Simpan</button>
</form>

<script>
    document.getElementById('foto-input').addEventListener('change', function(e) {
        const preview = document.getElementById('foto-preview');
        const previewContainer = document.getElementById('preview-container');
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
        }
    });
</script>