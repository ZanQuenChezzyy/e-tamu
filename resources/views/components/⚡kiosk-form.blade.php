<?php

use Livewire\Component;
use App\Models\Guest;
use App\Models\Visit;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Livewire\Attributes\Url;

new class extends Component
{
    public $nik = '';
    public $name = '';
    public $phone = '';
    public $agency_name = '';
    
    public $department_id = '';
    public $purpose = '';
    
    #[Url]
    public $room_id = '';
    
    public $departments = [];
    public bool $isSuccess = false;

    public function mount()
    {
        $this->departments = Department::orderBy('name')->get();
        if ($this->room_id) {
            $this->department_id = $this->room_id;
        }
    }

    public function rules()
    {
        return [
            'nik' => 'required|numeric|digits_between:16,16',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'agency_name' => 'nullable|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'purpose' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'nik.required' => 'NIK wajib diisi.',
            'nik.numeric' => 'NIK harus berupa angka.',
            'nik.digits_between' => 'NIK harus 16 digit.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor WhatsApp wajib diisi.',
            'department_id.required' => 'Tujuan (Fraksi/Bagian) wajib dipilih.',
            'purpose.required' => 'Keperluan kunjungan wajib diisi.',
        ];
    }

    public function submit()
    {
        $this->validate();

        DB::transaction(function () {
            $guest = Guest::firstOrCreate(
                ['nik' => $this->nik],
                [
                    'name' => $this->name,
                    'phone' => $this->phone,
                    'agency_name' => $this->agency_name,
                ]
            );

            // Update guest details if they changed
            if ($guest->name !== $this->name || $guest->phone !== $this->phone || $guest->agency_name !== $this->agency_name) {
                $guest->update([
                    'name' => $this->name,
                    'phone' => $this->phone ?? $guest->phone,
                    'agency_name' => $this->agency_name ?? $guest->agency_name,
                ]);
            }

            $now = Carbon::now();

            Visit::create([
                'guest_id' => $guest->id,
                'department_id' => $this->department_id,
                'purpose' => $this->purpose,
                'visit_date' => $now->format('Y-m-d'),
                'visit_time' => $now->format('H:i'),
                'status' => 'completed',
                'check_in_time' => $now,
            ]);
        });

        $this->isSuccess = true;
    }
    
    public function resetForm()
    {
        $this->reset(['nik', 'name', 'phone', 'agency_name', 'department_id', 'purpose']);
        $this->isSuccess = false;
    }
};
?>

