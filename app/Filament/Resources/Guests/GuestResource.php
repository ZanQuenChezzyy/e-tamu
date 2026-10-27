<?php

namespace App\Filament\Resources\Guests;

use App\Filament\Resources\Guests\Pages\CreateGuest;
use App\Filament\Resources\Guests\Pages\EditGuest;
use App\Filament\Resources\Guests\Pages\ListGuests;
use App\Filament\Resources\Guests\Schemas\GuestForm;
use App\Filament\Resources\Guests\Tables\GuestsTable;
use App\Models\Guest;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GuestResource extends Resource
{
    protected static ?string $model = Guest::class;

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 2;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::UserGroup;
    
    public static function getNavigationLabel(): string
    {
        return 'Data Tamu';
    }

    public static function form(Schema $schema): Schema
    {
        return GuestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GuestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGuests::route('/'),
            'create' => CreateGuest::route('/create'),
            'edit' => EditGuest::route('/{record}/edit'),
        ];
    }
}
