<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Masuk | SIM-LiBas RT 15 Pematang Sulur')</title>
    <meta name="description" content="Portal Terpadu Layanan Warga Kampung Digital Pinang Masak Lestari, RT 15 Pematang Sulur, Kota Jambi">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-900': '#0B2F6B',
                        'primary-700': '#0B3A85',
                        'primary-500': '#1C7ED6',
                        'accent-cyan': '#19A7E8',
                        'green-700': '#1B7A3A',
                        'green-500': '#6DB33F',
                        'gold-500': '#F2A900',
                        'gold-700': '#C98A00',
                        'bg-soft': '#F5F8FC',
                        'surface': '#FFFFFF',
                        'border-soft': '#DDE5F0',
                        'text-main': '#0F1F3D',
                        'text-muted': '#5B6B82',
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    @stack('styles')
</head>
<body class="bg-white text-text-main font-sans antialiased h-full min-h-screen min-h-[100dvh] m-0 p-0 overflow-x-hidden">
    @yield('content')
    @stack('scripts')
</body>
</html>
