{{-- Partial view untuk form group settings --}}
@if(isset($group) && isset($settings))
<form action="{{ route('admin.settings.update-group', $group) }}" method="POST" class="group-settings-form">
    @csrf
    @method('PUT')
    
    <div class="row">
        @foreach($settings as $setting)
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title d-flex justify-content-between align-items-start">
                        {{ $setting->name }}
                        @if($setting->is_required)
                        <span class="badge bg-danger">Required</span>
                        @endif
                    </h6>
                    
                    @if($setting->description)
                    <p class="text-muted small mb-3">{{ $setting->description }}</p>
                    @endif

                    @switch($setting->type)
                        @case(\App\Models\Setting::TYPE_BOOLEAN)
                            <div class="form-check form-switch">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="{{ $setting->key }}"
                                       value="1"
                                       {{ $setting->value ? 'checked' : '' }}
                                       id="{{ $setting->key }}">
                                <label class="form-check-label" for="{{ $setting->key }}">
                                    {{ $setting->value ? 'Enabled' : 'Disabled' }}
                                </label>
                            </div>
                            @break

                        @case(\App\Models\Setting::TYPE_SELECT)
                            @if($setting->hasOptions())
                            <select class="form-select" name="{{ $setting->key }}">
                                <option value="">Select {{ $setting->name }}</option>
                                @foreach($setting->options as $value => $label)
                                <option value="{{ $value }}" 
                                        {{ $setting->value == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            @endif
                            @break

                        @case(\App\Models\Setting::TYPE_INTEGER)
                            <input type="number" 
                                   class="form-control" 
                                   name="{{ $setting->key }}"
                                   value="{{ $setting->value }}"
                                   {{ $setting->is_required ? 'required' : '' }}>
                            @break

                        @case(\App\Models\Setting::TYPE_JSON)
                            <textarea class="form-control" 
                                      name="{{ $setting->key }}"
                                      rows="4"
                                      {{ $setting->is_required ? 'required' : '' }}>{{ $setting->value }}</textarea>
                            <div class="form-text">JSON format</div>
                            @break

                        @default
                            <input type="text" 
                                   class="form-control" 
                                   name="{{ $setting->key }}"
                                   value="{{ $setting->value }}"
                                   {{ $setting->is_required ? 'required' : '' }}>
                    @endswitch

                    @if($setting->validation_rules)
                    <div class="mt-2">
                        <small class="text-muted">
                            <strong>Validation:</strong> {{ $setting->validation_rules }}
                        </small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> Save {{ $group }} Settings
        </button>
        <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to All Settings
        </a>
    </div>
</form>
@endif