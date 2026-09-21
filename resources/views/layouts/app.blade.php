<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts - Inter (seperti SNSBWORLD) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        /* ============================================
           GLOBAL STYLE - SNSBWORLD STYLE
           ============================================ */
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        body {
            background-color: #ffffff;
            color: #000000;
        }
        
        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom Scrollbar - Minimalis */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f5f5f5;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #000000;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #333333;
        }
        
        /* ============================================
           TYPOGRAPHY - SNSBWORLD STYLE
           ============================================ */
        .font-display {
            font-weight: 900;
            letter-spacing: -0.02em;
        }
        
        .text-uppercase {
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        /* ============================================
           BUTTON STYLE - SNSBWORLD
           ============================================ */
        .btn-primary {
            background-color: #000000;
            color: #ffffff;
            padding: 12px 32px;
            font-weight: 500;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: 1px solid #000000;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #ffffff;
            color: #000000;
        }
        
        .btn-outline {
            background-color: transparent;
            color: #000000;
            padding: 12px 32px;
            font-weight: 500;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: 1px solid #000000;
            transition: all 0.3s ease;
        }
        
        .btn-outline:hover {
            background-color: #000000;
            color: #ffffff;
        }
        
        /* ============================================
           LINK STYLE - SNSBWORLD
           ============================================ */
        a {
            text-decoration: none;
        }
        
        /* ============================================
           FORM STYLE - SNSBWORLD
           ============================================ */
        input, textarea, select {
            border: 1px solid #e5e5e5;
            padding: 12px 16px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            border-radius: 0;
        }
        
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #000000;
        }
        
        /* ============================================
           UTILITY CLASSES
           ============================================ */
        .border-black {
            border-color: #000000;
        }
        
        .bg-black {
            background-color: #000000;
        }
        
        .text-black {
            color: #000000;
        }
        
        .bg-white {
            background-color: #ffffff;
        }
        
        .text-white {
            color: #ffffff;
        }
        
        /* ============================================
           ANIMATIONS
           ============================================ */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        /* ============================================
           NO SHADOW - SNSBWORLD STYLE
           ============================================ */
        .no-shadow {
            box-shadow: none !important;
        }
    </style>
    
    @stack('styles')
</head>
<body class="@yield('body-class')">
    @yield('content')

    @stack('scripts')
</body>
</html>