@props(['name', 'size' => 24, 'class' => '', 'type' => 'outline'])

@php
    // Đường dẫn tới file SVG trong thư mục resources/icons/tabler
    $path = resource_path("icons/tabler/{$type}/{$name}.svg");
    $svg = file_exists($path) ? file_get_contents($path) : null;
    if ($svg) {
        // Xóa width và height cũ để tránh xung đột
        $svg = preg_replace('/(<svg\b[^>]*?)\s*(width|height)="[^"]*"/', '$1', $svg);
        // Thêm width, height mới và ép scale bằng inline style
        $svg = preg_replace('/<svg\b/', '<svg width="'.$size.'" height="'.$size.'" style="width: '.$size.'px; height: '.$size.'px; max-width: 100%; max-height: 100%;" ', $svg, 1);
    }
@endphp

@if($svg)
    <span {!! $attributes->merge(['class' => $class]) !!}>
        {!! $svg !!}
    </span>
@else
    <span class="text-red-500">[Icon "{{$name}}" - ({{ $type }}) không tồn tại]</span>
@endif
