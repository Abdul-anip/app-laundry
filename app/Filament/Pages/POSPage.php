<?php

namespace App\Filament\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Bundle;
use App\Models\Order;
use App\Models\OrderTracking;
use App\Models\Promo;
use App\Models\Service;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Get;
use Filament\Forms\Set;

class POSPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    protected static string $view = 'filament.pages.pos-page';
    
    protected static ?string $navigationLabel = 'POS System';
    
    protected static ?string $title = 'Point of Sale (POS)';
    
    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'order_type' => 'service',
            'payment_method' => 'cash',
            'weight_kg' => 1,
            'pickup_date' => now()->toDateString(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        // LEFT COLUMN - INPUTS
                        Forms\Components\Section::make('Customer Details')
                            ->columnSpan(2)
                            ->schema([
                                Forms\Components\Radio::make('customer_source')
                                    ->label('Customer Type')
                                    ->options([
                                        'existing' => 'Registered Member',
                                        'manual' => 'Walk-in / Guest',
                                    ])
                                    ->inline()
                                    ->required()
                                    ->live(),
                                    
                                Forms\Components\Select::make('customer_user_id')
                                    ->label('Search Customer')
                                    ->options(User::where('role', 'customer')->pluck('name', 'id'))
                                    ->searchable()
                                    ->visible(fn (Get $get) => $get('customer_source') === 'existing')
                                    ->required(fn (Get $get) => $get('customer_source') === 'existing')
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        if ($user = User::find($state)) {
                                            $set('phone', $user->phone);
                                        }
                                    }),
                                    
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\TextInput::make('customer_name')
                                            ->label('Customer Name')
                                            ->visible(fn (Get $get) => $get('customer_source') === 'manual')
                                            ->required(fn (Get $get) => $get('customer_source') === 'manual'),
                                            
                                        Forms\Components\TextInput::make('phone')
                                            ->label('Phone Number')
                                            ->tel()
                                            ->required(),
                                    ]),
                            ]),
                            
                        Forms\Components\Section::make('Order Details')
                            ->columnSpan(2)
                            ->schema([
                                Forms\Components\ToggleButtons::make('order_type')
                                    ->label('Service Type')
                                    ->options([
                                        'service' => 'Per Kiogram',
                                        'bundle' => 'Bundle Package',
                                    ])
                                    ->colors([
                                        'service' => 'primary',
                                        'bundle' => 'warning',
                                    ])
                                    ->icons([
                                        'service' => 'heroicon-o-scale',
                                        'bundle' => 'heroicon-o-gift',
                                    ])
                                    ->inline()
                                    ->live()
                                    ->afterStateUpdated(fn (Set $set) => $set('total_price', 0)),

                                Forms\Components\Select::make('service_id')
                                    ->label('Select Service')
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->options(Service::all()->mapWithKeys(fn ($s) => [$s->id => "$s->name (Rp " . number_format($s->price_per_kg, 0, ',', '.') . "/kg)"]))
                                    ->visible(fn (Get $get) => $get('order_type') === 'service')
                                    ->required(fn (Get $get) => $get('order_type') === 'service')
                                    ->live()
                                    ->afterStateUpdated(fn ($state, Get $get, Set $set) => $this->calculateTotal($get, $set)),

                                Forms\Components\TextInput::make('weight_kg')
                                    ->label('Weight (kg)')
                                    ->numeric()
                                    ->default(1)
                                    ->step(0.1)
                                    ->minValue(0.1)
                                    ->visible(fn (Get $get) => $get('order_type') === 'service')
                                    ->required(fn (Get $get) => $get('order_type') === 'service')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Get $get, Set $set) => $this->calculateTotal($get, $set)),

                                Forms\Components\Select::make('bundle_id')
                                    ->label('Select Bundle')
                                    ->searchable()
                                    ->preload()
                                    ->native(false)
                                    ->options(Bundle::all()->mapWithKeys(fn ($b) => [$b->id => "$b->name (Rp " . number_format($b->price, 0, ',', '.') . ")"]))
                                    ->visible(fn (Get $get) => $get('order_type') === 'bundle')
                                    ->required(fn (Get $get) => $get('order_type') === 'bundle')
                                    ->live()
                                    ->afterStateUpdated(fn ($state, Get $get, Set $set) => $this->calculateTotal($get, $set)),
                                    
                                Forms\Components\Textarea::make('notes')
                                    ->label('Order Notes')
                                    ->rows(2),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\DatePicker::make('pickup_date')
                                            ->label('Pickup Date')
                                            ->default(now())
                                            ->minDate(now()->startOfDay())
                                            ->required(),
                                        Forms\Components\Select::make('pickup_time')
                                            ->label('Pickup Time')
                                            ->native(false)
                                            ->options([
                                                '08:00' => '08:00',
                                                '09:00' => '09:00',
                                                '10:00' => '10:00',
                                                '11:00' => '11:00',
                                                '12:00' => '12:00',
                                                '13:00' => '13:00',
                                                '14:00' => '14:00',
                                                '15:00' => '15:00',
                                                '16:00' => '16:00',
                                                '17:00' => '17:00',
                                                '18:00' => '18:00',
                                                '19:00' => '19:00',
                                                '20:00' => '20:00',
                                            ])
                                            ->default(now()->format('H:00'))
                                            ->required(),
                                    ]),
                            ]),

                        // RIGHT COLUMN - SUMMARY
                        Forms\Components\Section::make('Payment & Summary')
                            ->columnSpan(1)
                            ->schema([
                                Forms\Components\ToggleButtons::make('payment_method')
                                    ->label('Payment Method')
                                    ->options([
                                        'cash' => 'Cash',
                                        'transfer' => 'Transfer',
                                    ])
                                    ->colors([
                                        'cash' => 'success',
                                        'transfer' => 'info',
                                    ])
                                    ->icons([
                                        'cash' => 'heroicon-o-banknotes',
                                        'transfer' => 'heroicon-o-credit-card',
                                    ])
                                    ->inline()
                                    ->required(),

                                Forms\Components\TextInput::make('promo_code')
                                    ->label('Promo Code')
                                    ->placeholder('Enter code')
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('checkPromo')
                                            ->icon('heroicon-m-check')
                                            ->action(function (Get $get, Set $set) {
                                                $this->calculateTotal($get, $set);
                                            })
                                    )
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Get $get, Set $set) => $this->calculateTotal($get, $set)),

                                Forms\Components\Placeholder::make('summary_subtotal')
                                    ->label('Subtotal')
                                    ->content(fn (Get $get) => 'Rp ' . number_format($get('temp_subtotal') ?? 0, 0, ',', '.')),

                                Forms\Components\Placeholder::make('summary_discount')
                                    ->label('Discount')
                                    ->content(fn (Get $get) => '- Rp ' . number_format($get('temp_discount') ?? 0, 0, ',', '.'))
                                    ->visible(fn (Get $get) => ($get('temp_discount') ?? 0) > 0),

                                Forms\Components\Placeholder::make('summary_total')
                                    ->label('TOTAL ORDER')
                                    ->content(fn (Get $get) => 'Rp ' . number_format($get('temp_total_price') ?? 0, 0, ',', '.'))
                                    ->extraAttributes(['class' => 'text-2xl font-bold text-primary-600']),

                                // Hidden fields to store calculated values for submission
                                Forms\Components\Hidden::make('temp_subtotal'),
                                Forms\Components\Hidden::make('temp_discount'),
                                Forms\Components\Hidden::make('temp_total_price'),
                                
                                Forms\Components\Actions::make([
                                    Forms\Components\Actions\Action::make('submit')
                                        ->label('Create Order')
                                        ->color('primary')
                                        ->size('lg')
                                        ->action('createOrder')
                                        ->extraAttributes(['class' => 'w-full']),
                                ]),
                            ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function calculateTotal(Get $get, Set $set): void
    {
        $subtotal = 0;
        $orderType = $get('order_type');

        if ($orderType === 'service' && $get('service_id')) {
            $service = Service::find($get('service_id'));
            if ($service) {
                $subtotal = $service->price_per_kg * max(0, floatval($get('weight_kg')));
            }
        } elseif ($orderType === 'bundle' && $get('bundle_id')) {
            $bundle = Bundle::find($get('bundle_id'));
            if ($bundle) {
                $subtotal = $bundle->price;
            }
        }

        $discount = 0;
        $promoCode = $get('promo_code');
        if ($promoCode) {
            $promo = Promo::where('code', $promoCode)
                ->where('is_active', true)
                ->where(function ($query) {
                    $query->whereNull('expired_at')->orWhere('expired_at', '>=', now());
                })
                ->first();

            if ($promo) {
                if ($promo->discount_type === 'percent') {
                     $discount = $subtotal * ($promo->value / 100);
                } else {
                     $discount = $promo->value;
                }
                $discount = min($discount, $subtotal); // Cap at subtotal
            }
        }

        $total = max(0, $subtotal - $discount);

        $set('temp_subtotal', $subtotal);
        $set('temp_discount', $discount);
        $set('temp_total_price', $total);
    }

    public function createOrder(): void
    {
        $data = $this->form->getState();
        
        if (($data['order_type'] === 'service' && empty($data['service_id'])) || 
            ($data['order_type'] === 'bundle' && empty($data['bundle_id']))) {
            Notification::make()->title('Please select a service or bundle')->danger()->send();
            return;
        }

        DB::beginTransaction();
        try {
            $customerUserId = null;
            $customerName = '';
            
            if ($data['customer_source'] === 'existing' && !empty($data['customer_user_id'])) {
                $user = User::find($data['customer_user_id']);
                $customerUserId = $user->id;
                $customerName = $user->name;
            } else {
                // Auto-save manual customer as new User
                $customerName = $data['customer_name'] ?? 'Walk-in Customer';
                $phone = $data['phone'];
                
                // Check if user with this phone already exists to avoid duplicates
                $existingUser = User::where('phone', $phone)->first();
                
                if ($existingUser) {
                    $customerUserId = $existingUser->id;
                    // Update name if needed, or just use existing
                    // $existingUser->update(['name' => $customerName]); 
                } else {
                    // Create new user
                    try {
                        $newUser = User::create([
                            'name' => $customerName,
                            'email' => $phone . '@offline.customer', // Dummy email
                            'phone' => $phone,
                            'password' => bcrypt('password'), // Dummy password
                            'role' => 'customer',
                        ]);
                        $customerUserId = $newUser->id;
                    } catch (\Exception $e) {
                         // Fallback if creation fails (e.g. duplicate email/phone constraints not caught)
                         // Log::error("Failed to auto-create user: " . $e->getMessage());
                         $customerUserId = null;  
                    }
                }
            }

            $serviceId = null; 
            $bundleId = null;
            $subtotal = 0;

            if ($data['order_type'] === 'service') {
                $service = Service::findOrFail($data['service_id']);
                $subtotal = $service->price_per_kg * $data['weight_kg'];
                $serviceId = $service->id;
            } else {
                $bundle = Bundle::findOrFail($data['bundle_id']);
                $subtotal = $bundle->price;
                $bundleId = $bundle->id;
            }

            $discount = 0;
            $promoId = null;
            if (!empty($data['promo_code'])) {
                $promo = Promo::where('code', $data['promo_code'])->first();
                if ($promo) {
                    $promoId = $promo->id;
                     if ($promo->discount_type === 'percent') {
                         $discount = $subtotal * ($promo->value / 100);
                    } else {
                         $discount = $promo->value;
                    }
                    $discount = min($discount, $subtotal);
                }
            }
            
            $pickupFee = 0;
            $totalPrice = $subtotal - $discount;

            $year = date('Y');
            $lastOrder = Order::whereYear('created_at', $year)->orderBy('id', 'desc')->first();
            $lastNumber = $lastOrder ? intval(substr($lastOrder->order_code, -4)) : 0;
            $orderCode = 'LDRY-' . $year . '-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

            $order = Order::create([
                'order_code' => $orderCode,
                'user_id' => auth()->id(),
                'customer_user_id' => $customerUserId,
                'order_source' => 'offline',
                'service_id' => $serviceId,
                'bundle_id' => $bundleId,
                'promo_id' => $promoId,
                'customer_name' => $customerName,
                'phone' => $data['phone'],
                'address' => 'Walk-in (POS)',
                'weight_kg' => ($data['order_type'] === 'service') ? $data['weight_kg'] : 0,
                'payment_method' => $data['payment_method'],
                'pickup_date' => $data['pickup_date'],
                'pickup_time' => $data['pickup_time'],
                'distance_km' => 0,
                'pickup_fee' => 0,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total_price' => $totalPrice,
                'status' => 'process', 
                'description' => $data['notes'] ?? null,
            ]);

            OrderTracking::create([
                'order_id' => $order->id,
                'status' => 'process',
                'description' => 'Order created via POS by Admin',
            ]);

            DB::commit();

            Notification::make()->title('Order Created Successfully!')->success()->send();
            
            $this->form->fill([
                'order_type' => 'service',
                'payment_method' => 'cash',
                'weight_kg' => 1,
                'customer_source' => 'manual',
            ]);
            
            $this->redirect(OrderResource::getUrl('view', ['record' => $order]));

        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()->title('Error: ' . $e->getMessage())->danger()->send();
        }
    }
}
