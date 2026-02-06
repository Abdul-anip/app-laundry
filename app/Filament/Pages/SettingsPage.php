<?php

namespace App\Filament\Pages;

use App\Models\LandingPageSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Actions\Action;

class SettingsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.settings-page';
    
    protected static ?string $navigationLabel = 'Settings';
    
    protected static ?int $navigationSort = 100;

    public ?array $data = [];

    public function mount(): void
    {
        $settings = LandingPageSetting::first();
        
        if ($settings) {
            $this->data = [
                'laundry_address' => $settings->laundry_address,
                'laundry_latitude' => $settings->laundry_latitude,
                'laundry_longitude' => $settings->laundry_longitude,
            ];
            
            $this->form->fill($this->data);
        } else {
            // Default values if no settings exist
             $this->form->fill([
                'laundry_latitude' => -0.1185067,
                'laundry_longitude' => 100.566124,
                'laundry_address' => 'VIP Laundry, Jl. Merdeka No. 1',
            ]);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Laundry Location')
                    ->description('Set your laundry location for the landing page and accurate distance calculation.')
                    ->schema([
                        Forms\Components\Textarea::make('laundry_address')
                            ->label('Address')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Jl. Contoh No. 123, Kota Contoh'),
                            
                        Forms\Components\View::make('filament.settings.location-button'),
                            
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('laundry_latitude')
                                    ->label('Latitude')
                                    ->required()
                                    ->numeric()
                                    ->placeholder('-0.1185067'),
                                    
                                Forms\Components\TextInput::make('laundry_longitude')
                                    ->label('Longitude')
                                    ->required()
                                    ->numeric()
                                    ->placeholder('100.566124'),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        
        $settings = LandingPageSetting::first();
        
        if (!$settings) {
            $settings = new LandingPageSetting();
        }
        
        $settings->laundry_address = $data['laundry_address'];
        $settings->laundry_latitude = $data['laundry_latitude'];
        $settings->laundry_longitude = $data['laundry_longitude'];
        $settings->save();
        
        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