<div>
    @if($isSuccess)
        <div class="relative overflow-hidden bg-white/60 backdrop-blur-xl border border-white/40 shadow-2xl rounded-3xl p-10 text-center transform transition-all duration-500 hover:scale-[1.02]">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-gradient-to-br from-green-300 to-emerald-400 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-gradient-to-tr from-teal-300 to-green-400 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob animation-delay-2000"></div>
            
            <div class="relative z-10 flex flex-col items-center">
                <div class="inline-flex items-center justify-center w-28 h-28 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 shadow-xl mb-6 ring-4 ring-green-100">
                    <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-green-700 to-emerald-900 mb-4 tracking-tight">Check-In Berhasil!</h3>
                <p class="text-xl text-slate-600 mb-8 max-w-lg mx-auto leading-relaxed">Terima kasih telah mengisi buku tamu digital. Kehadiran Anda telah tercatat. Silakan menuju ke ruangan tujuan Anda.</p>
                
                <button wire:click="resetForm" class="px-10 py-4 text-xl bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold rounded-2xl hover:from-emerald-600 hover:to-green-700 transition-all shadow-lg hover:shadow-green-500/30 transform hover:-translate-y-1 active:translate-y-0">
                    Selesai
                </button>
            </div>
        </div>
    @else
        <form wire:submit="submit" class="space-y-8 relative">
            
            <!-- Kiosk Header Info -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Formulir Kunjungan Tamu</h2>
                <p class="text-lg text-slate-500 mt-2">Isi form di bawah ini untuk mencatat kunjungan Anda secara langsung.</p>
            </div>

            <!-- Informasi Data Diri -->
            <div class="bg-slate-50/50 border border-slate-100 rounded-3xl p-8 shadow-sm">
                <div class="mb-6 border-b border-slate-200 pb-4">
                    <h3 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                        Identitas Tamu
                    </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-base font-semibold text-slate-700 mb-2">NIK / Nomor Identitas *</label>
                        <input type="text" wire:model="nik" class="w-full rounded-2xl border-slate-200 bg-white px-5 py-4 text-lg focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors @error('nik') border-red-500 @enderror" placeholder="Masukkan 16 digit NIK">
                        @error('nik') <span class="text-sm text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-base font-semibold text-slate-700 mb-2">Nama Lengkap *</label>
                        <input type="text" wire:model="name" class="w-full rounded-2xl border-slate-200 bg-white px-5 py-4 text-lg focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors @error('name') border-red-500 @enderror" placeholder="Sesuai KTP">
                        @error('name') <span class="text-sm text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-base font-semibold text-slate-700 mb-2">Nomor WhatsApp *</label>
                        <input type="tel" wire:model="phone" class="w-full rounded-2xl border-slate-200 bg-white px-5 py-4 text-lg focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors @error('phone') border-red-500 @enderror" placeholder="08xxxxxxxxxx">
                        @error('phone') <span class="text-sm text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-base font-semibold text-slate-700 mb-2">Instansi / Perusahaan</label>
                        <input type="text" wire:model="agency_name" class="w-full rounded-2xl border-slate-200 bg-white px-5 py-4 text-lg focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors @error('agency_name') border-red-500 @enderror" placeholder="Kosongkan jika pribadi">
                        @error('agency_name') <span class="text-sm text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Detail Kunjungan -->
            <div class="bg-slate-50/50 border border-slate-100 rounded-3xl p-8 shadow-sm">
                <div class="mb-6 border-b border-slate-200 pb-4">
                    <h3 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Tujuan Kunjungan
                    </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-base font-semibold text-slate-700 mb-2">Tujuan (Ruangan Rapat) *</label>
                        @if($room_id)
                            @php 
                                $selectedRoom = $departments->firstWhere('id', $room_id);
                            @endphp
                            <div class="w-full rounded-2xl border-slate-200 bg-gray-100 px-5 py-4 text-lg font-bold text-gray-700 shadow-inner flex items-center gap-3">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                {{ $selectedRoom ? $selectedRoom->name : 'Ruangan Tidak Valid' }}
                            </div>
                            <input type="hidden" wire:model="department_id">
                        @else
                            <select wire:model="department_id" class="w-full rounded-2xl border-slate-200 bg-white px-5 py-4 text-lg focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors @error('department_id') border-red-500 @enderror">
                                <option value="">-- Pilih Tujuan --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        @endif
                        @error('department_id') <span class="text-sm text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-base font-semibold text-slate-700 mb-2">Keperluan *</label>
                        <input type="text" wire:model="purpose" class="w-full rounded-2xl border-slate-200 bg-white px-5 py-4 text-lg focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors @error('purpose') border-red-500 @enderror" placeholder="Cth: Audiensi / Rapat">
                        @error('purpose') <span class="text-sm text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full relative group overflow-hidden px-8 py-5 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold text-xl rounded-2xl transition-all duration-300 shadow-xl hover:shadow-blue-500/40 transform hover:-translate-y-1">
                    <span class="absolute right-0 w-8 h-32 -mt-12 transition-all duration-1000 transform translate-x-12 bg-white opacity-10 rotate-12 group-hover:-translate-x-96 ease"></span>
                    <span class="relative flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        Check In Sekarang
                        <svg wire:loading class="animate-spin w-5 h-5 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </span>
                </button>
            </div>
        </form>
    @endif
</div>