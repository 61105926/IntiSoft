@php
    $classes = 'input ' . ($class ?? '');
@endphp

<input {{ $attributes->merge(['class' => $classes]) }} />