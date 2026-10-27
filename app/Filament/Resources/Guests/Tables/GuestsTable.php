<?php

namespace App\Filament\Resources\Guests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GuestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Data Pengunjung')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (\App\Models\Guest $record): string => 'NIK: ' . $record->nik)
                    ->icon('heroicon-o-user'),
                TextColumn::make('nik')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone')
                    ->label('Nomor Kontak')
                    ->searchable()
                    ->icon('heroicon-o-phone'),
                TextColumn::make('agency_name')
                    ->label('Instansi / Perusahaan')
                    ->searchable()
                    ->badge()
                    ->color('info')
                    ->default('-'),
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
