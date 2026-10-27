<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Ruangan Rapat')
                    ->description('Masukkan data nama ruangan dan deskripsinya.')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Ruangan')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
            ]);
    }
}
