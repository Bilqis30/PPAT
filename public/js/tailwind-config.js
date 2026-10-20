/**
 * WebArsipp - Tailwind Custom Configuration
 */
tailwind.config = {
    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'sans-serif'],
            },
            colors: {
                blood: {
                    50: '#fff1f1',
                    100: '#ffe1e1',
                    200: '#ffc7c7',
                    300: '#ffa4a4',
                    400: '#f87171',
                    500: '#dc2626',
                    600: '#b91c1c',
                    700: '#991b1b',
                    800: '#7f1d1d',
                    900: '#760604', // Blood Red (#760604)
                    950: '#450302',
                },
                darkgrey: {
                    400: '#6d6e70',
                    500: '#5a5b5c',
                    600: '#4B4C4D', // Dark Grey (#4B4C4D)
                    700: '#3a3b3c',
                    800: '#2b2c2d',
                    900: '#1c1d1e',
                    950: '#121314',
                },
                brand: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                    950: '#020617',
                }
            }
        }
    }
};
