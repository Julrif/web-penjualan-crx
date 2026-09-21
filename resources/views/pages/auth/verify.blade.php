@extends('layouts.app')
@section('content')

<div class="min-h-screen flex items-center justify-center bg-white py-12 px-4 sm:px-6 lg:px-8">
    
    <div class="max-w-md w-full">
        <div class="border border-gray-200 p-10 sm:p-12 text-center">
            
            <!-- Icon -->
            <div class="mx-auto w-16 h-16 border-2 border-black flex items-center justify-center mb-8">
                <i class="bi bi-envelope text-black text-2xl"></i>
            </div>

            <!-- Title -->
            <p class="text-[11px] tracking-[0.3em] uppercase text-gray-500 mb-3">
                Verification
            </p>
            <h2 class="text-2xl font-black text-black tracking-tight uppercase mb-4">
                Check Your Email
            </h2>
            <p class="text-[13px] text-gray-600 leading-relaxed mb-8">
                We've sent a verification link to your email address. Please check your inbox and click the link to verify your account.
            </p>

            <!-- Messages -->
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-[12px] tracking-wider uppercase mb-6">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-[12px] tracking-wider uppercase mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Actions -->
            <div class="space-y-4">
                
                <!-- Resend -->
                <form action="{{ route('verification.resend') }}" method="POST">
                    @csrf
                    <button type="submit" 
                            class="w-full bg-black text-white py-4 text-[12px] font-medium tracking-[0.3em] uppercase hover:bg-white hover:text-black border border-black transition-all duration-300">
                        Resend Verification Email
                    </button>
                </form>

                <!-- Divider -->
                <div class="flex items-center gap-4 pt-4">
                    <div class="flex-1 border-t border-gray-200"></div>
                    <span class="text-[10px] tracking-[0.2em] uppercase text-gray-400">OR</span>
                    <div class="flex-1 border-t border-gray-200"></div>
                </div>

                <!-- Logout -->
                <a href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="block w-full text-center border border-gray-300 text-black py-4 text-[12px] font-medium tracking-[0.3em] uppercase hover:border-black transition-all duration-300">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>

        </div>

        <!-- Footer Info -->
        <p class="text-center text-[11px] tracking-wider uppercase text-gray-400 mt-6">
            Didn't receive the email? Check your spam folder.
        </p>
    </div>
</div>

@endsection