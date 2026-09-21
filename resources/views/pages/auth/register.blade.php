@extends("layouts.app")

@section('content')
<div class="min-h-screen flex items-center justify-center bg-white py-12 px-4 sm:px-6 lg:px-8">
    
    <div class="max-w-6xl w-full grid grid-cols-1 md:grid-cols-2 gap-16 items-center">
        
        <!-- ============================================ -->
        <!-- LEFT: BRANDING -->
        <!-- ============================================ -->
        <div class="hidden md:block space-y-8">
            
            <!-- Logo -->
            <a href="/">
                <span class="text-4xl lg:text-5xl font-black text-black tracking-tight uppercase">
                    {{ env("APP_NAME") }}
                </span>
            </a>

            <!-- Heading -->
            <div class="space-y-4">
                <p class="text-[11px] tracking-[0.3em] uppercase text-gray-500">
                    Join Us
                </p>
                <h1 class="text-4xl lg:text-5xl font-black text-black tracking-tight uppercase leading-[0.95]">
                    Create<br>
                    Your Account
                </h1>
                <p class="text-[14px] text-gray-600 leading-relaxed max-w-md">
                    Start your shopping journey with exclusive offers, member-only discounts, and personalized recommendations.
                </p>
            </div>

            <!-- Features -->
            <div class="space-y-4 pt-8 border-t border-gray-200">
                <div class="flex items-center gap-3">
                    <i class="bi bi-check-lg text-black text-[14px]"></i>
                    <span class="text-[12px] tracking-wider uppercase text-gray-700">Secure & Encrypted Registration</span>
                </div>
                <div class="flex items-center gap-3">
                    <i class="bi bi-check-lg text-black text-[14px]"></i>
                    <span class="text-[12px] tracking-wider uppercase text-gray-700">Track Your Orders Easily</span>
                </div>
                <div class="flex items-center gap-3">
                    <i class="bi bi-check-lg text-black text-[14px]"></i>
                    <span class="text-[12px] tracking-wider uppercase text-gray-700">Exclusive Member Discounts</span>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- RIGHT: REGISTER FORM -->
        <!-- ============================================ -->
        <div class="w-full max-w-md mx-auto md:mx-0">
            <form action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Mobile Logo -->
                <div class="flex justify-center md:hidden mb-8">
                    <a href="/">
                        <span class="text-3xl font-black text-black tracking-tight uppercase">
                            {{ env("APP_NAME") }}
                        </span>
                    </a>
                </div>

                <!-- Header -->
                <div class="mb-8">
                    <h2 class="text-2xl font-black text-black tracking-tight uppercase mb-2">
                        Create Account
                    </h2>
                    <p class="text-[12px] tracking-wider uppercase text-gray-500">
                        Fill in your details
                    </p>
                </div>

                <!-- Messages -->
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-[12px] tracking-wider uppercase">
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-[12px] tracking-wider">
                        @foreach($errors->all() as $error)
                            <p class="uppercase">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- Full Name -->
                <div>
                    <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                        Full Name *
                    </label>
                    <input name="name" 
                           type="text" 
                           required 
                           value="{{ old('name') }}"
                           class="w-full border border-gray-300 px-4 py-3.5 text-[13px] text-black focus:outline-none focus:border-black transition-colors"
                           placeholder="Your Name">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                        Email Address *
                    </label>
                    <input name="email" 
                           type="email" 
                           required 
                           value="{{ old('email') }}"
                           class="w-full border border-gray-300 px-4 py-3.5 text-[13px] text-black focus:outline-none focus:border-black transition-colors"
                           placeholder="you@example.com">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                        Password *
                    </label>
                    <div class="relative">
                        <input name="password" 
                               id="password-input"
                               type="password" 
                               required 
                               class="w-full border border-gray-300 px-4 py-3.5 text-[13px] text-black focus:outline-none focus:border-black transition-colors pr-12"
                               placeholder="Min. 6 characters">
                        <button type="button" 
                                onclick="togglePassword('password-input', this)"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-black transition-colors">
                            <i class="bi bi-eye text-[16px]"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                        Confirm Password *
                    </label>
                    <div class="relative">
                        <input name="password_confirmation" 
                               id="password-confirm-input"
                               type="password" 
                               required 
                               class="w-full border border-gray-300 px-4 py-3.5 text-[13px] text-black focus:outline-none focus:border-black transition-colors pr-12"
                               placeholder="Repeat password">
                        <button type="button" 
                                onclick="togglePassword('password-confirm-input', this)"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-black transition-colors">
                            <i class="bi bi-eye text-[16px]"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" 
                        class="w-full bg-black text-white py-4 text-[12px] font-medium tracking-[0.3em] uppercase hover:bg-white hover:text-black border border-black transition-all duration-300">
                    Create Account
                </button>

                <!-- Login Link -->
                <div class="text-center pt-4 border-t border-gray-200">
                    <p class="text-[12px] tracking-wider uppercase text-gray-500">
                        Already have an account?
                        <a href="{{ route('login') }}" 
                           class="text-black font-medium border-b border-black pb-0.5 hover:opacity-60 transition-opacity ml-1">
                            Sign In
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endpush

@endsection