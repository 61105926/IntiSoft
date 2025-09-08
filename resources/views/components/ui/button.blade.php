@php
    $variant = $variant ?? 'default';
    $size = $size ?? 'md';
    
    $variantClasses = [
        'default' => 'btn-default',
        'secondary' => 'btn-secondary',
        'outline' => 'btn-outline',
        'ghost' => 'btn-ghost',
        'destructive' => 'btn-destructive',
    ];
    
    $sizeClasses = [
        'sm' => 'btn-sm',
        'md' => 'btn-md',
        'lg' => 'btn-lg',
        'icon' => 'btn-icon',
    ];
    
    $classes = 'btn ' . ($variantClasses[$variant] ?? 'btn-default') . ' ' . ($sizeClasses[$size] ?? 'btn-md') . ' ' . ($class ?? '');
@endphp

<button {{ $attributes->merge(['type' => 'button', 'class' => $classes]) }}>
    {{ $slot }}
</button>