@extends('layouts.app')
@section('content')

<div class="min-h-screen bg-white">
    <x-navbar />

    <!-- ============================================ -->
    <!-- HEADER -->
    <!-- ============================================ -->
    <div class="pt-24 lg:pt-32 border-b border-gray-200">
        <div class="max-w-[1600px] mx-auto px-6 lg:px-12">
            <div class="flex items-center justify-between py-6">
                <div>
                    <p class="text-[11px] tracking-[0.3em] uppercase text-gray-500 mb-2">
                        Account
                    </p>
                    <h1 class="text-3xl lg:text-5xl font-black text-black tracking-tight uppercase leading-none">
                        Settings
                    </h1>
                </div>
                <a href="{{ route('user.profile') }}" 
                   class="text-[11px] tracking-[0.2em] uppercase text-black border-b border-black pb-1 hover:opacity-60 transition-opacity">
                    Back to Profile
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-[1600px] mx-auto px-6 lg:px-12 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
            
            <!-- ============================================ -->
            <!-- SIDEBAR -->
            <!-- ============================================ -->
            <div class="lg:col-span-1">
                <div class="sticky top-32">
                    <div class="border-t border-gray-200">
                        <button onclick="showTab('profile')" id="tab-profile"
                                class="w-full text-left py-4 px-1 border-b border-gray-200 text-[11px] tracking-[0.2em] uppercase transition-colors text-black">
                            My Profile
                        </button>
                        <button onclick="showTab('delivery')" id="tab-delivery"
                                class="w-full text-left py-4 px-1 border-b border-gray-200 text-[11px] tracking-[0.2em] uppercase transition-colors text-gray-400 hover:text-black">
                            Delivery Info
                        </button>
                        <button onclick="showTab('account')" id="tab-account"
                                class="w-full text-left py-4 px-1 border-b border-gray-200 text-[11px] tracking-[0.2em] uppercase transition-colors text-gray-400 hover:text-black">
                            Account Information
                        </button>
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="block py-4 px-1 text-[11px] tracking-[0.2em] uppercase transition-colors text-gray-400 hover:text-red-500">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>

            <!-- ============================================ -->
            <!-- CONTENT -->
            <!-- ============================================ -->
            <div class="lg:col-span-3">
                
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-[12px] tracking-wider uppercase mb-8">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-[12px] tracking-wider uppercase mb-8">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- ========================================== -->
                <!-- TAB 1: MY PROFILE -->
                <!-- ========================================== -->
                <div id="content-profile">
                    <div class="pb-4 mb-8 border-b border-gray-200">
                        <h2 class="text-2xl font-black text-black tracking-tight uppercase">
                            My Profile Info
                        </h2>
                    </div>

                    <form action="{{ route('user.profile.update') }}" method="POST" class="space-y-6 max-w-2xl">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                                Name *
                            </label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                   class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black transition-colors"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-[11px] tracking-wider uppercase mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                                Email *
                            </label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                   class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black transition-colors"
                                   required>
                            @error('email')
                                <p class="text-red-500 text-[11px] tracking-wider uppercase mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                                Birthday
                            </label>
                            <input type="date" name="birthday" value="{{ old('birthday', $user->birthday ?? '') }}" 
                                   class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black transition-colors">
                            @error('birthday')
                                <p class="text-red-500 text-[11px] tracking-wider uppercase mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" 
                                class="bg-black text-white px-10 py-4 text-[12px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all duration-300">
                            Save Changes
                        </button>
                    </form>
                </div>

                <!-- ========================================== -->
                <!-- TAB 2: DELIVERY INFO -->
                <!-- ========================================== -->
                <div id="content-delivery" class="hidden">
                    <div class="flex items-end justify-between pb-4 mb-8 border-b border-gray-200">
                        <h2 class="text-2xl font-black text-black tracking-tight uppercase">
                            My Addresses
                        </h2>
                        <button onclick="openAddAddress()" 
                                class="text-[11px] tracking-[0.2em] uppercase text-black border-b border-black pb-1 hover:opacity-60 transition-opacity">
                            + Add Address
                        </button>
                    </div>

                    @if($addresses->isEmpty())
                        <div class="text-center py-20">
                            <i class="bi bi-geo-alt text-5xl text-gray-300 block mb-6"></i>
                            <p class="text-[13px] tracking-[0.2em] uppercase text-black mb-3">
                                No addresses yet
                            </p>
                            <p class="text-[12px] text-gray-500 mb-8">
                                Add your shipping address
                            </p>
                            <button onclick="openAddAddress()" 
                                    class="inline-block bg-black text-white px-10 py-4 text-[12px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all duration-300">
                                Add Address
                            </button>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($addresses as $address)
                                <div class="border {{ $address->is_default ? 'border-black' : 'border-gray-200' }} p-6">
                                    <div class="flex items-start justify-between gap-6">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-3">
                                                <span class="text-[11px] font-bold tracking-[0.2em] uppercase text-black">
                                                    {{ $address->label }}
                                                </span>
                                                @if($address->is_default)
                                                    <span class="text-[10px] tracking-[0.15em] uppercase font-medium bg-black text-white px-2 py-0.5">
                                                        Default
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-[13px] text-black mb-1">{{ $address->address }}</p>
                                            @if($address->city || $address->province)
                                                <p class="text-[12px] text-gray-500">
                                                    {{ $address->city }}{{ $address->city && $address->province ? ', ' : '' }}{{ $address->province }}
                                                    @if($address->postal_code) — {{ $address->postal_code }} @endif
                                                </p>
                                            @endif
                                            <p class="text-[12px] text-gray-500 mt-1">Phone: {{ $address->phone }}</p>
                                        </div>
                                        <div class="flex flex-col gap-2 items-end">
                                            @if(!$address->is_default)
                                                <form action="{{ route('user.profile.address.default', $address->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="text-[11px] tracking-wider uppercase text-black hover:opacity-60 transition-opacity">
                                                        Set Default
                                                    </button>
                                                </form>
                                            @endif
                                            <button onclick="editAddress({{ $address->id }})" 
                                                    class="text-[11px] tracking-wider uppercase text-black hover:opacity-60 transition-opacity">
                                                Edit
                                            </button>
                                            <form action="{{ route('user.profile.address.delete', $address->id) }}" method="POST" 
                                                  onsubmit="return confirm('Delete this address?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-[11px] tracking-wider uppercase text-red-500 hover:opacity-60 transition-opacity">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- ========================================== -->
                <!-- TAB 3: ACCOUNT INFORMATION -->
                <!-- ========================================== -->
                <div id="content-account" class="hidden">
                    <div class="pb-4 mb-8 border-b border-gray-200">
                        <h2 class="text-2xl font-black text-black tracking-tight uppercase">
                            Change Password
                        </h2>
                    </div>

                    <form action="{{ route('user.profile.update.password') }}" method="POST" class="space-y-6 max-w-2xl">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                                Current Password *
                            </label>
                            <input type="password" name="current_password" 
                                   class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black transition-colors"
                                   required>
                            @error('current_password')
                                <p class="text-red-500 text-[11px] tracking-wider uppercase mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                                New Password *
                            </label>
                            <input type="password" name="new_password" 
                                   class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black transition-colors"
                                   required>
                            @error('new_password')
                                <p class="text-red-500 text-[11px] tracking-wider uppercase mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">
                                Confirm New Password *
                            </label>
                            <input type="password" name="new_password_confirmation" 
                                   class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black transition-colors"
                                   required>
                        </div>

                        <button type="submit" 
                                class="bg-black text-white px-10 py-4 text-[12px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all duration-300">
                            Update Password
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL ADDRESS -->
<!-- ============================================ -->
<div id="addressModal" class="fixed inset-0 z-[9999] hidden">
    <div class="absolute inset-0 bg-black/50" onclick="closeAddressModal()"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white w-full max-w-lg border border-black" onclick="event.stopPropagation()">
            
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-black">
                <h3 class="text-[12px] font-bold tracking-[0.2em] uppercase text-black" id="addressModalTitle">
                    Add Address
                </h3>
                <button onclick="closeAddressModal()" class="text-black text-xl">
                    <i class="bi bi-x"></i>
                </button>
            </div>

            <!-- Form -->
            <div class="p-6">
                <form id="addressForm" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" id="address_method" name="_method" value="POST">
                    
                    <div>
                        <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">Label *</label>
                        <select name="label" id="address_label" class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black">
                            <option value="Rumah">Rumah</option>
                            <option value="Kantor">Kantor</option>
                            <option value="Kost">Kost</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">Full Address *</label>
                        <textarea name="address" id="address_address" rows="3" 
                                  class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black resize-none"
                                  placeholder="Street, RT/RW, Kelurahan, Kecamatan"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">City</label>
                            <input type="text" name="city" id="address_city" 
                                   class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black">
                        </div>
                        <div>
                            <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">Province</label>
                            <input type="text" name="province" id="address_province" 
                                   class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">Postal Code</label>
                            <input type="text" name="postal_code" id="address_postal_code" 
                                   class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black">
                        </div>
                        <div>
                            <label class="block text-[11px] tracking-[0.15em] uppercase text-gray-500 mb-2">Phone *</label>
                            <input type="text" name="phone" id="address_phone" 
                                   class="w-full border border-gray-300 px-4 py-3 text-[13px] text-black focus:outline-none focus:border-black"
                                   placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_default" id="address_is_default" value="1" class="accent-black">
                        <label class="text-[11px] tracking-wider uppercase text-gray-500">Set as default address</label>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeAddressModal()" 
                                class="flex-1 border border-gray-300 text-black py-3 text-[11px] font-medium tracking-[0.2em] uppercase hover:border-black transition-all">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="flex-1 bg-black text-white py-3 text-[11px] font-medium tracking-[0.2em] uppercase hover:bg-white hover:text-black border border-black transition-all">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// ============================================
