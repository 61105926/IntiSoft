@php
    $variant = $variant ?? 'default';
    
    $variantClasses = [
        'default' => 'badge-default',
        'secondary' => 'badge-secondary',
        'outline' => 'badge-outline',
        'destructive' => 'badge-destructive',
    ];
    
    $classes = 'badge ' . ($variantClasses[$variant] ?? 'badge-default') . ' ' . ($class ?? '');
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>