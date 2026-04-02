{{-- Tailwind Play CDN: runtime JIT (all utilities). Theme merges your palette + existing Café tokens. --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#22ccb2',
                    'primary-dark': '#1bab96',
                    secondary: '#2c3e50',
                    accent: '#f39c12',
                    neutral: '#f5f7fa',
                    'neutral-dark': '#e3e8ef',
                    cream: '#EFE7D2',
                    dark: '#0A0B0A',
                    brand: {
                        cream: '#FAF3E0',
                        brown: '#2C1A0E',
                        'brown-hover': '#1a0f08',
                    },
                    /** SaaS admin / staff — slate-900 primary, gray-700 chrome rail */
                    panel: {
                        brand: '#0f172a',
                        primary: '#0f172a',
                        'primary-hover': '#1e293b',
                        chrome: '#2d3748',
                        chart: '#0f172a',
                        'chart-muted': '#1e293b',
                        on: '#f8fafc',
                        'on-bright': '#ffffff',
                        canvas: '#e2e8f0',
                        surface: '#f1f5f9',
                        heading: '#0f172a',
                        muted: '#64748b',
                        stroke: '#d8dee8',
                        action: '#0f172a',
                        'action-hover': '#1e293b',
                    },
                    'muted-bg': 'rgba(24, 24, 24, 0.5)',
                    'border-subtle': 'rgba(239, 231, 210, 0.15)',
                },
                fontFamily: {
                    sans: [
                        'Inter',
                        'system-ui',
                        '-apple-system',
                        'BlinkMacSystemFont',
                        'Segoe UI',
                        'Helvetica Neue',
                        'Arial',
                        'sans-serif',
                    ],
                    inter: ['Inter', 'sans-serif'],
                    forum: ['Forum', 'serif'],
                    satoshi: ['Satoshi', 'sans-serif'],
                },
                spacing: {
                    xs: '4px',
                    sm: '8px',
                    md: '16px',
                    lg: '24px',
                    xl: '32px',
                    '2xl': '48px',
                    '3xl': '64px',
                },
                borderRadius: {
                    card: '16px',
                    input: '12px',
                    btn: '12px',
                },
                fontSize: {
                    label: ['11px', { lineHeight: '16px', letterSpacing: '0.08em' }],
                    body: ['14px', { lineHeight: '22px' }],
                    'body-lg': ['16px', { lineHeight: '24px' }],
                    heading: ['20px', { lineHeight: '28px', fontWeight: '600' }],
                    title: ['28px', { lineHeight: '36px', fontWeight: '700' }],
                },
                boxShadow: {
                    card: '0 2px 16px rgba(0,0,0,0.25)',
                },
            },
        },
    };
</script>
