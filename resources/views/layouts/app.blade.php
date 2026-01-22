<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ByteLog') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/ByteLog/ByteLog-Icon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <style>[x-cloak]{ display:none !important; }</style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            @if (session('success'))
                <div
                    x-data="{ show: false }"
                    x-init="
                        const ping = () => {
                            try {
                                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                                const now = ctx.currentTime;
                                const gain = ctx.createGain();
                                gain.connect(ctx.destination);
                                const makeTone = (freq, start, duration, vol = 0.14) => {
                                    const osc = ctx.createOscillator();
                                    osc.type = 'sine';
                                    osc.frequency.value = freq;
                                    const g = ctx.createGain();
                                    g.gain.setValueAtTime(vol, now + start);
                                    g.gain.exponentialRampToValueAtTime(0.0001, now + start + duration);
                                    osc.connect(g);
                                    g.connect(gain);
                                    osc.start(now + start);
                                    osc.stop(now + start + duration + 0.08);
                                };
                                // Soft, professional two-tone chime
                                makeTone(620, 0, 0.16, 0.18);
                                makeTone(880, 0.05, 0.14, 0.16);
                            } catch (e) {}
                        };
                        setTimeout(() => { show = true; ping(); }, 50);
                        setTimeout(() => show = false, 3050);
                    "
                    class="fixed bottom-6 right-6 z-50"
                >
                    <div
                        x-show="show"
                        x-cloak
                        x-transition:enter="transform ease-out duration-300"
                        x-transition:enter-start="translate-y-4 translate-x-4 opacity-0"
                        x-transition:enter-end="translate-y-0 translate-x-0 opacity-100"
                        x-transition:leave="transform ease-in duration-200"
                        x-transition:leave-start="translate-y-0 translate-x-0 opacity-100"
                        x-transition:leave-end="translate-y-4 translate-x-4 opacity-0"
                        class="max-w-md w-full bg-[#F9FAFB] border border-gray-100 rounded-xl shadow-[0_2px_6px_rgba(0,0,0,0.06)] overflow-hidden"
                    >
                        <div class="px-5 py-4">
                            <p class="text-[16px] font-medium text-[#111827] leading-snug">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
