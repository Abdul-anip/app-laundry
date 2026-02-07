<?php

namespace App\Filament\Resources\ReviewResource\Widgets;

use App\Models\Review;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ReviewStats extends BaseWidget
{
    protected function getStats(): array
    {
        $avgRating = Review::avg('rating') ?? 0;
        $totalReviews = Review::count();
        $fiveStarCount = Review::where('rating', 5)->count();

        return [
            Stat::make('Average Rating', number_format($avgRating, 1) . ' / 5.0')
                ->description('Based on all reviews')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning')
                ->chart([$avgRating, 5]), // Visual chart

            Stat::make('Total Reviews', $totalReviews)
                ->description('All time reviews')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('5-Star Reviews', $fiveStarCount)
                ->description('Perfect scores')
                ->descriptionIcon('heroicon-m-hand-thumb-up')
                ->color('success'),
        ];
    }
}
