<x-filament-panels::page>
    <style>
        /* Membersihkan UI bawaan html5-qrcode */
        #reader { border: none !important; box-shadow: none !important; }
        #reader img { display: none !important; }
        #reader__dashboard_section_swaplink { display: none !important; }
        #reader__dashboard_section_csr span { display: none !important; }
        #reader button {
            background-color: #0d9488 !important; /* Teal 600 */
            color: white !important;
            border-radius: 9999px !important;
            padding: 0.5rem 1.5rem !important;
            border: none !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            margin-top: 1rem !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 6px -1px rgba(13, 148, 136, 0.4) !important;
        }
        #reader button:hover {
            background-color: #0f766e !important;
            transform: translateY(-2px) !important;
        }
        #reader select {
            padding: 0.625rem 1rem !important;
            border-radius: 9999px !important;
            border: 1px solid #e5e7eb !important;
            margin-bottom: 1rem !important;
            width: 100% !important;
            max-width: 350px !important;
            outline: none !important;
            font-size: 0.875rem !important;
            color: #374151 !important;
            background-color: #f9fafb !important;
            transition: all 0.2s !important;
        }
        #reader select:focus {
            border-color: #0d9488 !important;
            box-shadow: 0 0 0 2px rgba(13, 148, 136, 0.2) !important;
        }
        .dark #reader select {
            background-color: #1f2937 !important;
            border-color: #374151 !important;
            color: #f3f4f6 !important;
        }
        #reader__scan_region {
            border-radius: 1rem !important;
            overflow: hidden !important;
            border: 2px dashed #9ca3af !important;
        }
        .dark #reader__scan_region {
            border-color: #4b5563 !important;
        }
    </style>

    <div 
        x-data="{
            manualToken: '',
            scanner: null,
            init() {
                if (typeof Html5QrcodeScanner === 'undefined') {
                    let script = document.createElement('script');
                    script.src = 'https://unpkg.com/html5-qrcode';
                    script.onload = () => this.startScanner();
                    document.head.appendChild(script);
                } else {
                    this.startScanner();
                }
            },
            startScanner() {
                this.scanner = new Html5QrcodeScanner(
                    'reader', 
                    { fps: 15, qrbox: { width: 250, height: 250 } }, 
                    false
                );
                this.scanner.render(this.onScanSuccess.bind(this), this.onScanFailure.bind(this));
            },
            onScanSuccess(decodedText, decodedResult) {
                if(this.scanner) {
                    this.scanner.clear();
                }
                
                $wire.processQrCode(decodedText).then(() => {
                    setTimeout(() => {
                        this.startScanner();
                    }, 3000);
                });
            },
            onScanFailure(error) {
                // silent
            },
            submitManual() {
                if(this.manualToken.trim() !== '') {
                    $wire.processQrCode(this.manualToken).then(() => {
                        this.manualToken = '';
                    });
                }
            }
        }"
    >
        <!-- Header Section -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl">
                Check-in Tamu
            </h2>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Pindai QR Code yang dimiliki pengunjung atau gunakan scanner USB untuk melakukan check-in otomatis.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            
            <!-- Left Side: USB Scanner Input -->
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-md">
                <div class="p-6 sm:p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                              <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Scanner Fisik (Barcode Gun)</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Cara tercepat dan direkomendasikan</p>
                        </div>
                    </div>
                    
                    <form x-on:submit.prevent="submitManual" class="space-y-4">
                        <div>
                            <label class="sr-only">Input QR Token</label>
                            <div class="relative rounded-xl shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm2 2V5h1v1H5zM3 13a1 1 0 011-1h3a1 1 0 011 1v3a1 1 0 01-1 1H4a1 1 0 01-1-1v-3zm2 2v-1h1v1H5zM13 3a1 1 0 00-1 1v3a1 1 0 001 1h3a1 1 0 001-1V4a1 1 0 00-1-1h-3zm1 2v1h1V5h-1z" clip-rule="evenodd" />
                                        <path d="M13 13h1v1h-1v-1zM15 11h1v1h-1v-1zM11 15h1v1h-1v-1zM11 11h1v1h-1v-1zM15 15h1v1h-1v-1zM13 17h1v1h-1v-1zM17 13h1v1h-1v-1zM17 17h1v1h-1v-1z" />
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    x-model="manualToken" 
                                    class="block w-full rounded-xl border-0 py-3.5 pl-11 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-white dark:ring-gray-700 transition-all" 
                                    placeholder="Arahkan kursor kesini, lalu klik scanner..."
                                    autofocus
                                >
                            </div>
                        </div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                            Proses Data Tamu
                        </button>
                    </form>

                    <div class="mt-6 p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-900/50">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    <strong>Tips:</strong> Pastikan bahasa keyboard Scanner dalam kondisi EN/US dan bukan CapsLock agar kode terbaca dengan benar.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Camera Scanner -->
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-md">
                <div class="p-6 sm:p-8 flex flex-col items-center">
                    <div class="flex items-center gap-4 mb-6 w-full">
                        <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-full bg-teal-100 text-teal-600 dark:bg-teal-900/30 dark:text-teal-400">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Kamera Perangkat</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Pindai dengan webcam / kamera hp</p>
                        </div>
                    </div>
                    
                    <div id="reader" class="w-full max-w-sm overflow-hidden bg-gray-50 dark:bg-gray-800/50 rounded-xl"></div>
                    
                    <div class="mt-4 text-center">
                        <span class="inline-flex items-center rounded-full bg-yellow-50 px-3 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20 dark:bg-yellow-900/30 dark:text-yellow-500 dark:ring-yellow-500/20">
                            Fitur ini mengharuskan akses HTTPS
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
