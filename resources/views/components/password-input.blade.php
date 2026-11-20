@props (['name', 'label', 'autocomplete' => 'new-password', 'help' => null])
<div class="mb-3">
  <label for="{{ $name }}" class="form-label">{{ $label }}</label>
  <div class="input-group input-group-merge">
    <input
      id="{{ $name }}"
      name="{{ $name }}"
      type="password"
      autocomplete="{{ $autocomplete }}"
      {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($name)]) }}
      @if ($help) aria-describedby="{{ $name }}-help" @endif
      @error
      ($name)
      aria-invalid="true"
      @enderror
      required
    />
    <button
      class="btn btn-outline-secondary"
      type="button"
      data-password-toggle="{{ $name }}"
      aria-controls="{{ $name }}"
      aria-pressed="false"
      aria-label="Tampilkan {{ strtolower($label) }}"
    >
      <i class="bx bx-hide" aria-hidden="true"></i>
    </button>
  </div>
  @if ($help)
    <div id="{{ $name }}-help" class="form-text">{{ $help }}</div>
  @endif
</div>
