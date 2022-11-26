@props([
    'color' => 'primary',
    'disabled' => false,
    'form' => null,
    'icon' => null,
    'keyBindings' => null,
    'indicator' => null,
    'label' => null,
    'size' => 'md',
    'tag' => 'button',
    'tooltip' => null,
    'type' => 'button',
])

@php
    $buttonClasses = [
        'filament-icon-button flex items-center justify-center rounded-full relative hover:bg-gray-500/5 focus:outline-none disabled:opacity-70 disabled:pointer-events-none dark:hover:bg-gray-300/5',
        match ($color) {
            'danger' => 'text-danger-500 focus:bg-danger-500/10',
            'gray' => 'text-gray-500 focus:bg-gray-500/10',
            'primary' => 'text-primary-500 focus:bg-primary-500/10',
            'secondary' => 'text-secondary-500 focus:bg-secondary-500/10',
            'success' => 'text-success-500 focus:bg-success-500/10',
            'warning' => 'text-warning-500 focus:bg-warning-500/10',
            default => $color,
        },
        'w-10 h-10' => $size === 'md',
        'w-8 h-8' => $size === 'sm',
        'w-8 h-8 md:w-10 md:h-10' => $size === 'sm md:md',
        'w-12 h-12' => $size === 'lg',
    ];

    $iconSize = match ($size) {
        'md' => 'h-5 w-5',
        'sm' => 'h-4 w-4',
        'sm md:md' => 'h-4 w-4 md:h-5 md:w-5',
        'lg' => 'h-6 w-6',
    };

    $iconClasses = 'filament-icon-button-icon';

    $indicatorClasses = \Illuminate\Support\Arr::toCssClasses([
        'filament-icon-button-indicator absolute -top-0.5 -right-0.5 inline-block h-4 w-4 rounded-full text-xs font-medium text-white',
        match ($color) {
            'danger' => 'bg-danger-600',
            'gray' => 'bg-gray-600',
            'primary' => 'bg-primary-600',
            'secondary' => 'bg-secondary-600',
            'success' => 'bg-success-600',
            'warning' => 'bg-warning-600',
            default => $color,
        },
    ]);

    $hasLoadingIndicator = filled($attributes->get('wire:target')) || filled($attributes->get('wire:click')) || (($type === 'submit') && filled($form));

    if ($hasLoadingIndicator) {
        $loadingIndicatorTarget = html_entity_decode($attributes->get('wire:target', $attributes->get('wire:click', $form)), ENT_QUOTES);
    }
@endphp

@if ($tag === 'button')
    <button
        @if ($keyBindings)
            x-mousetrap.global.{{ collect($keyBindings)->map(fn (string $keyBinding): string => str_replace('+', '-', $keyBinding))->implode('.') }}
        @endif
        @if ($tooltip)
            x-tooltip.raw="{{ $tooltip }}"
        @endif
        @if ($keyBindings || $tooltip)
            x-data="{}"
        @endif
        {{
            $attributes
                ->merge([
                    'disabled' => $disabled,
                    'title' => $label,
                    'type' => $type,
                ], escape: false)
                ->class($buttonClasses)
        }}
    >
        @if ($label)
            <span class="sr-only">
                {{ $label }}
            </span>
        @endif

        <x-filament::icon
            :name="$icon"
            alias="support::icon-button"
            :size="$iconSize"
            :class="$iconClasses"
            :wire:loading.remove.delay="$hasLoadingIndicator"
            :wire:target="$hasLoadingIndicator ? $loadingIndicatorTarget : null"
        />

        @if ($hasLoadingIndicator)
            <x-filament::loading-indicator
                x-cloak
                wire:loading.delay
                :wire:target="$loadingIndicatorTarget"
                :class="$iconClasses"
            />
        @endif

        @if ($indicator)
            <span class="{{ $indicatorClasses }}">
                {{ $indicator }}
            </span>
        @endif
    </button>
@elseif ($tag === 'a')
    <a
        @if ($keyBindings)
            x-mousetrap.global.{{ collect($keyBindings)->map(fn (string $keyBinding): string => str_replace('+', '-', $keyBinding))->implode('.') }}
        @endif
        @if ($tooltip)
            x-tooltip.raw="{{ $tooltip }}"
        @endif
        @if ($keyBindings || $tooltip)
            x-data="{}"
        @endif
        {{
            $attributes
                ->merge([
                    'title' => $label,
                ], escape: false)
                ->class($buttonClasses)
        }}
    >
        @if ($label)
            <span class="sr-only">
                {{ $label }}
            </span>
        @endif

        <x-filament::icon
            :name="$icon"
            alias="support::icon-button"
            :size="$iconSize"
            :class="$iconClasses"
        />

        @if ($indicator)
            <span class="{{ $indicatorClasses }}">
                {{ $indicator }}
            </span>
        @endif
    </a>
@endif
