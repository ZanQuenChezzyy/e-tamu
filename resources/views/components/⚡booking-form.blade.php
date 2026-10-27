<?php

use Livewire\Component;
use App\Models\Guest;
use App\Models\Visit;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

new class extends Component
{
    public $nik = '';
    public $name = '';
    public $phone = '';
    public $agency_name = '';
    
    public $department_id = '';
    public $purpose = '';
    public $visit_date = '';
    public $visit_time = '';
    
    public $departments = [];
    public bool $isSuccess = false;

    public function mount()
    {
        $this->departments = Department::orderBy('name')->get();
        $this->visit_date = Carbon::now()->format('Y-m-d');
        $this->visit_time = Carbon::now()->format('H:i');
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
            'visit_date' => 'required|date',
            'visit_time' => 'required',
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

            Visit::create([
                'guest_id' => $guest->id,
                'department_id' => $this->department_id,
                'purpose' => $this->purpose,
                'visit_date' => $this->visit_date,
                'visit_time' => $this->visit_time,
                'status' => 'pending',
            ]);
        });

        $this->isSuccess = true;
    }
    
    public function resetForm()
    {
        $this->reset(['nik', 'name', 'phone', 'agency_name', 'department_id', 'purpose']);
        $this->visit_date = Carbon::now()->format('Y-m-d');
        $this->visit_time = Carbon::now()->format('H:i');
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
                <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 shadow-xl mb-6 ring-4 ring-green-100">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-3xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-green-700 to-emerald-900 mb-4 tracking-tight">Permohonan Berhasil Dikirim!</h3>
                <p class="text-lg text-slate-600 mb-8 max-w-md mx-auto leading-relaxed">Terima kasih telah mendaftar. Permohonan kunjungan Anda sedang diproses oleh admin kami. Silakan tunggu informasi dan kode QR yang akan diberikan selanjutnya.</p>
                <button wire:click="resetForm" class="px-8 py-3.5 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold rounded-xl hover:from-emerald-600 hover:to-green-700 transition-all shadow-lg hover:shadow-green-500/30 transform hover:-translate-y-1 active:translate-y-0">
                    Buat Permohonan Baru
                </button>
            </div>
        </div>
    @else
        <form wire:submit="submit" class="space-y-8 relative">
            
            <!-- Informasi Data Diri -->
            <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-slate-900">Informasi Data Diri</h3>
                    <p class="text-sm text-slate-500">Silakan isi data diri Anda dengan lengkap dan benar.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">NIK / Nomor Identitas *</label>
                        <input type="text" wire:model="nik" class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors @error('nik') border-red-500 @enderror" placeholder="Masukkan 16 digit NIK">
                        @error('nik') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap *</label>
                        <input type="text" wire:model="name" class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors @error('name') border-red-500 @enderror" placeholder="Sesuai KTP">
                        @error('name') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor WhatsApp *</label>
                        <input type="tel" wire:model="phone" class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors @error('phone') border-red-500 @enderror" placeholder="08xxxxxxxxxx">
                        @error('phone') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Instansi / Perusahaan</label>
                        <input type="text" wire:model="agency_name" class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors @error('agency_name') border-red-500 @enderror" placeholder="Kosongkan jika pribadi">
                        @error('agency_name') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Detail Kunjungan -->
            <div class="bg-slate-50/50 border border-slate-100 rounded-2xl p-6">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-slate-900">Detail Kunjungan</h3>
                    <p class="text-sm text-slate-500">Informasi terkait tujuan dan waktu kunjungan Anda.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tujuan (Fraksi / Bagian) *</label>
                        <select wire:model="department_id" class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors @error('department_id') border-red-500 @enderror">
                            <option value="">-- Pilih Tujuan --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Keperluan *</label>
                        <input type="text" wire:model="purpose" class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors @error('purpose') border-red-500 @enderror" placeholder="Cth: Audiensi / Rapat">
                        @error('purpose') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Kunjungan *</label>
                        <input type="date" wire:model="visit_date" class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors @error('visit_date') border-red-500 @enderror">
                        @error('visit_date') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Waktu Kunjungan *</label>
                        <input type="time" wire:model="visit_time" class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm transition-colors @error('visit_time') border-red-500 @enderror">
                        @error('visit_time') <span class="text-xs text-red-500 mt-1 block font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full relative group overflow-hidden px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold text-lg rounded-2xl transition-all duration-300 shadow-xl hover:shadow-blue-500/40 transform hover:-translate-y-1">
                    <span class="absolute right-0 w-8 h-32 -mt-12 transition-all duration-1000 transform translate-x-12 bg-white opacity-10 rotate-12 group-hover:-translate-x-96 ease"></span>
                    <span class="relative flex items-center justify-center gap-2">
                        Kirim Permohonan Kunjungan
                        <svg wire:loading.remove class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        <svg wire:loading class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </span>
                </button>
            </div>
        </form>
    @endif
</div>