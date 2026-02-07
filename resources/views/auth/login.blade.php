@extends('layouts.auth')

@section('title', 'Login - VIP Laundry')

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
        
        <!-- Illustration (SVG Washing Machine) -->
        <div class="float-animation relative z-10">
            <svg width="400" height="400" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Washing Machine Body -->
                <rect x="100" y="80" width="200" height="280" rx="20" fill="white" opacity="0.95"/>
                
                <!-- Control Panel Top -->
                <rect x="110" y="90" width="180" height="40" rx="8" fill="#3B82F6" opacity="0.8"/>
                <circle cx="140" cy="110" r="4" fill="white"/>
                <circle cx="160" cy="110" r="4" fill="white"/>
                <circle cx="180" cy="110" r="4" fill="white"/>
                
                <!-- Display Screen -->
                <rect x="210" y="95" width="70" height="30" rx="4" fill="#1E40AF" opacity="0.9"/>
                <text x="245" y="116" fill="white" font-size="12" text-anchor="middle" font-family="monospace">88:88</text>
                
                <!-- Door/Window -->
                <circle cx="200" cy="220" r="80" fill="#60A5FA" opacity="0.3"/>
                <circle cx="200" cy="220" r="70" fill="#3B82F6" opacity="0.2"/>
                <circle cx="200" cy="220" r="60" fill="white" opacity="0.4"/>
                
                <!-- Door Glass Effect -->
                <circle cx="200" cy="220" r="65" fill="none" stroke="white" stroke-width="4" opacity="0.6"/>
                
                <!-- Water/Bubbles inside -->
                <circle cx="180" cy="200" r="8" fill="white" opacity="0.7">
                    <animate attributeName="r" values="8;10;8" dur="2s" repeatCount="indefinite"/>
                    <animate attributeName="opacity" values="0.7;0.4;0.7" dur="2s" repeatCount="indefinite"/>
                </circle>
                <circle cx="220" cy="210" r="6" fill="white" opacity="0.7">
                    <animate attributeName="r" values="6;8;6" dur="2.5s" repeatCount="indefinite"/>
                    <animate attributeName="opacity" values="0.7;0.3;0.7" dur="2.5s" repeatCount="indefinite"/>
                </circle>
                <circle cx="195" cy="235" r="7" fill="white" opacity="0.7">
                    <animate attributeName="r" values="7;9;7" dur="3s" repeatCount="indefinite"/>
                    <animate attributeName="opacity" values="0.7;0.4;0.7" dur="3s" repeatCount="indefinite"/>
                </circle>
                
                <!-- Bottom Drawer -->
                <rect x="120" y="320" width="160" height="30" rx="6" fill="#E0E7FF" opacity="0.8"/>
                <circle cx="200" cy="335" r="3" fill="#3B82F6"/>
                
                <!-- Clothes Basket -->
                <ellipse cx="320" cy="300" rx="30" ry="15" fill="#FCD34D" opacity="0.8"/>
                <rect x="290" y="280" width="60" height="20" fill="#FCD34D" opacity="0.8"/>
                <path d="M 295 280 Q 300 270 305 280" fill="none" stroke="#3B82F6" stroke-width="2"/>
                <path d="M 310 280 Q 315 275 320 280" fill="none" stroke="#EC4899" stroke-width="2"/>
                <path d="M 325 280 Q 330 270 335 280" fill="none" stroke="#10B981" stroke-width="2"/>
            </svg>
        </div>
        
        <!-- Brand Text -->
        <div class="text-center relative z-10 mt-8">
            <h1 class="text-5xl font-black text-white mb-3">VIP Laundry</h1>
            <p class="text-xl text-blue-100">Your Premium Laundry Service</p>
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
                <h2 class="text-3xl font-black text-gray-900">Welcome to</h2>
                <h1 class="text-4xl font-black bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">VIP Laundry</h1>
            </div>
            
            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                    </div>
                </div>
            @endif
            
            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                
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
                               placeholder="Enter your password">
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" id="remember_me" name="remember" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <span class="ml-2 text-sm text-gray-700">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">Forgot password?</a>
                    @endif
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]">
                    LOG IN
                </button>
            </form>
            
            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500">or Log in with</span>
                </div>
            </div>
            
            <!-- Social Login (Optional) -->
            <div class="grid grid-cols-2 gap-4">
                <button type="button" class="flex items-center justify-center gap-2 bg-white border-2 border-gray-200 hover:border-gray-300 text-gray-700 font-medium py-3 px-4 rounded-xl transition-all">
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span>Google</span>
                </button>
                <button type="button" class="flex items-center justify-center gap-2 bg-white border-2 border-gray-200 hover:border-gray-300 text-gray-700 font-medium py-3 px-4 rounded-xl transition-all">
                    <svg class="w-5 h-5" fill="#1877F2" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span>Facebook</span>
                </button>
            </div>
            
            <!-- Register Link -->
            @if (Route::has('register'))
                <p class="mt-8 text-center text-sm text-gray-600">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-700">Create my VIP Laundry account!</a>
                </p>
            @endif
            
            <!-- Back to Home -->
            <div class="mt-6 text-center">
                <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back to Home</a>
            </div>
        </div>
    </div>
</div>
@endsection
