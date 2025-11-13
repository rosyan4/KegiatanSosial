@extends('admin.layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
@php
    // Helper functions untuk view
    function getGroupLabel($group) {
        $labels = [
            'general' => 'Pengaturan Umum',
            'app' => 'Aplikasi Mobile', 
            'email' => 'Email & SMTP',
            'notification' => 'Notifikasi',
            'security' => 'Keamanan',
            'payment' => 'Pembayaran',
            'social' => 'Media Sosial',
            'api' => 'API & Integrasi',
            'system' => 'Sistem',
            'appearance' => 'Tampilan & UI',
        ];
        return $labels[$group] ?? ucfirst(str_replace('_', ' ', $group));
    }

    function getGroupIcon($group) {
        $icons = [
            'general' => 'cog',
            'app' => 'mobile-alt',
            'email' => 'envelope', 
            'notification' => 'bell',
            'security' => 'shield-alt',
            'payment' => 'credit-card',
            'social' => 'share-alt',
            'api' => 'code',
            'system' => 'server',
            'appearance' => 'palette',
        ];
        return $icons[$group] ?? 'cog';
    }

    function getGroupDescription($group) {
        $descriptions = [
            'general' => 'Pengaturan umum aplikasi dan konfigurasi dasar',
            'app' => 'Konfigurasi aplikasi mobile dan pengaturan tampilan',
            'email' => 'Konfigurasi server email dan template', 
            'notification' => 'Pengaturan notifikasi dan preferensi',
            'security' => 'Pengaturan keamanan dan autentikasi',
            'payment' => 'Konfigurasi gateway pembayaran',
            'social' => 'Integrasi media sosial dan sharing',
            'api' => 'Pengaturan API dan integrasi eksternal',
            'system' => 'Pengaturan sistem dan performa',
            'appearance' => 'Kustomisasi tampilan dan tema',
        ];
        return $descriptions[$group] ?? 'Pengaturan konfigurasi sistem';
    }
@endphp

<div class="row">
    <div class="col-md-3">
        <!-- Settings Navigation -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-sliders-h me-2"></i>Grup Pengaturan
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($groups as $group)
                    <a href="#group-{{ $group }}" 
                       class="list-group-item list-group-item-action setting-group-link {{ $loop->first ? 'active' : '' }}"
                       data-bs-toggle="list"
                       data-group="{{ $group }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-{{ getGroupIcon($group) }} me-2 text-muted"></i>
                                {{ getGroupLabel($group) }}
                            </div>
                            <span class="badge bg-secondary rounded-pill">{{ $settings[$group]->count() }}</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            <div class="card-footer">
                <form action="{{ route('admin.settings.initialize-defaults') }}" method="POST" class="d-grid">
                    @csrf
                    <button type="submit" class="btn btn-outline-warning btn-sm" 
                            onclick="return confirm('Apakah Anda yakin ingin mengembalikan semua pengaturan ke nilai default? Tindakan ini tidak dapat dibatalkan.')">
                        <i class="fas fa-undo me-1"></i> Reset ke Default
                    </button>
                </form>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title mb-0">Statistik</h6>
            </div>
            <div class="card-body">
                @php
                    $totalSettings = 0;
                    $requiredSettings = 0;
                    foreach($settings as $groupSettings) {
                        $totalSettings += $groupSettings->count();
                        $requiredSettings += $groupSettings->where('is_required', true)->count();
                    }
                @endphp
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Pengaturan:</span>
                    <strong>{{ $totalSettings }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Grup:</span>
                    <strong>{{ $groups->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Diperlukan:</span>
                    <strong>{{ $requiredSettings }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <!-- Settings Content -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-1" id="current-group-title">
                            {{ getGroupLabel($groups->first()) }}
                        </h5>
                        <p class="text-muted mb-0 small" id="current-group-description">
                            {{ getGroupDescription($groups->first()) }}
                        </p>
                    </div>
                    <div class="d-none" id="save-button-container">
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary" id="discard-changes">
                                <i class="fas fa-times me-1"></i> Batal
                            </button>
                            <button type="button" class="btn btn-primary" id="save-settings">
                                <i class="fas fa-save me-1"></i> Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form id="settings-form" method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="tab-content">
                        @foreach($groups as $group)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                             id="group-{{ $group }}">
                            
                            @if($settings[$group]->count() > 0)
                            <div class="row">
                                @foreach($settings[$group] as $setting)
                                <div class="col-lg-6 mb-4">
                                    <div class="card setting-card h-100">
                                        <div class="card-header py-2 bg-light">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 fw-bold">
                                                    {{ $setting->name }}
                                                    @if($setting->is_required)
                                                    <span class="text-danger" title="Wajib diisi">*</span>
                                                    @endif
                                                </h6>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                            type="button" 
                                                            data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                        <i class="fas fa-cog"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="#" 
                                                               onclick="showSettingInfo('{{ $setting->key }}', '{{ $setting->name }}')">
                                                                <i class="fas fa-info-circle me-2"></i>Info
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#" 
                                                               onclick="resetSetting('{{ $setting->key }}', '{{ $setting->name }}')">
                                                                <i class="fas fa-undo me-2"></i>Reset
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @if($setting->description)
                                            <p class="text-muted small mb-3">{{ $setting->description }}</p>
                                            @endif

                                            <!-- Boolean Type -->
                                            @if($setting->type === 'boolean')
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input setting-input" 
                                                           type="checkbox" 
                                                           id="setting-{{ $setting->key }}"
                                                           name="{{ $setting->key }}"
                                                           value="1"
                                                           {{ $setting->value ? 'checked' : '' }}
                                                           data-original-value="{{ $setting->value ? '1' : '0' }}"
                                                           data-type="boolean">
                                                    <label class="form-check-label" for="setting-{{ $setting->key }}">
                                                        {{ $setting->value ? 'Aktif' : 'Nonaktif' }}
                                                    </label>
                                                </div>

                                            <!-- Select Type -->
                                            @elseif($setting->type === 'select' && $setting->options)
                                                @php
                                                    $options = is_array($setting->options) 
                                                        ? $setting->options 
                                                        : json_decode($setting->options, true);
                                                @endphp
                                                @if($options)
                                                <select class="form-select setting-input" 
                                                        id="setting-{{ $setting->key }}"
                                                        name="{{ $setting->key }}"
                                                        data-original-value="{{ $setting->value }}"
                                                        data-type="select"
                                                        {{ $setting->is_required ? 'required' : '' }}>
                                                    <option value="">Pilih {{ $setting->name }}</option>
                                                    @foreach($options as $optionValue => $optionLabel)
                                                    <option value="{{ $optionValue }}" 
                                                            {{ $setting->value == $optionValue ? 'selected' : '' }}>
                                                        {{ $optionLabel }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @else
                                                <input type="text" 
                                                       class="form-control" 
                                                       value="Options not configured"
                                                       disabled>
                                                @endif

                                            <!-- Integer Type -->
                                            @elseif($setting->type === 'integer')
                                                <input type="number" 
                                                       class="form-control setting-input" 
                                                       id="setting-{{ $setting->key }}"
                                                       name="{{ $setting->key }}"
                                                       value="{{ $setting->value }}"
                                                       data-original-value="{{ $setting->value }}"
                                                       data-type="integer"
                                                       {{ $setting->is_required ? 'required' : '' }}>

                                            <!-- JSON Type -->
                                            @elseif($setting->type === 'json')
                                                <textarea class="form-control setting-input font-monospace" 
                                                          id="setting-{{ $setting->key }}"
                                                          name="{{ $setting->key }}"
                                                          rows="4"
                                                          data-original-value="{{ $setting->value }}"
                                                          data-type="json"
                                                          {{ $setting->is_required ? 'required' : '' }}>{{ $setting->value }}</textarea>
                                                <div class="form-text">
                                                    <i class="fas fa-code me-1"></i>Format JSON
                                                    <button type="button" class="btn btn-sm btn-outline-secondary ms-2" 
                                                            onclick="formatJSON('setting-{{ $setting->key }}')">
                                                        Format
                                                    </button>
                                                </div>

                                            <!-- Textarea Type -->
                                            @elseif($setting->type === 'textarea')
                                                <textarea class="form-control setting-input" 
                                                          id="setting-{{ $setting->key }}"
                                                          name="{{ $setting->key }}"
                                                          rows="3"
                                                          data-original-value="{{ $setting->value }}"
                                                          data-type="textarea"
                                                          {{ $setting->is_required ? 'required' : '' }}>{{ $setting->value }}</textarea>

                                            <!-- Default Text Type -->
                                            @else
                                                <input type="text" 
                                                       class="form-control setting-input" 
                                                       id="setting-{{ $setting->key }}"
                                                       name="{{ $setting->key }}"
                                                       value="{{ $setting->value }}"
                                                       data-original-value="{{ $setting->value }}"
                                                       data-type="text"
                                                       {{ $setting->is_required ? 'required' : '' }}>
                                            @endif

                                            @if($setting->validation_rules)
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-shield-alt me-1"></i>
                                                    <strong>Validasi:</strong> {{ $setting->validation_rules }}
                                                </small>
                                            </div>
                                            @endif

                                            <div class="mt-2 d-flex justify-content-between">
                                                <small class="text-muted">
                                                    <i class="fas fa-key me-1"></i>
                                                    <code>{{ $setting->key }}</code>
                                                </small>
                                                <small class="text-muted">
                                                    Tipe: <span class="badge bg-info">{{ $setting->type }}</span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-5">
                                <i class="fas fa-cogs fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Tidak ada pengaturan</h5>
                                <p class="text-muted">Belum ada pengaturan yang dikonfigurasi untuk grup ini.</p>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </form>
            </div>

            <div class="card-footer bg-light">
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Gunakan tombol <strong>Simpan</strong> untuk menyimpan perubahan pengaturan.
                        </small>
                    </div>
                    <div class="col-md-6 text-end">
                        <small class="text-muted">
                            Terakhir diperbarui: {{ now()->format('d M Y H:i') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Setting Info Modal -->
<div class="modal fade" id="settingInfoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pengaturan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="settingInfoContent">
                <!-- Content will be loaded via JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.setting-card {
    transition: all 0.3s ease;
    border: 1px solid #dee2e6;
}

.setting-card:hover {
    border-color: #0d6efd;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.setting-card.changed {
    border-color: #ffc107;
    background-color: #fffcf0;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.1);
}

.setting-group-link.active {
    background-color: #0d6efd !important;
    border-color: #0d6efd !important;
    color: white !important;
}

.setting-group-link.active .badge {
    background-color: white !important;
    color: #0d6efd !important;
}

.form-switch .form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
}

.font-monospace {
    font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    font-size: 0.875rem;
}

.card-header.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@endpush

@push('scripts')
<script>
// Group icons mapping
const groupIcons = {
    'general': 'cog',
    'app': 'mobile-alt',
    'email': 'envelope',
    'notification': 'bell',
    'security': 'shield-alt',
    'payment': 'credit-card',
    'social': 'share-alt',
    'api': 'code',
    'system': 'server',
    'appearance': 'palette'
};

// Group descriptions
const groupDescriptions = {
    'general': 'Pengaturan umum aplikasi dan konfigurasi dasar',
    'app': 'Konfigurasi aplikasi mobile dan pengaturan tampilan',
    'email': 'Konfigurasi server email dan template',
    'notification': 'Pengaturan notifikasi dan preferensi',
    'security': 'Pengaturan keamanan dan autentikasi',
    'payment': 'Konfigurasi gateway pembayaran',
    'social': 'Integrasi media sosial dan sharing',
    'api': 'Pengaturan API dan integrasi eksternal',
    'system': 'Pengaturan sistem dan performa',
    'appearance': 'Kustomisasi tampilan dan tema'
};

// Group labels
const groupLabels = {
    'general': 'Pengaturan Umum',
    'app': 'Aplikasi Mobile',
    'email': 'Email & SMTP',
    'notification': 'Notifikasi',
    'security': 'Keamanan',
    'payment': 'Pembayaran',
    'social': 'Media Sosial',
    'api': 'API & Integrasi',
    'system': 'Sistem',
    'appearance': 'Tampilan & UI'
};

class SettingsManager {
    constructor() {
        this.hasUnsavedChanges = false;
        this.currentGroup = '{{ $groups->first() }}';
        this.initialize();
    }

    initialize() {
        this.initializeEventListeners();
        this.initializeGroupNavigation();
        this.checkAllChanges();
    }

    initializeEventListeners() {
        // Setting input changes
        document.querySelectorAll('.setting-input').forEach(input => {
            input.addEventListener('change', (e) => this.handleSettingChange(e.target));
            if (input.type !== 'checkbox') {
                input.addEventListener('input', (e) => this.handleSettingChange(e.target));
            }
        });

        // Save button
        document.getElementById('save-settings')?.addEventListener('click', () => this.saveSettings());
        
        // Discard button
        document.getElementById('discard-changes')?.addEventListener('click', () => this.discardChanges());

        // Before unload warning
        window.addEventListener('beforeunload', (e) => {
            if (this.hasUnsavedChanges) {
                e.preventDefault();
                e.returnValue = 'Anda memiliki perubahan yang belum disimpan. Apakah Anda yakin ingin meninggalkan halaman ini?';
                return e.returnValue;
            }
        });

        // Group navigation
        document.querySelectorAll('.setting-group-link').forEach(link => {
            link.addEventListener('click', (e) => this.handleGroupNavigation(e));
        });
    }

    initializeGroupNavigation() {
        const firstGroup = document.querySelector('.setting-group-link.active');
        if (firstGroup) {
            this.currentGroup = firstGroup.dataset.group;
            this.updateGroupHeader(this.currentGroup);
        }
    }

    handleGroupNavigation(e) {
        e.preventDefault();
        const target = e.target.closest('a');
        const group = target.dataset.group;
        
        this.currentGroup = group;
        this.updateGroupHeader(group);
    }

    updateGroupHeader(group) {
        const title = document.getElementById('current-group-title');
        const description = document.getElementById('current-group-description');
        
        if (title) {
            title.textContent = groupLabels[group] || this.formatGroupName(group);
        }
        
        if (description) {
            description.textContent = groupDescriptions[group] || 'Pengaturan konfigurasi sistem';
        }
    }

    formatGroupName(group) {
        return group.charAt(0).toUpperCase() + group.slice(1).replace('_', ' ');
    }

    handleSettingChange(input) {
        const card = input.closest('.setting-card');
        const originalValue = input.dataset.originalValue;
        const currentValue = this.getInputValue(input);

        if (currentValue !== originalValue) {
            card.classList.add('changed');
        } else {
            card.classList.remove('changed');
        }

        this.checkAllChanges();
    }

    getInputValue(input) {
        switch (input.type) {
            case 'checkbox':
                return input.checked ? '1' : '0';
            case 'select-one':
                return input.value;
            default:
                return input.value;
        }
    }

    checkAllChanges() {
        const changedCards = document.querySelectorAll('.setting-card.changed');
        this.hasUnsavedChanges = changedCards.length > 0;
        this.toggleSaveButton();
    }

    toggleSaveButton() {
        const saveButtonContainer = document.getElementById('save-button-container');
        if (this.hasUnsavedChanges) {
            saveButtonContainer?.classList.remove('d-none');
        } else {
            saveButtonContainer?.classList.add('d-none');
        }
    }

    async saveSettings() {
        const form = document.getElementById('settings-form');
        const saveButton = document.getElementById('save-settings');
        
        if (!form || !saveButton) return;

        // Show loading state
        const originalText = saveButton.innerHTML;
        saveButton.disabled = true;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';

        try {
            const formData = new FormData(form);
            
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (response.ok) {
                const result = await response.json();
                this.showAlert(result.message || 'Pengaturan berhasil disimpan!', 'success');
                
                // Update original values
                this.updateOriginalValues();
                this.hasUnsavedChanges = false;
                this.toggleSaveButton();
            } else {
                throw new Error('Gagal menyimpan pengaturan');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showAlert('Gagal menyimpan pengaturan: ' + error.message, 'danger');
        } finally {
            // Reset button
            saveButton.disabled = false;
            saveButton.innerHTML = originalText;
        }
    }

    updateOriginalValues() {
        document.querySelectorAll('.setting-input').forEach(input => {
            const currentValue = this.getInputValue(input);
            input.dataset.originalValue = currentValue;
            input.closest('.setting-card').classList.remove('changed');
        });
    }

    discardChanges() {
        if (confirm('Apakah Anda yakin ingin membatalkan semua perubahan?')) {
            document.querySelectorAll('.setting-input').forEach(input => {
                const originalValue = input.dataset.originalValue;
                
                if (input.type === 'checkbox') {
                    input.checked = originalValue === '1';
                } else if (input.type === 'select-one') {
                    input.value = originalValue;
                } else {
                    input.value = originalValue;
                }
                
                input.closest('.setting-card').classList.remove('changed');
            });
            
            this.hasUnsavedChanges = false;
            this.toggleSaveButton();
        }
    }

    showAlert(message, type) {
        // Remove existing alerts
        document.querySelectorAll('.alert-dismissible').forEach(alert => alert.remove());

        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        const cardBody = document.querySelector('.card-body');
        if (cardBody) {
            cardBody.insertBefore(alert, cardBody.firstChild);
        }

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }
}

// Global functions
function showSettingInfo(key, name) {
    const modal = new bootstrap.Modal(document.getElementById('settingInfoModal'));
    const content = document.getElementById('settingInfoContent');
    
    content.innerHTML = `
        <div class="text-center">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p>Memuat informasi...</p>
        </div>
    `;
    
    modal.show();
    
    // Simulate API call - in real app, fetch from server
    setTimeout(() => {
        content.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tr><th>Key:</th><td><code>${key}</code></td></tr>
                        <tr><th>Nama:</th><td>${name}</td></tr>
                        <tr><th>Tipe:</th><td><span class="badge bg-info">text</span></td></tr>
                        <tr><th>Grup:</th><td>general</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm">
                        <tr><th>Wajib:</th><td><span class="badge bg-success">Ya</span></td></tr>
                        <tr><th>Urutan:</th><td>1</td></tr>
                        <tr><th>Status:</th><td><span class="badge bg-success">Aktif</span></td></tr>
                    </table>
                </div>
            </div>
            <div class="mt-3">
                <h6>Deskripsi:</h6>
                <p class="text-muted">Ini adalah pengaturan "${name}" dengan key <code>${key}</code>.</p>
            </div>
        `;
    }, 500);
}

function resetSetting(key, name) {
    if (confirm(`Apakah Anda yakin ingin mengembalikan pengaturan "${name}" ke nilai default?`)) {
        const input = document.getElementById(`setting-${key}`);
        if (input) {
            // In a real application, you would fetch the default value from the server
            const defaultValue = '';
            
            if (input.type === 'checkbox') {
                input.checked = defaultValue === '1';
            } else {
                input.value = defaultValue;
            }
            
            // Trigger change event
            input.dispatchEvent(new Event('change', { bubbles: true }));
        }
    }
}

function formatJSON(textareaId) {
    const textarea = document.getElementById(textareaId);
    if (!textarea) return;
    
    try {
        if (textarea.value.trim()) {
            const parsed = JSON.parse(textarea.value);
            textarea.value = JSON.stringify(parsed, null, 2);
            textarea.dispatchEvent(new Event('change', { bubbles: true }));
        }
    } catch (e) {
        alert('JSON tidak valid: ' + e.message);
    }
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    window.settingsManager = new SettingsManager();
});
</script>
@endpush