<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    
    protected static ?string $navigationLabel = 'Reviews';
    
    protected static ?int $navigationSort = 5;

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
            ->columns([
                Tables\Columns\TextColumn::make('rating')
                    ->label('Rating')
                    ->sortable()
                    ->formatStateUsing(fn (int $state): string => str_repeat('⭐', $state))
                    ->badge()
                    ->color('warning'),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('order.order_code')
                    ->label('Order Code')
                    ->searchable()
                    ->sortable()
                    ->url(fn (Review $record): string => $record->order_id 
                        ? route('filament.admin.resources.orders.view', ['record' => $record->order_id])
                        : '#')
                    ->color('info'),
                    
                Tables\Columns\TextColumn::make('comment')
                    ->label('Comment')
                    ->limit(50)
                    ->searchable()
                    ->wrap(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Reviewed At')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rating')
                    ->label('Rating')
                    ->options([
                        5 => '⭐⭐⭐⭐⭐ (5 stars)',
                        4 => '⭐⭐⭐⭐ (4 stars)',
                        3 => '⭐⭐⭐ (3 stars)',
                        2 => '⭐⭐ (2 stars)',
                        1 => '⭐ (1 star)',
                    ]),
            ])
            ->actions([
                // No actions - read-only
            ])
            ->bulkActions([
                // No bulk actions - read-only
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListReviews::route('/'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return false;
    }
}
