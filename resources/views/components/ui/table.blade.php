@php
    $classes = 'table ' . ($class ?? '');
@endphp

<div class="table-container">
    <table {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </table>
</div>