// TAB SWITCHER
// ============================================
function showTab(tab) {
    // Hide all
    document.getElementById('content-profile').classList.add('hidden');
    document.getElementById('content-delivery').classList.add('hidden');
    document.getElementById('content-account').classList.add('hidden');
    
    // Reset all tabs
    document.getElementById('tab-profile').className = 'w-full text-left py-4 px-1 border-b border-gray-200 text-[11px] tracking-[0.2em] uppercase transition-colors text-gray-400 hover:text-black';
    document.getElementById('tab-delivery').className = 'w-full text-left py-4 px-1 border-b border-gray-200 text-[11px] tracking-[0.2em] uppercase transition-colors text-gray-400 hover:text-black';
    document.getElementById('tab-account').className = 'w-full text-left py-4 px-1 border-b border-gray-200 text-[11px] tracking-[0.2em] uppercase transition-colors text-gray-400 hover:text-black';
    
    // Show selected
    if (tab === 'profile') {
        document.getElementById('content-profile').classList.remove('hidden');
        document.getElementById('tab-profile').className = 'w-full text-left py-4 px-1 border-b border-gray-200 text-[11px] tracking-[0.2em] uppercase transition-colors text-black font-bold';
    } else if (tab === 'delivery') {
        document.getElementById('content-delivery').classList.remove('hidden');
        document.getElementById('tab-delivery').className = 'w-full text-left py-4 px-1 border-b border-gray-200 text-[11px] tracking-[0.2em] uppercase transition-colors text-black font-bold';
    } else if (tab === 'account') {
        document.getElementById('content-account').classList.remove('hidden');
        document.getElementById('tab-account').className = 'w-full text-left py-4 px-1 border-b border-gray-200 text-[11px] tracking-[0.2em] uppercase transition-colors text-black font-bold';
    }
}

