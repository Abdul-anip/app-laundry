<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationLabel = 'Orders';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Orders are not editable via Filament, only viewable
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['user', 'service', 'bundle', 'promo']))
            ->columns([
                Tables\Columns\TextColumn::make('order_code')
                    ->label('Order Code')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->description(fn (Order $record): string => $record->phone ?? ''),
                    
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Service')
                    ->badge()
                    ->color('info')
                    ->placeholder('Bundle'),
                    
                Tables\Columns\TextColumn::make('bundle.name')
                    ->label('Bundle')
                    ->badge()
                    ->color('warning')
                    ->placeholder('-'),
                    
                Tables\Columns\TextColumn::make('order_source')
                    ->label('Source')
                    ->badge()
                    ->colors([
                        'success' => 'online',
                        'gray' => 'offline',
                    ]),
                    
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'pickup',
                        'primary' => 'process',
                        'success' => 'finished',
                        'info' => 'delivered', // Changed delivered to info (blue-ish)
                        'gray' => 'completed', // Completed is final state
                    ]),
                    
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'pickup' => 'Pickup',
                        'process' => 'Process',
                        'finished' => 'Finished',
                        'delivered' => 'Delivered',
                        'completed' => 'Completed',
                    ])
                    ->multiple(),
                    
                Tables\Filters\SelectFilter::make('order_source')
                    ->label('Source')
                    ->options([
                        'online' => 'Online',
                        'offline' => 'Offline',
                    ]),
                    
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('From'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                
                // 1. WhatsApp Action Group
                Tables\Actions\ActionGroup::make([
                    // WA Pickup (One-time Action)
                    Tables\Actions\Action::make('wa_pickup')
                        ->label('WA Pickup')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Kirim WA Pickup?')
                        ->modalDescription('Pesan ini hanya bisa dikirim SATU KALI. Pastikan Anda siap menjemput order ini.')
                        ->action(function (Order $record) {
                            // 1. Create Log
                            \App\Models\OrderTracking::create([
                                'order_id' => $record->id,
                                'status' => 'pickup_notified', // Custom internal status or just description
                                'description' => 'WhatsApp Pickup notification sent',
                            ]);
                            
                            // 2. Build URL
                            $url = "https://wa.me/" . self::formatPhone($record->phone) . "?text=" . urlencode("Halo {$record->customer_name}, kurir VIP Laundry sedang menuju lokasi Anda untuk penjemputan order {$record->order_code}. Mohon ditunggu ya! \n\n- Terima Kasih");
                            
                            // 3. Notification
                            \Filament\Notifications\Notification::make()
                                ->title('WA Pickup Marked as Sent')
                                ->success()
                                ->send();

                            // 4. Redirect
                            return redirect()->away($url);
                        })
                        ->visible(function (Order $record) {
                            // Visible if status is pending/pickup AND notification NOT YET sent
                            $alreadySent = $record->orderTrackings()
                                ->where('description', 'WhatsApp Pickup notification sent')
                                ->exists();
                                
                            return in_array($record->status, ['pending', 'pickup']) && !$alreadySent;
                        }),

                    // WA Invoice / Konfirmasi
                    Tables\Actions\Action::make('wa_invoice')
                        ->label('WA Tagihan')
                        ->icon('heroicon-o-currency-dollar')
                        ->color('success')
                        ->url(fn (Order $record) => "https://wa.me/" . self::formatPhone($record->phone) . "?text=" . urlencode("Halo {$record->customer_name}, Order {$record->order_code} sudah kami timbang.\n\nBerat: " . floatval($record->weight_kg) . " Kg\nTotal: Rp " . number_format($record->total_price, 0, ',', '.') . "\n\nDetail: " . route('customer.orders.show', $record) . "\n\nOrder segera kami proses. Terima kasih!"), true)
                        ->visible(fn (Order $record) => $record->status === 'pickup' && $record->weight_kg > 0),
                    
                    // WA General Status Update
                    Tables\Actions\Action::make('wa_status')
                        ->label('WA Update Status')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('success')
                        ->url(fn (Order $record) => "https://wa.me/" . self::formatPhone($record->phone) . "?text=" . urlencode("Halo {$record->customer_name}, update status order {$record->order_code}: *" . ucfirst($record->status) . "*.\n\nCek detail: " . route('customer.orders.show', $record)), true),
                ])
                ->icon('heroicon-m-chat-bubble-oval-left-ellipsis')
                ->color('success')
                ->tooltip('WhatsApp Actions'),

                // 2. Input Weight Action (Only for Service orders in 'pickup' status)
                Tables\Actions\Action::make('input_weight')
                    ->label('Input Berat')
                    ->icon('heroicon-o-scale')
                    ->color('info')
                    ->form([
                        Forms\Components\TextInput::make('weight_kg')
                            ->label('Berat Aktual (Kg)')
                            ->numeric()
                            ->required()
                            ->step(0.1)
                            ->minValue(0.1)
                            ->suffix('Kg'),
                    ])
                    ->action(function (Order $record, array $data) {
                        $weight = $data['weight_kg'];
                        
                        // Recalculate Logic
                        $subtotal = 0;
                        if ($record->service_id && $record->service) {
                            $subtotal = $record->service->price_per_kg * $weight;
                        } elseif ($record->bundle_id && $record->bundle) {
                            // Bundle usually fixed price, but if weight matters, adjust here. 
                            // Assuming bundle price is fixed for now, so subtotal remains bundle price.
                            $subtotal = $record->subtotal; 
                        }

                        // Re-calculate Discount if percentage
                        $discount = 0;
                        if ($record->promo_id && $record->promo) {
                            if ($record->promo->discount_type === 'percent') {
                                $discount = $subtotal * ($record->promo->value / 100);
                            } else {
                                $discount = $record->promo->value; // Fixed amount
                            }
                            // Cap discount
                            if ($discount > $subtotal) $discount = $subtotal;
                        }

                        $totalPrice = $subtotal + $record->pickup_fee - $discount;

                        $record->update([
                            'weight_kg' => $weight,
                            'subtotal' => $subtotal,
                            'discount' => $discount,
                            'total_price' => $totalPrice,
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Berat & Harga Diupdate')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Order $record) => $record->status === 'pickup' && $record->order_type !== 'bundle'), // Assuming bundle doesn't need weight input for price? Or maybe it does for record keeping. Adjusted to show for service mostly.

                // 3. Main Status Button
                Tables\Actions\Action::make('advance_status')
                    ->label(fn (Order $record) => match ($record->status) {
                        'pending' => 'Start Pickup',
                        'pickup' => 'Start Process',
                        'process' => 'Finish Order',
                        'finished' => 'Deliver Order',
                        default => 'Next Step',
                    })
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color(fn (Order $record) => match ($record->status) {
                         'pickup' => $record->weight_kg > 0 ? 'success' : 'gray', // Dim if weight not input
                         default => 'success',
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Update Order Status')
                    ->modalDescription(fn (Order $record) => match ($record->status) {
                        'pending' => 'Mulai penjemputan? Jangan lupa kirim WA Pickup ke customer.',
                        'pickup' => 'Lanjut proses cuci? Pastikan BERAT sudah diinput.',
                        'process' => 'Order selesai dicuci/setrika?',
                        'finished' => 'Antar kembali ke customer?',
                        default => 'Lanjut ke status berikutnya?',
                    })
                    ->action(function (Order $record) {
                        // Validation: Block passing 'pickup' if weight is 0 for service orders
                        if ($record->status === 'pickup' && $record->service_id && $record->weight_kg <= 0) {
                            \Filament\Notifications\Notification::make()
                                ->title('Gagal Update Status')
                                ->body('Harap input berat aktual terlebih dahulu sebelum memproses order!')
                                ->danger()
                                ->send();
                            return;
                        }

                        $nextStatus = match ($record->status) {
                            'pending' => 'pickup',
                            'pickup' => 'process',
                            'process' => 'finished',
                            'finished' => 'delivered',
                            default => null,
                        };

                        if ($nextStatus) {
                            $record->update(['status' => $nextStatus]);
                            
                            // Create tracking
                            \App\Models\OrderTracking::create([
                                'order_id' => $record->id,
                                'status' => $nextStatus,
                                'description' => 'Status updated to ' . ucfirst($nextStatus),
                            ]);
                            
                            // Point System Logic (Added)
                            if ($nextStatus === 'finished') {
                                $points = floor($record->total_price / 1000); // 1 Point per 1k
                                if ($points > 0 && $record->user) {
                                    $record->user->increment('points', $points);
                                    
                                    \App\Models\OrderTracking::create([
                                        'order_id' => $record->id,
                                        'status' => 'point_added',
                                        'description' => "Customer earned {$points} points from order total Rp " . number_format($record->total_price, 0, ',', '.'),
                                    ]);
                                    
                                    \Filament\Notifications\Notification::make()
                                        ->title("{$points} Points Added to User")
                                        ->success()
                                        ->send();
                                }
                            }
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Status Updated')
                                ->success()
                                ->send();
                        }
                    })
                    ->visible(fn (Order $record) => in_array($record->status, ['pending', 'pickup', 'process', 'finished'])),
            ])
            ->bulkActions([
                // No bulk actions for orders
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }

    protected static function formatPhone(?string $phone): string
    {
        if (!$phone) return '';
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        return $phone;
    }
}
