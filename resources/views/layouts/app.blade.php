<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">

    <title>@yield('title', __('site.brand')) · {{ __('site.brand') }}</title>
    <meta name="description" content="{{ __('site.description') }}">

    <script>
        (() => {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col">

    <header
        class="sticky top-0 z-50 border-b border-slate-200 bg-white/95 shadow-sm backdrop-blur dark:border-slate-800 dark:bg-slate-900/95">
        <nav class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">

            <a href="{{ route('items.index') }}"
                class="flex shrink-0 items-center gap-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500">
                <span
                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-teal-600 text-2xl text-white shadow-sm">
                    <span aria-hidden="true">⌕</span>
                </span>
                <span>
                    <span class="block text-lg font-extrabold tracking-tight text-slate-900 dark:text-white">
                        {{ __('site.brand') }}
                    </span>
                    <span class="hidden text-xs text-slate-500 sm:block dark:text-slate-400">
                        {{ __('site.tagline') }}
                    </span>
                </span>
            </a>

            <div class="hidden items-center gap-1 lg:flex">
                <a href="{{ route('items.index') }}" @class([
                    'rounded-lg px-4 py-2 text-sm font-semibold transition',
                    'bg-teal-50 text-teal-700 dark:bg-teal-950 dark:text-teal-300' => request()->routeIs(
                        'items.*'),
                    'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' => !request()->routeIs(
                        'items.*'),
                ])>
                    {{ __('site.items') }}
                </a>

                <a href="{{ route('places.index') }}" @class([
                    'rounded-lg px-4 py-2 text-sm font-semibold transition',
                    'bg-teal-50 text-teal-700 dark:bg-teal-950 dark:text-teal-300' => request()->routeIs(
                        'places.*'),
                    'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' => !request()->routeIs(
                        'places.*'),
                ])>
                    {{ __('site.places') }}
                </a>

                <a href="{{ route('tags.index') }}" @class([
                    'rounded-lg px-4 py-2 text-sm font-semibold transition',
                    'bg-teal-50 text-teal-700 dark:bg-teal-950 dark:text-teal-300' => request()->routeIs(
                        'tags.*'),
                    'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' => !request()->routeIs(
                        'tags.*'),
                ])>
                    {{ __('site.tags') }}
                </a>

                <a href="{{ route('claims.create') }}" @class([
                    'rounded-lg px-4 py-2 text-sm font-semibold transition',
                    'bg-teal-50 text-teal-700 dark:bg-teal-950 dark:text-teal-300' => request()->routeIs(
                        'claims.*'),
                    'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' => !request()->routeIs(
                        'claims.*'),
                ])>{{ __('site.report_loss') }}</a>
                <a href="{{ route('statistics.index') }}" @class([
                    'rounded-lg px-4 py-2 text-sm font-semibold transition',
                    'bg-teal-50 text-teal-700 dark:bg-teal-950 dark:text-teal-300' => request()->routeIs(
                        'statistics.*'),
                    'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800' => !request()->routeIs(
                        'statistics.*'),
                ])>{{ __('site.statistics') }}</a>
            </div>

            <div class="flex items-center gap-2">
                <form action="{{ route('locale.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="locale" value="{{ app()->getLocale() === 'fr' ? 'en' : 'fr' }}">
                    <button type="submit"
                        aria-label="{{ app()->getLocale() === 'fr' ? __('site.switch_to_english') : __('site.switch_to_french') }}"
                        title="{{ app()->getLocale() === 'fr' ? __('site.switch_to_english') : __('site.switch_to_french') }}"
                        class="flex h-10 min-w-10 items-center justify-center rounded-lg border border-slate-200 px-2 text-sm font-bold hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-teal-500 dark:border-slate-700 dark:hover:bg-slate-800">
                        {{ app()->getLocale() === 'fr' ? 'EN' : 'FR' }}
                    </button>
                </form>

                <button type="button" id="theme-toggle" aria-label="{{ __('site.theme_toggle') }}"
                    title="{{ __('site.theme_toggle') }}"
                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-lg hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-teal-500 dark:border-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700">
                    <span id="theme-icon" aria-hidden="true">🌙</span>
                </button>

                <details class="relative lg:hidden">
                    <summary
                        class="flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-xl border border-slate-200 text-xl hover:bg-slate-100 dark:border-slate-700 dark:hover:bg-slate-800"
                        aria-label="{{ __('site.menu_open') }}">
                        <span aria-hidden="true">☰</span>
                    </summary>

                    <div
                        class="absolute right-0 top-14 z-50 w-60 rounded-2xl border border-slate-200 bg-white p-2 shadow-xl dark:border-slate-700 dark:bg-slate-900">
                        <a class="block rounded-xl px-4 py-3 font-medium hover:bg-slate-100 dark:hover:bg-slate-800"
                            href="{{ route('items.index') }}">{{ __('site.items') }}</a>
                        <a class="block rounded-xl px-4 py-3 font-medium hover:bg-slate-100 dark:hover:bg-slate-800"
                            href="{{ route('places.index') }}">{{ __('site.places') }}</a>
                        <a class="block rounded-xl px-4 py-3 font-medium hover:bg-slate-100 dark:hover:bg-slate-800"
                            href="{{ route('tags.index') }}">{{ __('site.tags') }}</a>
                        <a class="block rounded-xl px-4 py-3 font-medium hover:bg-slate-100 dark:hover:bg-slate-800"
                            href="{{ route('claims.create') }}">{{ __('site.report_loss') }}</a>
                        <a class="block rounded-xl px-4 py-3 font-medium hover:bg-slate-100 dark:hover:bg-slate-800"
                            href="{{ route('statistics.index') }}">{{ __('site.statistics') }}</a>
                    </div>
                </details>
            </div>
        </nav>
    </header>

    <main class="mx-auto min-h-0 w-full max-w-7xl flex-1 px-4 py-6 sm:px-6 sm:py-10 lg:px-8">
        @if (session('success'))
            <div role="status"
                class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/60 dark:text-emerald-300">
                <span aria-hidden="true">✓</span>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div role="alert"
                class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-800 dark:border-red-900 dark:bg-red-950/60 dark:text-red-300">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="mt-auto border-t border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
        <div
            class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-6 text-sm text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8 dark:text-slate-400">
            <p class="font-semibold text-slate-700 dark:text-slate-200">{{ __('site.brand') }}</p>
            <p>{{ __('site.footer_tagline') }}</p>
        </div>
    </footer>

</body>

</html>
