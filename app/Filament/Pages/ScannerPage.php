<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Visit;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ScannerPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';
    protected static ?string $navigationLabel = 'QR Scanner';
    protected static ?string $title = 'Scan QR Code Tamu';
    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.pages.scanner-page';

    public static function canAccess(): bool
    {
        return Auth::user()->hasRole(['Administrator', 'Security']);
    }

    public function processQrCode(string $token)
    {
        $visit = Visit::where('qr_code_token', $token)->first();

        if (!$visit) {
            Notification::make()
                ->title('QR Code Tidak Valid')
                ->body('Tamu tidak ditemukan dengan QR Code ini.')
                ->danger()
                ->send();
            return;
        }

        if ($visit->status === 'completed') {
            if ($visit->check_out_time) {
                Notification::make()
                    ->title('Sudah Check-Out')
                    ->body('Tamu ini sudah melakukan check-out sebelumnya.')
                    ->warning()
                    ->send();
                return;
            }

            // Perform Check-Out
            $visit->update(['check_out_time' => now()]);

            Notification::make()
                ->title('Check-Out Berhasil')
                ->body("Tamu {$visit->guest->name} berhasil check-out.")
                ->success()
                ->send();
            return;
        }

        if ($visit->status === 'pending') {
            Notification::make()
                ->title('Kunjungan Belum Disetujui')
                ->body('Status kunjungan ini masih pending dan belum disetujui Admin.')
                ->danger()
                ->send();
            return;
        }

        if ($visit->status === 'rejected') {
            Notification::make()
                ->title('Kunjungan Ditolak')
                ->body('Kunjungan ini ditolak oleh Admin.')
                ->danger()
                ->send();
            return;
        }

        // If status is approved, perform Check-In
        $visit->update([
            'status' => 'completed',
            'check_in_time' => now(),
        ]);

        Notification::make()
            ->title('Check-In Berhasil')
            ->body("Tamu {$visit->guest->name} berhasil check-in.")
            ->success()
            ->send();
    }
}
