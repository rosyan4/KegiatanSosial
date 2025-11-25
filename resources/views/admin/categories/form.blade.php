<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
            @csrf
            @if(isset($category))
                @method('PUT')
            @endif
            
            <div class="row">
                <!-- Basic Information -->
                <div class="col-md-6">
                    <h5 class="mb-3 text-primary"><i class="fas fa-info-circle me-2"></i>Informasi Dasar</h5>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                               value="{{ old('name', $category->name ?? '') }}" required placeholder="Contoh: Olahraga, Seni, Pendidikan">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" 
                                  rows="3" placeholder="Deskripsi singkat tentang kategori">{{ old('description', $category->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="sort_order" class="form-label">Urutan Tampil <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                            id="sort_order" name="sort_order" min="0" required
                            value="{{ old('sort_order', $category->sort_order ?? '') }}" 
                            placeholder="Contoh: 1, 2, 3">
                        @error('sort_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Appearance & Status -->
                <div class="col-md-6">
                    <h5 class="mb-3 text-primary"><i class="fas fa-palette me-2"></i>Tampilan & Status</h5>

                    <div class="mb-3">
                        <label for="color" class="form-label">Warna <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                   id="color" name="color" 
                                   value="{{ old('color', $category->color ?? '#007bff') }}" 
                                   title="Pilih warna untuk kategori">
                            <input type="text" class="form-control @error('color') is-invalid @enderror" 
                                   id="color_hex" name="color_hex" 
                                   value="{{ old('color', $category->color ?? '#007bff') }}" 
                                   placeholder="#007bff" pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$">
                        </div>
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Pilih warna yang merepresentasikan kategori</div>
                    </div>

                    <div class="mb-3">
                        <label for="icon" class="form-label">Ikon</label>
                        <div class="input-group">
                            <span class="input-group-text"><i id="iconPreview" class="{{ old('icon', $category->icon ?? 'fas fa-tag') }}"></i></span>
                            <input type="text" class="form-control @error('icon') is-invalid @enderror" id="icon" name="icon" 
                                   value="{{ old('icon', $category->icon ?? '') }}" 
                                   placeholder="fas fa-tag" list="iconSuggestions">
                            <datalist id="iconSuggestions">
                                <option value="fas fa-tag">
                                <option value="fas fa-running">
                                <option value="fas fa-music">
                                <option value="fas fa-paint-brush">
                                <option value="fas fa-book">
                                <option value="fas fa-utensils">
                                <option value="fas fa-heart">
                                <option value="fas fa-graduation-cap">
                                <option value="fas fa-briefcase">
                                <option value="fas fa-home">
                                <option value="fas fa-tree">
                                <option value="fas fa-users">
                                <option value="fas fa-calendar-alt">
                                <option value="fas fa-star">
                                <option value="fas fa-gift">
                            </datalist>
                        </div>
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Gunakan class ikon Font Awesome (contoh: fas fa-tag)</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" 
                                   name="is_active" value="1"
                                   {{ old('is_active', isset($category) ? $category->is_active : true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Kategori Aktif
                            </label>
                        </div>
                        <div class="form-text">Nonaktifkan untuk menyembunyikan kategori dari pilihan</div>
                    </div>

                    <!-- Preview -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">Pratinjau Kategori</h6>
                        </div>
                        <div class="card-body text-center">
                            <div id="categoryPreview" class="d-inline-block p-3 rounded" 
                                 style="background-color: {{ old('color', $category->color ?? '#007bff') }}; color: white;">
                                <i id="previewIcon" class="{{ old('icon', $category->icon ?? 'fas fa-tag') }} fa-2x mb-2"></i>
                                <div id="previewName" class="fw-bold">{{ old('name', $category->name ?? 'Nama Kategori') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i> {{ isset($category) ? 'Update' : 'Simpan' }}
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Color picker synchronization
        const colorInput = document.getElementById('color');
        const colorHexInput = document.getElementById('color_hex');
        const preview = document.getElementById('categoryPreview');

        colorInput.addEventListener('input', function() {
            colorHexInput.value = this.value;
            updatePreview();
        });

        colorHexInput.addEventListener('input', function() {
            if (this.value.match(/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/)) {
                colorInput.value = this.value;
                updatePreview();
            }
        });

        // Icon preview
        $('#icon').on('input', function() {
            const iconClass = $(this).val() || 'fas fa-tag';
            $('#iconPreview').attr('class', iconClass);
            $('#previewIcon').attr('class', iconClass + ' fa-2x mb-2');
        });

        // Name preview
        $('#name').on('input', function() {
            const name = $(this).val() || 'Nama Kategori';
            $('#previewName').text(name);
        });

        function updatePreview() {
            const color = colorInput.value;
            preview.style.backgroundColor = color;
            
            // Determine text color based on background brightness
            const hex = color.replace('#', '');
            const r = parseInt(hex.substr(0, 2), 16);
            const g = parseInt(hex.substr(2, 2), 16);
            const b = parseInt(hex.substr(4, 2), 16);
            const brightness = ((r * 299) + (g * 587) + (b * 114)) / 1000;
            preview.style.color = brightness > 128 ? 'black' : 'white';
        }

        // Initialize preview
        updatePreview();
    });
</script>
@endpush