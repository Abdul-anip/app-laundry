<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromoResource\Pages;
use App\Filament\Resources\PromoResource\RelationManagers;
use App\Models\Promo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PromoResource extends Resource
{
    protected static ?string $model = Promo::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    
    protected static ?string $navigationLabel = 'Promos';
    
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Promo Code')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true)
                    ->placeholder('DISC50')
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('code', strtoupper($state)))
                    ->live(onBlur: true),
                    
                Forms\Components\Select::make('discount_type')
                    ->label('Discount Type')
                    ->required()
                    ->options([
                        'percent' => 'Percentage (%)',
                        'fixed' => 'Fixed Amount (Rp)',
                    ])
                    ->default('percent')
                    ->live(),
                    
                Forms\Components\TextInput::make('value')
                    ->label('Discount Value')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix(fn (Get $get): string => $get('discount_type') === 'fixed' ? 'Rp' : '')
                    ->suffix(fn (Get $get): string => $get('discount_type') === 'percent' ? '%' : '')
                    ->placeholder(fn (Get $get): string => $get('discount_type') === 'percent' ? '10' : '10000'),
                    
                Forms\Components\DatePicker::make('expired_at')
                    ->label('Expiry Date')
                    ->nullable()
                    ->native(false)
                    ->displayFormat('d M Y'),
                    
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->inline(false),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Promo Code')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),
                    
                Tables\Columns\TextColumn::make('discount_type')
                    ->label('Type')
                    ->badge()
                    ->colors([
                        'success' => 'percent',
                        'warning' => 'fixed',
                    ])
                    ->formatStateUsing(fn (string $state): string => $state === 'percent' ? 'Percentage' : 'Fixed'),
                    
                Tables\Columns\TextColumn::make('value')
                    ->label('Value')
                    ->formatStateUsing(function ($record): string {
                        if ($record->discount_type === 'percent') {
                            return $record->value . '%';
                        }
                        return 'Rp ' . number_format($record->value, 0, ',', '.');
                    })
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('expired_at')
                    ->label('Expires')
                    ->date('d M Y')
                    ->placeholder('No expiry')
                    ->sortable()
                    ->color(fn ($record): string => $record->expired_at && $record->expired_at->isPast() ? 'danger' : 'success'),
                    
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('orders_count')
                    ->label('Usage')
                    ->counts('orders')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        true => 'Active',
                        false => 'Inactive',
                    ]),
                    
                Tables\Filters\SelectFilter::make('discount_type')
                    ->label('Type')
                    ->options([
                        'percent' => 'Percentage',
                        'fixed' => 'Fixed Amount',
                    ]),
                    
                Tables\Filters\Filter::make('expired')
                    ->label('Expired')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('expired_at')->whereDate('expired_at', '<', now())),
                    
                Tables\Filters\Filter::make('active_unexpired')
                    ->label('Active & Valid')
                    ->query(fn (Builder $query): Builder => $query
                        ->where('is_active', true)
                        ->where(function ($q) {
                            $q->whereNull('expired_at')
                              ->orWhereDate('expired_at', '>=', now());
                        })),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPromos::route('/'),
            'create' => Pages\CreatePromo::route('/create'),
            'edit' => Pages\EditPromo::route('/{record}/edit'),
        ];
    }
}
