<div class="col-md-6">
    <label class="form-label">{{ $label }}</label>
    <div class="input-group">
    
        <select id="inputStates" class="form-select" {{ $attributes }}>
            <option selected hidden>{{ $label }}</option>
            @foreach ($options as $option)
                <option value="{{ $option->id }}">{{ $option->expediente_code }}</option>
            @endforeach
        </select>
    </div>
    @error($attributes['wire:model'])
        <span class="text-danger er">{{ $message }}</span>
    @enderror
</div>
