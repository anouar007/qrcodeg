@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => null,
    'required' => false,
    'floating' => false,
    'help' => null,
    'id' => null,
    'disabled' => false,
])

@php
    $selectId = $id ?: $name;
    $currentSelected = old($name, $selected);
    $hasError = isset($errors) && $errors->has($name);
@endphp

<div class="mb-3">
    @if($floating)
        <div class="form-floating">
            <select
                name="{{ $name }}"
                id="{{ $selectId }}"
                {{ $required ? 'required' : '' }}
                {{ $disabled ? 'disabled' : '' }}
                {{ $attributes->merge(['class' => 'form-select form-select-custom' . ($hasError ? ' is-invalid' : '')]) }}
            >
                @if($placeholder)
                    <option value="" disabled {{ $currentSelected === null || $currentSelected === '' ? 'selected' : '' }}>
                        {{ $placeholder }}
                    </option>
                @endif

                @foreach($options as $val => $optLabel)
                    <option value="{{ $val }}" {{ (string) $currentSelected === (string) $val ? 'selected' : '' }}>
                        {{ $optLabel }}
                    </option>
                @endforeach

                {{ $slot }}
            </select>
            @if($label)
                <label for="{{ $selectId }}">
                    {{ $label }}
                    @if($required) <span class="text-danger">*</span> @endif
                </label>
            @endif
        </div>
    @else
        @if($label)
            <label for="{{ $selectId }}" class="form-label fw-semibold text-dark small mb-1">
                {{ $label }}
                @if($required) <span class="text-danger">*</span> @endif
            </label>
        @endif

        <select
            name="{{ $name }}"
            id="{{ $selectId }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge(['class' => 'form-select form-select-custom' . ($hasError ? ' is-invalid' : '')]) }}
        >
            @if($placeholder)
                <option value="" {{ $currentSelected === null || $currentSelected === '' ? 'selected' : '' }}>
                    {{ $placeholder }}
                </option>
            @endif

            @foreach($options as $val => $optLabel)
                <option value="{{ $val }}" {{ (string) $currentSelected === (string) $val ? 'selected' : '' }}>
                    {{ $optLabel }}
                </option>
            @endforeach

            {{ $slot }}
        </select>
    @endif

    @if($help)
        <div class="form-text text-muted small mt-1">{{ $help }}</div>
    @endif

    @if(isset($errors) && $errors->has($name))
        <div class="invalid-feedback d-block small mt-1">{{ $errors->first($name) }}</div>
    @endif
</div>
