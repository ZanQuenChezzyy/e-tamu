<?php

namespace App\Filament\Resources\Guests\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class GuestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Data Pengunjung / Tamu')
                    ->description('Pastikan NIK dan nama lengkap terisi dengan benar.')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('nik')
                                ->label('NIK / No Identitas')
                                ->required()
                                ->numeric()
                                ->maxLength(16),
                            TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->label('Nomor Telepon / WA')
                                ->tel()
                                ->maxLength(20),
                            TextInput::make('agency_name')
                                ->label('Instansi / Perusahaan')
                                ->maxLength(255),
                        ])
                    ])
            ]);
    }
}
