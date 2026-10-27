<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>E-Tamu | DPRD Kota Bontang</title>
        
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
    <body class="bg-slate-50 antialiased selection:bg-blue-500 selection:text-white">
        <!-- Navbar -->
        <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">E-Tamu</h1>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Sekretariat DPRD Kota Bontang</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        @auth
                            <a href="{{ url('/admin') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">Dashboard Admin &rarr;</a>
                        @else
                            <a href="{{ url('/admin') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">Log In &rarr;</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section & Form -->
        <div class="relative min-h-screen pb-20 overflow-x-hidden">
            <!-- Background Decoration -->
            <div class="absolute inset-0 z-0 pointer-events-none">
                <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
                <div class="absolute top-40 -left-40 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 lg:mt-20">
                <div class="lg:grid lg:grid-cols-12 lg:gap-16 items-start">
                    
                    <!-- Left Copy -->
                    <div class="lg:col-span-5 pt-8 mb-12 lg:mb-0">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-sm font-semibold mb-6 border border-blue-100">
                            <span class="relative flex h-2 w-2">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                            </span>
                            Layanan Publik Digital
                        </div>
                        <h2 class="text-4xl lg:text-5xl font-extrabold text-slate-900 leading-tight mb-6 tracking-tight">
                            Buku Tamu <br/>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Digital</span>
                        </h2>
                        <p class="text-lg text-slate-600 leading-relaxed mb-8">
                            Selamat datang di aplikasi E-Tamu Sekretariat DPRD Kota Bontang. Silakan isi form disamping untuk mendaftarkan kunjungan Anda. Setelah disetujui, Anda akan mendapatkan QR Code untuk check-in.
                        </p>
                        
                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-10 h-10 bg-white rounded-lg shadow flex items-center justify-center text-blue-600 border border-slate-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-base font-semibold text-slate-900">1. Isi Form Pendaftaran</h4>
                                    <p class="text-sm text-slate-500 mt-1">Lengkapi data diri dan tujuan kunjungan Anda.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-10 h-10 bg-white rounded-lg shadow flex items-center justify-center text-blue-600 border border-slate-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-base font-semibold text-slate-900">2. Tunggu Persetujuan</h4>
                                    <p class="text-sm text-slate-500 mt-1">Admin kami akan meninjau dan menyetujui permohonan Anda.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-10 h-10 bg-white rounded-lg shadow flex items-center justify-center text-blue-600 border border-slate-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-base font-semibold text-slate-900">3. Scan QR Code</h4>
                                    <p class="text-sm text-slate-500 mt-1">Tunjukkan QR Code ke petugas keamanan saat kedatangan.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-12">
                            <livewire:check-status />
                        </div>
                    </div>

                    <!-- Right Form -->
                    <div class="lg:col-span-7">
                        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 p-6 sm:p-10">
                            <livewire:booking-form />
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        @filamentScripts
        @livewireScripts
    </body>
</html>
