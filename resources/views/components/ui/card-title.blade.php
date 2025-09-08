@php
    $classes = 'card-title ' . ($class ?? '');
@endphp

<h3 {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</h3>