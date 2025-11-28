@if($enabled)
    @php
        $types = [
            'info' => [
                'bg' => 'linear-gradient(to right, #eff6ff, #bae6fd)',
                'border' => '#3b82f6',
                'text' => '#1d4ed8',
                'icon' => 'heroicon-o-information-circle',
            ],
            'success' => [
                'bg' => 'linear-gradient(to right, #dcfce7, #bbf7d0)',
                'border' => '#22c55e',
                'text' => '#15803d',
                'icon' => 'heroicon-o-check-circle',
            ],
            'warning' => [
                'bg' => 'linear-gradient(to right, #fef9c3, #fde68a)',
                'border' => '#f59e0b',
                'text' => '#b45309',
                'icon' => 'heroicon-o-exclamation-circle',
            ],
            'danger' => [
                'bg' => 'linear-gradient(to right, #fef2f2, #fecaca)',
                'border' => '#dc2626',
                'text' => '#b91c1c',
                'icon' => 'heroicon-o-exclamation-circle',
            ],
        ];
        $style = $types[$type] ?? $types['info'];
    @endphp

    <div style="position: sticky; top: 0; z-index: 50; width: 100%; background: {{ $style['bg'] }}; border-bottom: 2px solid {{ $style['border'] }}; padding: 16px 24px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
        <div style="max-width: 1280px; margin: 0 auto;">
            <div style="display: flex; align-items: center; justify-content: center; gap: 12px; flex-wrap: wrap;">
                @svg($style['icon'], 'w-6 h-6', [ 'style' => "width: 24px !important; height: 24px !important; color: {$style['text']}; flex-shrink: 0;" ])
                <span style="font-size: 14px; font-weight: 600; color: {{ $style['text'] }}; letter-spacing: 0.025em;">
                    {{ $text }}
                </span>
            </div>
        </div>
    </div>
@endif