// ============================================
// ADDRESS MODAL
// ============================================
function openAddAddress() {
    const modal = document.getElementById('addressModal');
    const form = document.getElementById('addressForm');
    const title = document.getElementById('addressModalTitle');
    
    form.action = "{{ route('user.profile.address.store') }}";
    document.getElementById('address_method').value = 'POST';
    title.textContent = 'Add Address';
    
    document.getElementById('address_label').value = 'Rumah';
    document.getElementById('address_address').value = '';
    document.getElementById('address_city').value = '';
    document.getElementById('address_province').value = '';
    document.getElementById('address_postal_code').value = '';
    document.getElementById('address_phone').value = '';
    document.getElementById('address_is_default').checked = false;
    
    modal.classList.remove('hidden');
}

function editAddress(id) {
    const modal = document.getElementById('addressModal');
    const form = document.getElementById('addressForm');
    const title = document.getElementById('addressModalTitle');
    
    fetch(`/profile/address/${id}/edit`)
        .then(response => response.json())
        .then(data => {
            form.action = `/profile/address/${id}`;
            document.getElementById('address_method').value = 'PUT';
            title.textContent = 'Edit Address';
            
            document.getElementById('address_label').value = data.label;
            document.getElementById('address_address').value = data.address;
            document.getElementById('address_city').value = data.city || '';
            document.getElementById('address_province').value = data.province || '';
            document.getElementById('address_postal_code').value = data.postal_code || '';
            document.getElementById('address_phone').value = data.phone || '';
            document.getElementById('address_is_default').checked = data.is_default ? true : false;
            
            modal.classList.remove('hidden');
        })
        .catch(error => {
            alert('Failed to load address');
        });
}

function closeAddressModal() {
    document.getElementById('addressModal').classList.add('hidden');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddressModal();
    }
});

// ============================================
// INIT
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    showTab('profile');
});
</script>

@endsection