@props(['name', 'label', 'value' => '', 'rows' => 5])

<div class="mb-3">
    <label for="{{ $name }}" class="form-label">{{ $label }}</label>
    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        class="form-control @error($name) is-invalid @enderror"
        {{ $attributes }}
    >{{ old($name, $value) }}</textarea>
    @error($name)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
