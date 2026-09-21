@extends('layouts.app')
@section('content')

<div class="min-h-screen bg-white">
    <x-navbar />

    <!-- ============================================ -->
    <!-- HEADER -->
    <!-- ============================================ -->
    <div class="pt-24 lg:pt-32 border-b border-gray-200">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12">
            <div class="py-6">
                <p class="text-[11px] tracking-[0.3em] uppercase text-gray-500 mb-2">
                    Final Step
                </p>
                <h1 class="text-3xl lg:text-5xl font-black text-black tracking-tight uppercase leading-none">
                    Checkout
                </h1>
            </div>
        </div>
    </div>

    <div class="max-w-[1600px] mx-auto px-6 lg:px-12 py-12">

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-[12px] tracking-wider uppercase mb-8">
                {{ session('error') }}
            </div>
        @endif

        <form id="checkoutForm" action="{{ route('checkout.process') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                <!-- ============================================ -->
                <!-- LEFT: FORM -->
                <!-- ============================================ -->
                <div class="lg:col-span-2 space-y-12">

                    <!-- SECTION 1: SHIPPING ADDRESS -->
                    <div>
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                            <span class="w-6 h-6 bg-black text-white text-[11px] font-bold flex items-center justify-center">1</span>
                            <h2 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">
                                Shipping Address
                            </h2>
                        </div>
                        
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                                    Full Address *
                                </label>
                                <textarea name="address" rows="3" 
                                        class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black transition-colors resize-none"
                                        placeholder="Street, RT/RW, Kelurahan, Kecamatan"
                                        required>{{ old('address', $defaultAddress->address ?? '') }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                                        City
                                    </label>
                                    <input type="text" name="city" 
                                        class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black transition-colors"
                                        value="{{ old('city', $defaultAddress->city ?? '') }}">
                                </div>
                                <div>
                                    <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                                        Province
                                    </label>
                                    <input type="text" name="province" 
                                        class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black transition-colors"
                                        value="{{ old('province', $defaultAddress->province ?? '') }}">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                                        Postal Code
                                    </label>
                                    <input type="text" name="postal_code" 
                                        class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black transition-colors"
                                        value="{{ old('postal_code', $defaultAddress->postal_code ?? '') }}">
                                </div>
                                <div>
                                    <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                                        Phone Number *
                                    </label>
                                    <input type="text" name="phone" 
                                        class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black transition-colors"
                                        placeholder="08xxxxxxxxxx"
                                        value="{{ old('phone', $defaultAddress->phone ?? '') }}"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: SHIPPING METHOD -->
                    <div>
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                            <span class="w-6 h-6 bg-black text-white text-[11px] font-bold flex items-center justify-center">2</span>
                            <h2 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">
                                Shipping Method
                            </h2>
                        </div>
                        
                        <div class="space-y-3">
                            <label class="flex items-center justify-between p-4 border border-gray-300 hover:border-black transition-colors cursor-pointer group has-[:checked]:border-black has-[:checked]:bg-gray-50">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="shipping_method" value="JNE" 
                                        class="accent-black" checked>
                                    <span class="text-[12px] font-medium tracking-wider uppercase text-black">JNE</span>
                                </div>
                                <span class="text-[12px] text-gray-500 tracking-wider">IDR 20.000</span>
                            </label>
                            
                            <label class="flex items-center justify-between p-4 border border-gray-300 hover:border-black transition-colors cursor-pointer group has-[:checked]:border-black has-[:checked]:bg-gray-50">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="shipping_method" value="JNT" 
                                        class="accent-black">
                                    <span class="text-[12px] font-medium tracking-wider uppercase text-black">J&T</span>
                                </div>
                                <span class="text-[12px] text-gray-500 tracking-wider">IDR 20.000</span>
                            </label>
                        </div>
                    </div>

                    <!-- SECTION 3: PAYMENT METHOD -->
                    <div>
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                            <span class="w-6 h-6 bg-black text-white text-[11px] font-bold flex items-center justify-center">3</span>
                            <h2 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">
                                Payment Method
                            </h2>
                        </div>
                        
                        <div class="space-y-3">
                            <label class="flex items-center justify-between p-4 border border-gray-300 hover:border-black transition-colors cursor-pointer has-[:checked]:border-black has-[:checked]:bg-gray-50">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="manual" 
                                        class="accent-black" checked>
                                    <div>
                                        <p class="text-[12px] font-medium tracking-wider uppercase text-black">Bank Transfer Manual</p>
                                        <p class="text-[11px] text-gray-500 tracking-wider">BCA, BNI, BRI</p>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="flex items-center justify-between p-4 border border-gray-300 hover:border-black transition-colors cursor-pointer has-[:checked]:border-black has-[:checked]:bg-gray-50">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="midtrans" 
                                        class="accent-black">
                                    <div>
                                        <p class="text-[12px] font-medium tracking-wider uppercase text-black">Midtrans</p>
                                        <p class="text-[11px] text-gray-500 tracking-wider">VA, QRIS, E-Wallet — Instant</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- SECTION 4: NOTES -->
                    <div>
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                            <span class="w-6 h-6 bg-white text-black border border-black text-[11px] font-bold flex items-center justify-center">4</span>
                            <h2 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black">
                                Notes <span class="text-gray-400 font-normal">(Optional)</span>
                            </h2>
                        </div>
                        
                        <textarea name="notes" rows="3" 
                                  class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black transition-colors resize-none"
                                  placeholder="Add notes for your order...">{{ old('notes') }}</textarea>
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <div class="pt-4">
                        <button type="submit" id="submitBtn"
                                class="w-full bg-black text-white py-5 text-[12px] font-medium tracking-[0.3em] uppercase hover:bg-white hover:text-black border border-black transition-all duration-300">
                            Place Order
                        </button>
                    </div>

                </div>

                <!-- ============================================ -->
                <!-- RIGHT: ORDER SUMMARY -->
                <!-- ============================================ -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 p-8 sticky top-32">
                        <h3 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black mb-6 pb-4 border-b border-gray-300">
                            Order Summary
                        </h3>
                        
                        <!-- Items -->
                        <div class="space-y-4 max-h-60 overflow-y-auto mb-6 pb-6 border-b border-gray-300">
                            @foreach($cartItems as $item)
                                <div class="flex justify-between items-start text-[12px] gap-4">
                                    <div class="flex-1">
                                        <p class="text-black tracking-wider uppercase leading-tight">
                                            {{ $item->product->name }}
                                        </p>
                                        <p class="text-[11px] text-gray-500 tracking-wider mt-1">
                                            Size: {{ $item->size }} · Qty: {{ $item->quantity }}
                                        </p>
                                    </div>
                                    <span class="text-black font-medium whitespace-nowrap">
                                        IDR {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Summary -->
                        <div class="space-y-3 text-[12px]">
                            <div class="flex justify-between tracking-wider uppercase">
                                <span class="text-gray-500">Subtotal</span>
                                <span class="text-black font-medium">IDR {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between tracking-wider uppercase">
                                <span class="text-gray-500">Shipping</span>
                                <span class="text-black font-medium">IDR {{ number_format($shippingCost, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between tracking-wider uppercase">
                                <span class="text-gray-500">Handling</span>
                                <span class="text-black font-medium">IDR {{ number_format($operationalCost, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="border-t border-gray-300 my-4"></div>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-[13px] font-bold tracking-[0.2em] uppercase text-black">Total</span>
                                <span class="text-[16px] font-bold text-black">IDR {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Info -->
                        <p class="text-[10px] text-gray-500 tracking-wider uppercase text-center mt-6 pt-4 border-t border-gray-300">
                            Secure Checkout
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkoutForm');
    if (!form) return;

    const radioButtons = document.querySelectorAll('input[name="payment_method"]');
    
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'midtrans') {
                form.action = "{{ route('midtrans.checkout') }}";
            } else {
                form.action = "{{ route('checkout.process') }}";
            }
        });
    });

    document.getElementById('submitBtn').addEventListener('click', function(e) {
        const selected = document.querySelector('input[name="payment_method"]:checked');
        
        if (selected && selected.value === 'midtrans') {
            form.action = "{{ route('midtrans.checkout') }}";
        } else {
            form.action = "{{ route('checkout.process') }}";
        }
    });
});
</script>
@endpush

@endsection