@extends('layouts.auth')

@section('title', 'Register - VIP Laundry')

@push('styles')
<style>
    .auth-container {
        min-height: 100vh;
        display: flex;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }
    
    .illustration-panel {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }
    
    .float-animation {
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    
    .bubble {
        position: absolute;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: bubble-rise 4s ease-in infinite;
    }
    
    @keyframes bubble-rise {
        0% {
            transform: translateY(0) scale(1);
            opacity: 0.7;
        }
        100% {
            transform: translateY(-100vh) scale(0.5);
            opacity: 0;
        }
    }
    
    .input-icon {
        pointer-events: none;
    }
</style>
@endpush

@section('content')
<div class="auth-container">
    <!-- Left Panel - Illustration -->
    <div class="illustration-panel hidden lg:flex lg:w-1/2 flex-col items-center justify-center p-12 relative">
        <!-- Decorative Bubbles -->
        <div class="bubble" style="width: 60px; height: 60px; left: 20%; top: 20%; animation-delay: 0s;"></div>
        <div class="bubble" style="width: 40px; height: 40px; left: 70%; top: 40%; animation-delay: 1s;"></div>
        <div class="bubble" style="width: 50px; height: 50px; left: 40%; top: 60%; animation-delay: 2s;"></div>
        <div class="bubble" style="width: 35px; height: 35px; left: 80%; top: 70%; animation-delay: 1.5s;"></div>
        
        <!-- Illustration (SVG Clothes & Hangers) -->
        <div class="float-animation relative z-10">
            <svg width="400" height="400" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Clothesline -->
                <line x1="50" y1="100" x2="350" y2="100" stroke="white" stroke-width="4" opacity="0.8"/>
                
                <!-- Hanger 1 - Shirt -->
                <g transform="translate(120, 100)">
                    <!-- Hanger -->
                    <path d="M -20 0 L 0 -15 L 20 0" stroke="white" stroke-width="3" fill="none" opacity="0.9"/>
                    <!-- Shirt -->
                    <rect x="-25" y="0" width="50" height="70" rx="5" fill="#60A5FA" opacity="0.9"/>
                    <rect x="-35" y="5" width="20" height="40" rx="3" fill="#60A5FA" opacity="0.9"/>
                    <rect x="15" y="5" width="20" height="40" rx="3" fill="#60A5FA" opacity="0.9"/>
                    <circle cx="0" cy="25" r="3" fill="white" opacity="0.8"/>
                    <circle cx="0" cy="35" r="3" fill="white" opacity="0.8"/>
                </g>
                
                <!-- Hanger 2 - Dress -->
                <g transform="translate(200, 100)">
                    <!-- Hanger -->
                    <path d="M -20 0 L 0 -15 L 20 0" stroke="white" stroke-width="3" fill="none" opacity="0.9"/>
                    <!-- Dress -->
                    <path d="M -20 0 L -25 60 L -35 100 L 35 100 L 25 60 L 20 0 Z" fill="#EC4899" opacity="0.9"/>
                    <rect x="-30" y="5" width="15" height="30" rx="3" fill="#EC4899" opacity="0.9"/>
                    <rect x="15" y="5" width="15" height="30" rx="3" fill="#EC4899" opacity="0.9"/>
                </g>
                
                <!-- Hanger 3 - Pants -->
                <g transform="translate(280, 100)">
                    <!-- Hanger -->
                    <path d="M -20 0 L 0 -15 L 20 0" stroke="white" stroke-width="3" fill="none" opacity="0.9"/>
                    <!-- Pants -->
                    <rect x="-20" y="0" width="18" height="80" rx="4" fill="#10B981" opacity="0.9"/>
                    <rect x="2" y="0" width="18" height="80" rx="4" fill="#10B981" opacity="0.9"/>
                    <rect x="-22" y="0" width="44" height="20" rx="4" fill="#10B981" opacity="0.9"/>
                </g>
                
                <!-- Washing Basket -->
                <g transform="translate(200, 280)">
                    <ellipse cx="0" cy="0" rx="60" ry="20" fill="#FCD34D" opacity="0.8"/>
                    <path d="M -60 0 L -50 -40 L 50 -40 L 60 0" fill="#FCD34D" opacity="0.8"/>
                    <!-- Clothes in basket -->
                    <path d="M -30 -30 Q -20 -40 -10 -30" stroke="#3B82F6" stroke-width="3" fill="none"/>
                    <path d="M 0 -30 Q 10 -35 20 -30" stroke="#EC4899" stroke-width="3" fill="none"/>
                    <circle cx="-20" cy="-15" r="5" fill="#10B981" opacity="0.7"/>
                    <circle cx="15" cy="-18" r="4" fill="#60A5FA" opacity="0.7"/>
                </g>
                
                <!-- Bubbles -->
                <circle cx="80" cy="180" r="12" fill="white" opacity="0.6">
                    <animate attributeName="r" values="12;15;12" dur="2s" repeatCount="indefinite"/>
                </circle>
                <circle cx="320" cy="200" r="10" fill="white" opacity="0.6">
                    <animate attributeName="r" values="10;13;10" dur="2.5s" repeatCount="indefinite"/>
                </circle>
                <circle cx="150" cy="240" r="8" fill="white" opacity="0.6">
                    <animate attributeName="r" values="8;11;8" dur="3s" repeatCount="indefinite"/>
                </circle>
            </svg>
        </div>
        
        <!-- Brand Text -->
        <div class="text-center relative z-10 mt-8">
            <h1 class="text-5xl font-black text-white mb-3">VIP Laundry</h1>
            <p class="text-xl text-blue-100">Join Our Premium Service</p>
            <p class="text-sm text-blue-200 mt-2">Fast • Clean • Fragrant</p>
        </div>
    </div>
    
    <!-- Right Panel - Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white">
        <div class="w-full max-w-md">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full mb-4 lg:hidden">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-black text-gray-900">Create Account</h2>
                <h1 class="text-4xl font-black bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">VIP Laundry</h1>
            </div>
            
            <!-- Register Form -->
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Name Input -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center input-icon">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="block w-full pl-12 pr-4 py-3.5 text-gray-900 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="Enter your full name">
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center input-icon">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="block w-full pl-12 pr-4 py-3.5 text-gray-900 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="Enter your email">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Phone Input -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center input-icon">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                        </div>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                               class="block w-full pl-12 pr-4 py-3.5 text-gray-900 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="Enter your phone number">
                    </div>
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center input-icon">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required
                               class="block w-full pl-12 pr-4 py-3.5 text-gray-900 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="Create a password">
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Confirm Password Input -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center input-icon">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="block w-full pl-12 pr-4 py-3.5 text-gray-900 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                               placeholder="Confirm your password">
                    </div>
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02] mt-6">
                    CREATE ACCOUNT
                </button>
            </form>
            

            
            <!-- Login Link -->
            <p class="mt-8 text-center text-sm text-gray-600">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-bold text-blue-600 hover:text-blue-700">Log in here!</a>
            </p>
            
            <!-- Back to Home -->
            <div class="mt-6 text-center">
                <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back to Home</a>
            </div>
        </div>
    </div>
</div>
@endsection
