<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Order;

class LatestOrders extends BaseWidget
{
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_code')
                    ->label('Code')
                    ->color('info')
                    ->url(fn (Order $record): string => route('filament.admin.resources.orders.view', ['record' => $record->id])),
                    
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer'),
                    
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->placeholder('Bundle'),
                    
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'pickup',
                        'primary' => 'process',
                        'success' => 'finished',
                        'gray' => 'delivered',
                    ]),
                    
                Tables\Columns\TextColumn::make('total_price')
                    ->money('IDR'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M H:i'),
            ]);
    }
}
