<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VIP Laundry')</title>
    
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- App CSS -->
    <!-- App Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
<body style="background: #F3F4F6; margin: 0; font-family: 'Inter', sans-serif;">
    <style>
        /* Variables */
        :root {
            --primary: #3B82F6;
            --primary-hover: #2563EB;
            --text-primary: #1F2937;
            --text-secondary: #6B7280;
            --border: #E5E7EB;
            --danger: #EF4444;
        }

        /* Centered Layout (Login/Register) */
        .centered-layout {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .container-sm {
            width: 100%;
            max-width: 420px;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .card-header { text-align: center; margin-bottom: 2rem; }
        .card-title { font-size: 1.75rem; font-weight: 700; color: var(--text-primary); margin: 0 0 0.5rem 0; }
        .card-subtitle { color: var(--text-secondary); margin: 0; }

        /* Forms */
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-weight: 500; margin-bottom: 0.5rem; color: var(--text-primary); }
        .form-input { 
            width: 100%; 
            padding: 0.75rem 1rem; 
            border: 1px solid var(--border); 
            border-radius: 8px; 
            font-size: 1rem; 
            transition: all 0.2s;
            box-sizing: border-box; /* Fix padding issue */
        }
        .form-input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }

        /* Buttons */
        .btn { 
            display: inline-block; 
            padding: 0.75rem 1.5rem; 
            border-radius: 8px; 
            font-weight: 600; 
            text-align: center; 
            cursor: pointer; 
            transition: all 0.2s; 
            border: none;
            text-decoration: none;
        }
        .btn-block { display: block; width: 100%; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-hover); transform: translateY(-1px); }

        /* Alerts */
        .alert { padding: 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.875rem; }
        .alert-success { background: #ECFDF5; color: #065F46; border: 1px solid #A7F3D0; }
        .alert-error { background: #FEF2F2; color: #991B1B; border: 1px solid #FECACA; }
    </style>
    @stack('styles')
</head>
<body>
    @yield('content')
    
    @stack('scripts')
</body>
</html>
