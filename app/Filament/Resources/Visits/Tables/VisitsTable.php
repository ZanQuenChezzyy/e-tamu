<?php

namespace App\Filament\Resources\Visits\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VisitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('guest.name')
                    ->label('Tamu & Tujuan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (\App\Models\Visit $record): string => 'Ke: ' . ($record->department->name ?? '-'))
                    ->icon('heroicon-o-user-group'),
                TextColumn::make('purpose')
                    ->label('Keperluan')
                    ->searchable()
                    ->wrap()
                    ->limit(50),
                TextColumn::make('visit_date')
                    ->label('Jadwal Kunjungan')
                    ->date('d M Y')
                    ->sortable()
                    ->description(fn (\App\Models\Visit $record): string => \Carbon\Carbon::parse($record->visit_time)->format('H:i'))
                    ->icon('heroicon-o-calendar-days'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'completed' => 'info',
                        default => 'primary',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'pending' => 'heroicon-o-clock',
                        'approved' => 'heroicon-o-check-circle',
                        'rejected' => 'heroicon-o-x-circle',
                        'completed' => 'heroicon-o-flag',
                        default => 'heroicon-o-document',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'completed' => 'Selesai',
                        default => ucfirst($state),
                    }),
                TextColumn::make('check_in_time')
                    ->label('Check In')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('check_out_time')
                    ->label('Check Out')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Action::make('approve')
                    ->label('Approve & Generate QR')
                    ->icon('heroicon-o-qr-code')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (\App\Models\Visit $record) {
                        $record->update([
                            'status' => 'approved',
                            'qr_code_token' => \Illuminate\Support\Str::uuid()->toString(),
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Visit approved successfully')
                            ->success()
                            ->send();
                    })
                    ->visible(fn(\App\Models\Visit $record) => $record->status === 'pending'),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
