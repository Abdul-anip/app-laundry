<?php

namespace App\Filament\Pages;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Actions;
use Filament\Notifications\Notification;

class ReportsPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.pages.reports-page';
    
    protected static ?string $navigationLabel = 'Daily Report';
    
    protected static ?int $navigationSort = 99;

    public ?string $date = null;

    public function mount(): void
    {
        $this->date = now()->format('Y-m-d');
        $this->form->fill(['date' => $this->date]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->label('Report Date')
                    ->required()
                    ->default(now())
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->date = $state;
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $date = $this->date ? Carbon::parse($this->date) : now();
                return Order::whereDate('created_at', $date);
            })
            ->columns([
                Tables\Columns\TextColumn::make('order_code')
                    ->label('Order Code')
                    ->searchable()
                    ->sortable()
                    ->url(fn (Order $record): string => route('filament.admin.resources.orders.view', ['record' => $record->id]))
                    ->color('info'),
                    
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer'),
                    
                Tables\Columns\TextColumn::make('order_source')
                    ->label('Source')
                    ->badge()
                    ->colors([
                        'success' => 'online',
                        'secondary' => 'offline',
                    ]),
                    
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->placeholder('Bundle'),
                    
                Tables\Columns\TextColumn::make('weight_kg')
                    ->label('Weight')
                    ->suffix(' kg')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->label('Total Weight')),
                    
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Price')
                    ->money('IDR')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('IDR')->label('Total Revenue')),
                    
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'pickup',
                        'primary' => 'process',
                        'success' => 'finished',
                        'gray' => 'delivered',
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public function getStatsProperty(): array
    {
        $date = $this->date ? Carbon::parse($this->date) : now();
        $orders = Order::whereDate('created_at', $date)->get();

        return [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total_price'),
            'total_weight' => $orders->sum('weight_kg'),
            'online_orders' => $orders->where('order_source', 'online')->count(),
            'offline_orders' => $orders->where('order_source', 'offline')->count(),
        ];
    }
    public function getHeaderActions(): array
    {
        return [
            Actions\Action::make('downloadPdf')
                ->label('Download PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->action(function () {
                    $date = $this->date ? Carbon::parse($this->date) : now();
                    $orders = Order::whereDate('created_at', $date)->get();
                    
                    if ($orders->isEmpty()) {
                        Notification::make()
                            ->title('No data to download')
                            ->warning()
                            ->send();
                        return;
                    }

                    $stats = [
                        'total_orders' => $orders->count(),
                        'total_revenue' => $orders->sum('total_price'),
                        'total_weight' => $orders->sum('weight_kg'),
                        'online_orders' => $orders->where('order_source', 'online')->count(),
                        'offline_orders' => $orders->where('order_source', 'offline')->count(),
                    ];

                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.daily_pdf', [
                        'orders' => $orders,
                        'stats' => $stats,
                        'date' => $date->format('Y-m-d'),
                    ]);

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, 'daily-report-' . $date->format('Y-m-d') . '.pdf');
                }),
        ];
    }
}
