@props(['value', 'required' => false])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
    @if ($required)
        <span class="text-red-600" aria-hidden="true">*</span>
        <span class="sr-only">{{ __('required') }}</span>
    @endif
</label>
