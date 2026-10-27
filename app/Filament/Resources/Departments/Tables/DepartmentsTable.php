<?php

namespace App\Filament\Resources\Departments\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DepartmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Bagian / Fraksi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn(\App\Models\Department $record): string => str($record->description)->limit(50))
                    ->icon('heroicon-o-building-office-2'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('qr_code')
                    ->label('QR Kiosk')
                    ->icon('heroicon-o-qr-code')
                    ->color('success')
                    ->modalHeading(fn($record) => 'QR Code Kiosk: ' . $record->name)
                    ->modalDescription('Scan QR ini dari smartphone tamu untuk langsung Check-In ke ruangan ini melalui layanan mandiri.')
                    ->modalContent(fn($record) => new HtmlString('<div class="flex justify-center p-6"><div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">' . QrCode::size(250)->margin(2)->generate(url('/kiosk?room_id=' . $record->id)) . '</div></div>'))
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
