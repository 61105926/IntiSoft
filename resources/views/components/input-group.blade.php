<div class="col-md-6">
    <label class="form-label">{{ $label }}</label>
    <div class="input-group">
        <span class="input-group-text">
            <i class="{{ $icon }}"></i>
        </span>
        {{ $slot }}
    </div>
    @error($attributes['wire:model'])
        <span class="text-danger er">{{ $message }}</span>
    @enderror
</div>
