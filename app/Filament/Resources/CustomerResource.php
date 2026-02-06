<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationLabel = 'Customers';
    
    protected static ?int $navigationSort = 6;
    
    protected static ?string $modelLabel = 'Customer';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Read-only resource, no form needed
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('role', 'customer'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                    
                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->placeholder('-'),
                    
                Tables\Columns\TextColumn::make('orders_count')
                    ->label('Total Orders')
                    ->counts('orders')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                    
                Tables\Columns\TextColumn::make('orders_sum_total_price')
                    ->label('Total Spent')
                    ->sum('orders', 'total_price')
                    ->money('IDR')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('points')
                    ->label('Points')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                    
                Tables\Columns\TextColumn::make('orders_max_created_at')
                    ->label('Last Order')
                    ->max('orders', 'created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->placeholder('No orders'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_orders')
                    ->label('Has Orders')
                    ->query(fn (Builder $query): Builder => $query->has('orders')),
                    
                Tables\Filters\Filter::make('no_orders')
                    ->label('No Orders Yet')
                    ->query(fn (Builder $query): Builder => $query->doesntHave('orders')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // No bulk actions - read-only
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Customer Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Name'),
                        Infolists\Components\TextEntry::make('email')
                            ->label('Email')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('phone')
                            ->label('Phone')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('points')
                            ->label('Loyalty Points')
                            ->badge()
                            ->color('success'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Member Since')
                            ->dateTime('d M Y'),
                    ])
                    ->columns(2),
                    
                Infolists\Components\Section::make('Order Statistics')
                    ->schema([
                        Infolists\Components\TextEntry::make('orders_count')
                            ->label('Total Orders')
                            ->state(fn (User $record): int => $record->orders()->count()),
                            
                        Infolists\Components\TextEntry::make('total_spent')
                            ->label('Total Spent')
                            ->money('IDR')
                            ->state(fn (User $record): float => $record->orders()->sum('total_price')),
                            
                        Infolists\Components\TextEntry::make('total_weight')
                            ->label('Total Weight')
                            ->suffix(' kg')
                            ->state(fn (User $record): float => $record->orders()->sum('weight_kg')),
                            
                        Infolists\Components\TextEntry::make('last_order')
                            ->label('Last Order')
                            ->dateTime('d M Y, H:i')
                            ->state(fn (User $record) => $record->orders()->latest()->first()?->created_at)
                            ->placeholder('No orders yet'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\OrdersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'view' => Pages\ViewCustomer::route('/{record}'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return false;
    }
}
