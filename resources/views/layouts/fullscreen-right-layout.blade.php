<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'GriyaSpace' }} | GriyaSpace</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Theme Store -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() {
                    const savedTheme = localStorage.getItem('theme');
                    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' :
                        'light';
                    this.theme = savedTheme || systemTheme;
                    this.updateTheme();
                },
                theme: 'light',
                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    this.updateTheme();
                },
                updateTheme() {
                    const html = document.documentElement;
                    const body = document.body;
                    if (this.theme === 'dark') {
                        html.classList.add('dark');
                        body.classList.add('dark', 'bg-gray-900');
                    } else {
                        html.classList.remove('dark');
                        body.classList.remove('dark', 'bg-gray-900');
                    }
                }
            });
        });
    </script>

    <!-- Apply dark mode immediately to prevent flash -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = savedTheme || systemTheme;
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark', 'bg-gray-900');
            } else {
                document.documentElement.classList.remove('dark');
                document.body.classList.remove('dark', 'bg-gray-900');
            }
        })();
    </script>
</head>

<body x-data="{ 'loaded': true }" class="bg-white dark:bg-gray-900 min-h-screen">

    <div class="relative z-1 bg-white p-4 sm:p-0 dark:bg-gray-900 min-h-screen">
        <div class="relative flex min-h-screen w-full flex-col justify-center sm:p-0 lg:flex-row dark:bg-gray-900">
            
            <!-- Left Side: Brand Banner & Graphic (Desktop) -->
            <div class="bg-brand-950 relative hidden min-h-screen w-full items-center lg:grid lg:w-1/2 dark:bg-white/5 overflow-hidden">
                <div class="z-1 flex items-center justify-center p-8">
                    <x-common.common-grid-shape />
                    <div class="flex max-w-xs flex-col items-center">
                        <a href="{{ url('/') }}" class="mb-4 block">
                            <img class="dark:hidden h-10 w-auto" src="/images/logo/logo.svg" alt="GriyaSpace" />
                            <img class="hidden dark:block h-10 w-auto" src="/images/logo/logo-dark.svg" alt="GriyaSpace" />
                        </a>
                        <p class="text-center text-gray-400 dark:text-white/60 text-sm">
                            {{ $bannerText ?? 'GriyaSpace - Sistem Peminjaman Ruangan' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Content Area -->
            <div class="flex w-full flex-1 flex-col justify-center min-h-screen lg:w-1/2 overflow-y-auto">
                <div class="mx-auto flex w-full max-w-lg flex-1 flex-col justify-center p-6 sm:p-8 lg:p-12">
                    <!-- Mobile Logo (Screens smaller than lg) -->
                    <div class="mb-8 flex justify-center lg:hidden">
                        <a href="{{ url('/') }}">
                            <img class="dark:hidden h-9 w-auto" src="/images/logo/logo.svg" alt="GriyaSpace" />
                            <img class="hidden dark:block h-9 w-auto" src="/images/logo/logo-dark.svg" alt="GriyaSpace" />
                        </a>
                    </div>

                    @yield('content')
                </div>
            </div>

            <!-- Theme Toggler (Floating bottom right) -->
            <div class="fixed right-6 bottom-6 z-50">
                <button
                    class="bg-brand-500 hover:bg-brand-600 inline-flex size-14 items-center justify-center rounded-full text-white shadow-lg transition-colors focus:outline-none"
                    @click.prevent="$store.theme.toggle()"
                    aria-label="Toggle Dark Mode">
                    <i class="fa-solid fa-sun hidden dark:block text-xl"></i>
                    <i class="fa-solid fa-moon dark:hidden text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Global Toast Notification Container -->
    <x-common.toast />

</body>

@stack('scripts')

</html>
