<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Cache;

class FooterSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.footer-settings';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Footer Information';

    protected static ?int $navigationSort = 100;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'footer_company_description' => Setting::get('footer_company_description', ''),
            'footer_email' => Setting::get('footer_email', ''),
            'footer_phone' => Setting::get('footer_phone', ''),
            'footer_address' => Setting::get('footer_address', ''),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Company Information')
                    ->description('Manage company description shown in footer')
                    ->schema([
                        Textarea::make('footer_company_description')
                            ->label('Company Description')
                            ->rows(3)
                            ->maxLength(500)
                            ->placeholder('Your premium laundry service partner...')
                            ->required(),
                    ])
                    ->columns(1),

                Section::make('Contact Information')
                    ->description('Manage contact details shown in footer')
                    ->schema([
                        TextInput::make('footer_email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('support@viplaundry.com'),

                        TextInput::make('footer_phone')
                            ->label('Phone Number')
                            ->tel()
                            ->required()
                            ->maxLength(50)
                            ->placeholder('+62 812-3456-7890'),

                        TextInput::make('footer_address')
                            ->label('Address')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Jakarta, Indonesia'),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::set($key, $value, 'footer');
        }

        // Clear all footer-related cache
        Cache::forget('setting_footer_company_description');
        Cache::forget('setting_footer_email');
        Cache::forget('setting_footer_phone');
        Cache::forget('setting_footer_address');

        Notification::make()
            ->success()
            ->title('Settings saved successfully')
            ->body('Footer information has been updated.')
            ->send();
    }
}
