@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'required' => false,
    'placeholder' => null,
    'icon' => null,
    'floating' => false,
    'help' => null,
    'id' => null,
    'disabled' => false,
    'readonly' => false,
])

@php
    $inputId = $id ?: $name;
    $computedValue = old($name, $value);
    $hasError = isset($errors) && $errors->has($name);
@endphp

<div class="mb-3">
    @if($floating)
        <div class="form-floating">
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                id="{{ $inputId }}"
                value="{{ $computedValue }}"
                placeholder="{{ $placeholder ?: ($label ?: 'Enter ' . $name) }}"
                {{ $required ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                {{ $readonly ? 'readonly' : '' }}
                {{ $attributes->merge(['class' => 'form-control form-control-custom' . ($hasError ? ' is-invalid' : '')]) }}
            >
            @if($label)
                <label for="{{ $inputId }}">
                    {{ $label }}
                    @if($required) <span class="text-danger">*</span> @endif
                </label>
            @endif
        </div>
    @else
        @if($label)
            <label for="{{ $inputId }}" class="form-label fw-semibold text-dark small mb-1">
                {{ $label }}
                @if($required) <span class="text-danger">*</span> @endif
            </label>
        @endif

        @if($icon)
            <div class="input-icon-group">
                <i class="bi {{ $icon }} input-icon"></i>
                <input
                    type="{{ $type }}"
                    name="{{ $name }}"
                    id="{{ $inputId }}"
                    value="{{ $computedValue }}"
                    placeholder="{{ $placeholder }}"
                    {{ $required ? 'required' : '' }}
                    {{ $disabled ? 'disabled' : '' }}
                    {{ $readonly ? 'readonly' : '' }}
                    {{ $attributes->merge(['class' => 'form-control form-control-custom' . ($hasError ? ' is-invalid' : '')]) }}
                >
            </div>
        @else
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                id="{{ $inputId }}"
                value="{{ $computedValue }}"
                placeholder="{{ $placeholder }}"
                {{ $required ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                {{ $readonly ? 'readonly' : '' }}
                {{ $attributes->merge(['class' => 'form-control form-control-custom' . ($hasError ? ' is-invalid' : '')]) }}
            >
        @endif
    @endif

    @if($help)
        <div class="form-text text-muted small mt-1">{{ $help }}</div>
    @endif

    @if(isset($errors) && $errors->has($name))
        <div class="invalid-feedback d-block small mt-1">{{ $errors->first($name) }}</div>
    @endif
</div>
