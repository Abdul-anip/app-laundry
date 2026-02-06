<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrderStatsChart extends ChartWidget
{
    protected static ?string $heading = 'Order Status Distribution';
    
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $pending = Order::where('status', 'pending')->count();
        $process = Order::where('status', 'process')->count();
        $finished = Order::where('status', 'finished')->count();
        $delivered = Order::where('status', 'delivered')->count();
        $pickup = Order::where('status', 'pickup')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => [$pending, $pickup, $process, $finished, $delivered],
                    'backgroundColor' => [
                        '#F59E0B', // Pending - Warning
                        '#3B82F6', // Pickup - Info
                        '#6366F1', // Process - Indigo
                        '#10B981', // Finished - Success
                        '#6B7280', // Delivered - Gray
                    ],
                ],
            ],
            'labels' => ['Pending', 'Pickup', 'In Process', 'Finished', 'Delivered'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
