<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderTracking;
use App\Notifications\OrderDelivered;
use App\Notifications\OrderFinished;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Order Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('order_code')
                            ->label('Order Code')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->colors([
                                'warning' => 'pending',
                                'info' => 'pickup',
                                'primary' => 'process',
                                'success' => 'finished',
                                'gray' => 'delivered',
                            ]),
                        Infolists\Components\TextEntry::make('order_source')
                            ->label('Source')
                            ->badge()
                            ->colors([
                                'success' => 'online',
                                'gray' => 'offline',
                            ]),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Order Date')
                            ->dateTime('d M Y, H:i'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Customer Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('customer_name')
                            ->label('Name'),
                        Infolists\Components\TextEntry::make('phone')
                            ->label('Phone'),
                        Infolists\Components\TextEntry::make('address')
                            ->label('Address')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('latitude')
                            ->label('Latitude')
                            ->placeholder('-')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('longitude')
                            ->label('Longitude')
                            ->placeholder('-')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('distance_km')
                            ->label('Distance')
                            ->suffix(' km')
                            ->placeholder('-'),
                        Infolists\Components\TextEntry::make('pickup_date')
                            ->label('Pickup Date')
                            ->date('d M Y'),
                        Infolists\Components\TextEntry::make('pickup_time')
                            ->label('Pickup Time'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Service Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('service.name')
                            ->label('Service')
                            ->placeholder('-'),
                        Infolists\Components\TextEntry::make('bundle.name')
                            ->label('Bundle')
                            ->placeholder('-'),
                        Infolists\Components\TextEntry::make('fabric_type')
                            ->label('Fabric Type')
                            ->placeholder('-'),
                        Infolists\Components\TextEntry::make('weight_kg')
                            ->label('Weight (kg)')
                            ->suffix(' kg'),
                        Infolists\Components\TextEntry::make('promo.code')
                            ->label('Promo Code')
                            ->placeholder('No promo'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Pricing')
                    ->schema([
                        Infolists\Components\TextEntry::make('subtotal')
                            ->label('Subtotal')
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('pickup_fee')
                            ->label('Pickup Fee')
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('discount')
                            ->label('Discount')
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('total_price')
                            ->label('Total Price')
                            ->money('IDR')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold')
                            ->color('success'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Order Tracking')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('orderTrackings')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('status')
                                    ->badge()
                                    ->colors([
                                        'warning' => 'pending',
                                        'info' => 'pickup',
                                        'primary' => 'process',
                                        'success' => 'finished',
                                        'gray' => 'delivered',
                                        'warning' => 'point_added',
                                    ]),
                                Infolists\Components\TextEntry::make('description'),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Time')
                                    ->dateTime('d M Y, H:i'),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            // View on Google Maps Action
            Actions\Action::make('viewOnMaps')
                ->label('View on Google Maps')
                ->icon('heroicon-o-map-pin')
                ->color('info')
                ->url(fn (Order $record): ?string => 
                    $record->latitude && $record->longitude 
                        ? "https://www.google.com/maps?q={$record->latitude},{$record->longitude}"
                        : null
                )
                ->openUrlInNewTab()
                ->visible(fn (Order $record): bool => $record->latitude && $record->longitude),
            
            // Advance Status Action
            Actions\Action::make('advanceStatus')
                ->label(fn () => match ($this->record->status) {
                    'pending' => 'Start Pickup',
                    'pickup' => 'Start Process',
                    'process' => 'Finish Order',
                    'finished' => 'Deliver Order',
                    default => 'Completed',
                })
                ->icon('heroicon-o-arrow-right-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Update Order Status')
                ->modalDescription(fn () => match ($this->record->status) {
                    'pending' => 'Are you sure you want to start the pickup process for this order?',
                    'pickup' => 'Are you sure you want to start processing (washing) this order?',
                    'process' => 'Are you sure this order is finished and ready/packed?',
                    'finished' => 'Are you sure you want to deliver this order back to the customer?',
                    default => 'Are you sure you want to advance the status?',
                })
                ->action(function () {
                    try {
                        DB::beginTransaction();

                        $order = $this->record;
                        $originalStatus = $order->status;
                        
                        $nextStatus = match ($originalStatus) {
                            'pending' => 'pickup',
                            'pickup' => 'process',
                            'process' => 'finished',
                            'finished' => 'delivered',
                            default => null,
                        };

                        if (!$nextStatus) {
                            Notification::make()
                                ->title('No next status available')
                                ->warning()
                                ->send();
                            return;
                        }

                        // Update Order Status
                        $order->update([
                            'status' => $nextStatus,
                            'payment_status' => $nextStatus === 'delivered' ? 'paid' : $order->payment_status,
                        ]);

                        // Create Order Tracking
                        OrderTracking::create([
                            'order_id' => $order->id,
                            'status' => $nextStatus,
                            'description' => 'Status updated to ' . ucfirst($nextStatus) . ' by Admin',
                        ]);

                        // Trigger Notifications
                        if ($originalStatus !== 'finished' && $nextStatus === 'finished') {
                            if ($order->user) {
                                $order->user->notify(new OrderFinished($order));
                            }
                        }

                        if ($originalStatus !== 'delivered' && $nextStatus === 'delivered') {
                            if ($order->order_source === 'online' && $order->user) {
                                $order->user->notify(new OrderDelivered($order));
                            }
                        }

                        // Point System Logic: Add points if status is 'finished' and was not previously 'finished'
                        if ($nextStatus === 'finished' && $originalStatus !== 'finished') {
                            $points = floor($order->weight_kg);
                            // Ensure we have a user and points > 0
                            if ($points > 0 && $order->user) {
                                $order->user->increment('points', $points);
                                
                                OrderTracking::create([
                                    'order_id' => $order->id,
                                    'status' => 'point_added',
                                    'description' => "Customer earned {$points} points",
                                ]);
                            }
                        }

                        DB::commit();

                        Notification::make()
                            ->title('Order status updated successfully!')
                            ->success()
                            ->send();

                        // Refresh the page to show new data
                        return redirect()->route('filament.admin.resources.orders.view', ['record' => $order]);

                    } catch (\Exception $e) {
                        DB::rollBack();
                        
                        Notification::make()
                            ->title('Failed to update status')
                            ->danger()
                            ->send();
                    }
                })
                ->visible(fn () => in_array($this->record->status, ['pending', 'pickup', 'process', 'finished'])),

            Actions\Action::make('printReceipt')
                ->label('Print Receipt')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->action(function () {
                    $order = $this->record;
                    $order->load(['user', 'service', 'bundle', 'promo']);
                    
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.orders.receipt', compact('order'));
                    
                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, 'struk-' . $order->order_code . '.pdf');
                }),
        ];
    }
}
