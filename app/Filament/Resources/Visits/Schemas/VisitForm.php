<?php

namespace App\Filament\Resources\Visits\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Split;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;

class VisitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()->schema([
                    Section::make('Informasi Utama')
                        ->description('Data tamu dan tujuan kunjungan')
                        ->icon('heroicon-o-user')
                        ->schema([
                            Grid::make(2)->schema([
                                Select::make('guest_id')
                                    ->relationship('guest', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->label('Nama Tamu')
                                    ->createOptionForm([
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
                                    ]),
                                Select::make('department_id')
                                    ->relationship('department', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->label('Tujuan (Fraksi/Bagian)'),
                            ]),
                            TextInput::make('purpose')
                                ->required()
                                ->label('Keperluan')
                                ->columnSpanFull(),
                        ]),

                    Section::make('Jadwal Kunjungan')
                        ->description('Waktu rencana kunjungan')
                        ->icon('heroicon-o-calendar')
                        ->schema([
                            Grid::make(2)->schema([
                                DatePicker::make('visit_date')
                                    ->required()
                                    ->label('Tanggal')
                                    ->native(false),
                                TimePicker::make('visit_time')
                                    ->required()
                                    ->label('Waktu')
                                    ->native(false),
                            ])
                        ])
                ])->columnSpan(['lg' => 2]),

                Group::make()->schema([
                    Section::make('Status Kunjungan')
                        ->schema([
                            Select::make('status')
                                ->options([
                                    'pending' => 'Menunggu Persetujuan',
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    'completed' => 'Selesai (Sudah Check-In)',
                                ])
                                ->default('pending')
                                ->required()
                                ->native(false),
                        ]),
                    Section::make('Log Aktivitas')
                        ->schema([
                            DateTimePicker::make('check_in_time')
                                ->label('Waktu Check In'),
                            DateTimePicker::make('check_out_time')
                                ->label('Waktu Check Out'),
                        ])
                ])->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }
}
