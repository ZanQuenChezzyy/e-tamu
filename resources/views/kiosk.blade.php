<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Kiosk E-Tamu | DPRD Kota Bontang</title>
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        
        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @filamentStyles
        @livewireStyles
    </head>
    <body class="bg-slate-50 antialiased selection:bg-blue-500 selection:text-white min-h-screen flex flex-col justify-center py-10 relative overflow-x-hidden">
        <!-- Background Decoration -->
        <div class="absolute inset-0 z-0 pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
            <div class="absolute top-40 -left-40 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
            <div class="absolute bottom-0 right-1/4 w-[30rem] h-[30rem] bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
        </div>

        <div class="relative z-10 w-full max-w-4xl mx-auto px-4 sm:px-6">
            
            <!-- Branding -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-2xl shadow-xl shadow-blue-500/30 mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight">E-Tamu <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Layanan Mandiri</span></h1>
                <p class="text-sm font-semibold text-slate-500 uppercase tracking-widest mt-3">Sekretariat DPRD Kota Bontang</p>
            </div>

            <!-- Kiosk Form Component -->
            <div class="bg-white/80 backdrop-blur-md rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 p-8 sm:p-12 relative">
                <!-- Inner decoration -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-[100px] -z-10"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-indigo-50 rounded-tr-[100px] -z-10"></div>
                
                <livewire:kiosk-form />
            </div>

            <!-- Footer / Back to home -->
            <div class="mt-8 text-center">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-blue-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Halaman Utama
                </a>
            </div>

        </div>

        @filamentScripts
        @livewireScripts
    </body>
</html>
