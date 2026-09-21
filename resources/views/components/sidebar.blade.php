<!-- ============================================ -->
<!-- SIDEBAR - SNSBWORLD STYLE -->
<!-- ============================================ -->
<aside class="fixed top-0 left-0 h-screen w-64 bg-white border-r border-gray-200 flex flex-col">

    <!-- Logo / Brand -->
    <div class="h-20 flex items-center px-6 border-b border-gray-200">
        <a href="/" class="block">
            <span class="text-xl font-black text-black tracking-tight uppercase">
                {{ config('app.name') }}
            </span>
            <p class="text-[10px] tracking-[0.2em] uppercase text-gray-500 mt-0.5">
                Admin Panel
            </p>
        </a>
    </div>

    <!-- Menu -->
    <nav class="flex-1 overflow-y-auto py-6">
        
        <!-- Section Label -->
        <p class="px-6 mb-3 text-[10px] tracking-[0.25em] uppercase text-gray-400">
            Main Menu
        </p>

        <div class="space-y-1">
            
            <!-- Dashboard -->
            <a href="/dashboard"
               class="flex items-center gap-3 px-6 py-3 text-[12px] tracking-[0.1em] uppercase transition-colors
                      {{ request()->is('dashboard') && !request()->is('dashboard/*') ? 'bg-black text-white' : 'text-gray-600 hover:text-black hover:bg-gray-50' }}">
                <i class="bi bi-speedometer2 text-[14px]"></i>
                <span>Dashboard</span>
            </a>
            
            @if(Auth::user()->role_id == 1)
                
                <!-- Product -->
                <a href="/dashboard/product"
                   class="flex items-center gap-3 px-6 py-3 text-[12px] tracking-[0.1em] uppercase transition-colors
                          {{ request()->is('dashboard/product*') ? 'bg-black text-white' : 'text-gray-600 hover:text-black hover:bg-gray-50' }}">
                    <i class="bi bi-box text-[14px]"></i>
                    <span>Products</span>
                </a>

                <!-- Orders -->
                <a href="/dashboard/orders"
                class="flex items-center gap-3 px-6 py-3 text-[12px] tracking-[0.1em] uppercase transition-colors
                        {{ request()->is('dashboard/orders*') ? 'bg-black text-white' : 'text-gray-600 hover:text-black hover:bg-gray-50' }}">
                    <i class="bi bi-receipt text-[14px]"></i>
                    <span>Orders</span>
                    @php
                        $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
                    @endphp
                    @if($pendingOrders > 0)
                        @if(request()->is('dashboard/orders*'))
                            {{-- Active state: badge putih dengan text hitam --}}
                            <span class="ml-auto bg-white text-black text-[9px] font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                {{ $pendingOrders }}
                            </span>
                        @else
                            {{-- Non-active state: badge hitam dengan text putih --}}
                            <span class="ml-auto bg-black text-white text-[9px] font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                {{ $pendingOrders }}
                            </span>
                        @endif
                    @endif
                </a>

                <!-- Report -->
                <a href="/dashboard/report"
                class="flex items-center gap-3 px-6 py-3 text-[12px] tracking-[0.1em] uppercase transition-colors
                        {{ request()->is('dashboard/report*') ? 'bg-black text-white' : 'text-gray-600 hover:text-black hover:bg-gray-50' }}">
                    <i class="bi bi-bar-chart text-[14px]"></i>
                    <span>Reports</span>
                </a>

            @endif
        </div>

        <!-- Section Label -->
        <p class="px-6 mt-8 mb-3 text-[10px] tracking-[0.25em] uppercase text-gray-400">
            Navigation
        </p>

        <div class="space-y-1">
            
            <!-- Home -->
            <a href="/"
               class="flex items-center gap-3 px-6 py-3 text-[12px] tracking-[0.1em] uppercase text-gray-600 hover:text-black hover:bg-gray-50 transition-colors">
                <i class="bi bi-house text-[14px]"></i>
                <span>Back to Store</span>
            </a>

            <!-- Logout -->
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form-sidebar').submit();"
               class="flex items-center gap-3 px-6 py-3 text-[12px] tracking-[0.1em] uppercase text-gray-600 hover:text-red-500 hover:bg-red-50 transition-colors">
                <i class="bi bi-box-arrow-right text-[14px]"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form-sidebar" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </nav>

    <!-- Footer -->
    <div class="p-6 border-t border-gray-200">
        <p class="text-[10px] tracking-[0.2em] uppercase text-gray-400">
            &copy; {{ date('Y') }} {{ config('app.name') }}
        </p>
        <p class="text-[10px] tracking-[0.2em] uppercase text-gray-400 mt-1">
            v1.0.0
        </p>
    </div>

</aside>