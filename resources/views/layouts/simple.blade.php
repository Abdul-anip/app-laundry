<!DOCTYPE html>
<html lang="en" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VIP Laundry')</title>
    
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- App Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="h-full">
    <div class="min-h-full flex">
        
        <!-- Left Side: Image (Hidden on Mobile) -->
        <div class="hidden lg:block relative w-0 flex-1">
            <img class="absolute inset-0 h-full w-full object-cover" src="https://images.unsplash.com/photo-1545173168-9f1947eebb8f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80" alt="Laundry Background">
            <div class="absolute inset-0 bg-primary-600 mix-blend-multiply opacity-40"></div>
            <div class="absolute inset-0 flex flex-col justify-end p-12 text-white">
                <h2 class="text-4xl font-bold mb-4">VIP Laundry</h2>
                <p class="text-lg opacity-90">Experience the best laundry service in town. Fast, clean, and fragrant.</p>
            </div>
        </div>

        <!-- Right Side: Content -->
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-white w-full lg:w-[600px]">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                @yield('content')
            </div>
        </div>

    </div>
    
    @stack('scripts')
</body>
</html>
