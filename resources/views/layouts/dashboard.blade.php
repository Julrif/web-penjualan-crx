<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} — Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts - Inter (seperti SNSBWORLD) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        /* ============================================
           GLOBAL STYLE - SNSBWORLD (Dashboard)
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
           TYPOGRAPHY
           ============================================ */
        .font-display {
            font-weight: 900;
            letter-spacing: -0.02em;
        }
        
        /* ============================================
           FORM STYLE
           ============================================ */
        input, textarea, select {
            border: 1px solid #e5e5e5;
            padding: 12px 16px;
            font-size: 13px;
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
        SEARCH INPUT - FIX ICON PADDING
        ============================================ */
        .search-input-wrap {
            position: relative;
        }

        .search-input-wrap i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 14px;
            pointer-events: none;
        }

        .search-input-wrap input {
            padding-left: 44px !important;
            width: 100%;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    @yield('content')

    {{-- Script --}}
    @stack('scripts')
</body>
</html>