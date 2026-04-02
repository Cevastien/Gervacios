import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                forum: ['Forum', 'serif'],
                satoshi: ['Satoshi', 'sans-serif'],
            },
            colors: {
                cream: '#EFE7D2',
                dark: '#0A0B0A',
                /** Staff / admin dashboard (Café Gervacios) */
                brand: {
                    cream: '#FAF3E0',
                    brown: '#2C1A0E',
                    'brown-hover': '#1a0f08',
                },
                /** Admin / staff console — light UI; use `panel.brand` sparingly for accents */
                /**
                 * SaaS admin / staff — Priority Verification–aligned palette:
                 * primary = header / CTAs / sidebar / chart ink on light (#0F172A)
                 * chrome = single 1px rail between sidebar & canvas (#2D3748)
                 */
                panel: {
                    brand: '#0f172a',
                    primary: '#0f172a',
                    'primary-hover': '#1e293b',
                    /** Sidebar ↔ main separator only — avoids “boxed” double borders */
                    chrome: '#2d3748',
                    /** Data series on white cards */
                    chart: '#0f172a',
                    'chart-muted': '#1e293b',
                    on: '#f8fafc',
                    'on-bright': '#ffffff',
                    /** Softer than pure white — easier on eyes for long sessions */
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
    plugins: [],
};
