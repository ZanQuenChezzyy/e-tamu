<?php

use Livewire\Component;
use App\Models\Guest;
use App\Models\Visit;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

new class extends Component
{
    public $searchNik = '';
    public $searchResult = null;
    public $errorMessage = '';

    public function rules()
    {
        return [
            'searchNik' => 'required|numeric'
        ];
    }
    
    public function messages()
    {
        return [
            'searchNik.required' => 'Masukkan NIK Anda untuk mengecek status.',
            'searchNik.numeric' => 'NIK harus berupa angka.',
        ];
    }

    public function checkStatus()
    {
        $this->validate();

        $this->errorMessage = '';
        $this->searchResult = null;

        $guest = Guest::where('nik', $this->searchNik)->first();

        if (!$guest) {
            $this->errorMessage = 'Data tamu tidak ditemukan. Pastikan NIK yang Anda masukkan benar.';
            return;
        }

        $latestVisit = Visit::with('department')->where('guest_id', $guest->id)->latest()->first();

        if (!$latestVisit) {
            $this->errorMessage = 'Anda belum memiliki riwayat kunjungan.';
            return;
        }

        $this->searchResult = $latestVisit;
    }
    
    public function getQrCodeProperty()
    {
        if ($this->searchResult && $this->searchResult->status === 'approved' && $this->searchResult->qr_code_token) {
            return (string) QrCode::size(200)->margin(2)->generate($this->searchResult->qr_code_token);
        }
        return null;
    }
};
?>

<div class="bg-white/60 backdrop-blur-xl border border-white/40 shadow-xl rounded-3xl p-6 sm:p-10 relative overflow-hidden">
    <!-- Decorative blobs -->
    <div class="absolute -top-10 -right-10 w-40 h-40 bg-gradient-to-br from-blue-300 to-indigo-400 rounded-full mix-blend-multiply filter blur-2xl opacity-50 animate-blob"></div>
    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-gradient-to-tr from-purple-300 to-pink-400 rounded-full mix-blend-multiply filter blur-2xl opacity-50 animate-blob animation-delay-2000"></div>
    
    <div class="relative z-10">
        <div class="mb-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 shadow-lg mb-4 text-white">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Cek Status & Unduh QR</h2>
            <p class="text-sm text-gray-500 mt-2 max-w-sm mx-auto">Masukkan NIK yang telah Anda daftarkan sebelumnya untuk melihat status kunjungan dan QR Code Anda.</p>
        </div>

        <form wire:submit="checkStatus" class="flex gap-3 max-w-md mx-auto mb-8">
            <div class="flex-grow relative">
                <input type="text" wire:model="searchNik" class="w-full rounded-xl border-gray-300 bg-white/80 px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-all" placeholder="Masukkan 16 digit NIK...">
                @error('searchNik') <span class="absolute -bottom-5 left-0 text-xs text-red-500 font-medium">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-bold transition-all shadow-md hover:shadow-blue-500/30 flex-shrink-0">
                <span wire:loading.remove>Cari</span>
                <svg wire:loading class="animate-spin w-5 h-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </button>
        </form>

        @if($errorMessage)
            <div class="max-w-md mx-auto p-4 bg-red-50 border border-red-200 rounded-xl text-center">
                <p class="text-sm font-medium text-red-600">{{ $errorMessage }}</p>
            </div>
        @endif

        @if($searchResult)
            <div class="max-w-md mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-6 transition-all duration-500">
                <div class="p-6">
                    <h3 class="font-bold text-lg text-gray-900 mb-1">Detail Kunjungan</h3>
                    <p class="text-xs text-gray-500 mb-4">Didaftarkan pada: {{ $searchResult->created_at->format('d M Y, H:i') }}</p>
                    
                    <div class="space-y-3 mb-6 text-sm">
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-500">Tujuan:</span>
                            <span class="font-semibold text-gray-900 text-right">{{ $searchResult->department->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-500">Waktu:</span>
                            <span class="font-semibold text-gray-900 text-right">{{ \Carbon\Carbon::parse($searchResult->visit_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($searchResult->visit_time)->format('H:i') }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-500">Keperluan:</span>
                            <span class="font-semibold text-gray-900 text-right">{{ $searchResult->purpose }}</span>
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <span class="text-gray-500">Status:</span>
                            @if($searchResult->status === 'pending')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold uppercase tracking-wider">Menunggu</span>
                            @elseif($searchResult->status === 'approved')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold uppercase tracking-wider">Disetujui</span>
                            @elseif($searchResult->status === 'rejected')
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold uppercase tracking-wider">Ditolak</span>
                            @elseif($searchResult->status === 'completed')
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold uppercase tracking-wider">Selesai (Check-in)</span>
                            @endif
                        </div>
                    </div>

                    @if($searchResult->status === 'approved' && $this->qrCode)
                        <div class="bg-gray-50 p-6 rounded-xl flex flex-col items-center justify-center border border-gray-200">
                            <p class="text-xs text-gray-500 mb-3 text-center uppercase tracking-wide font-bold">QR Code Kunjungan Anda</p>
                            <div class="bg-white p-2 rounded-lg shadow-sm">
                                {!! $this->qrCode !!}
                            </div>
                            <p class="text-xs text-gray-500 mt-4 text-center">Tunjukkan QR Code ini kepada satpam/resepsionis saat Anda tiba di lokasi.</p>
                        </div>
                    @elseif($searchResult->status === 'pending')
                        <div class="bg-yellow-50 p-4 rounded-xl flex items-start gap-3 border border-yellow-200">
                            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <p class="text-sm text-yellow-800">Permohonan Anda masih dalam tahap peninjauan. Silakan cek kembali secara berkala, atau tunggu arahan selanjutnya.</p>
                        </div>
                    @elseif($searchResult->status === 'rejected')
                        <div class="bg-red-50 p-4 rounded-xl flex items-start gap-3 border border-red-200">
                            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <p class="text-sm text-red-800">Mohon maaf, permohonan kunjungan Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>