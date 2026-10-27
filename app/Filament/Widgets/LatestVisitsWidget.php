<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use App\Models\Department;
use App\Models\Visit;
use Carbon\Carbon;

class LatestVisitsWidget extends Widget implements HasForms
{
    use InteractsWithForms;
    protected static ?string $heading = 'Kunjungan Terbaru';
    protected string $view = 'filament.widgets.latest-visits-custom';
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 2;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'date' => Carbon::today()->format('Y-m-d'),
            'department_id' => null,
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Select::make('department_id')
                    ->label('Filter Ruangan')
                    ->options(Department::pluck('name', 'id'))
                    ->searchable()
                    ->placeholder('Semua Ruangan')
                    ->live(),
                DatePicker::make('date')
                    ->label('Filter Tanggal')
                    ->displayFormat('l, d F Y')
                    ->native(false)
                    ->default(now())
                    ->live(),
            ])
            ->columns(2)
            ->statePath('data');
    }

    public function getVisitsProperty()
    {
        $rawDate = $this->data['date'] ?? null;
        $date = $rawDate ? Carbon::parse($rawDate)->format('Y-m-d') : Carbon::today()->format('Y-m-d');
        $deptId = $this->data['department_id'] ?? null;

        $query = Visit::with(['department', 'guest'])
            ->whereDate('visit_date', $date)
            ->latest();

        if ($deptId) {
            $query->where('department_id', $deptId);
        }

        return $query->get();
    }
}
