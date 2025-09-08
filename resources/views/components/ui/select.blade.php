@php
    $classes = 'select ' . ($class ?? '');
@endphp

<select {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</select>