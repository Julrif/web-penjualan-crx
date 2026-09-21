<!-- NAVBAR - SNSBWORLD STYLE -->
<nav class="fixed top-0 left-0 right-0 z-[9999] transition-all duration-300" id="navbar">
  <div class="max-w-[1600px] mx-auto">
    <div class="px-6 lg:px-12">
      <div class="flex justify-between items-center h-16 lg:h-20">
        
        <!-- LEFT: MENU (Desktop) -->
        <div class="hidden lg:flex items-center space-x-8">
          <a href="/" class="nav-link text-[13px] font-medium tracking-wider uppercase transition-colors">
            Home
          </a>
          <a href="/products" class="nav-link text-[13px] font-medium tracking-wider uppercase transition-colors">
            Shop
          </a>
          <a href="/about" class="nav-link text-[13px] font-medium tracking-wider uppercase transition-colors">
            Lookbooks
          </a>
        </div>

        <!-- MOBILE MENU BUTTON (Left) -->
        <button id="menu-btn" class="lg:hidden w-8 h-8 flex flex-col items-start justify-center gap-1.5 focus:outline-none">
          <span class="nav-line block w-6 h-[1.5px] transition-all duration-300" id="line1"></span>
          <span class="nav-line block w-6 h-[1.5px] transition-all duration-300" id="line2"></span>
        </button>

        <!-- CENTER: LOGO -->
        <div class="absolute left-1/2 transform -translate-x-1/2">
          <a href="/" class="block">
            <span class="nav-logo text-2xl lg:text-3xl font-black tracking-tight transition-colors">
              {{ env("APP_NAME") }}
            </span>
          </a>
        </div>

        <!-- RIGHT: ICONS -->
        <div class="flex items-center space-x-5">
          
          @auth
            @if(Auth::user()->role_id == 1)
              <a href="/dashboard" class="nav-icon transition-colors" title="Dashboard">
                <i class="bi bi-speedometer2 text-[18px]"></i>
              </a>
              <a href="{{ route('logout') }}" class="nav-icon transition-colors" title="Logout">
                <i class="bi bi-box-arrow-right text-[18px]"></i>
              </a>
            @else
              <!-- User Dropdown -->
              <div class="relative" id="user-dropdown">
                <button onclick="toggleDropdown()" class="nav-icon transition-colors flex items-center">
                  <i class="bi bi-person text-[18px]"></i>
                </button>
                
                <div id="dropdown-menu" 
                     class="hidden absolute right-0 mt-3 w-56 bg-white border border-gray-200 shadow-lg z-50">
                  
                  <div class="px-4 py-3 border-b border-gray-100">
                    <p class="text-[13px] font-semibold text-black">{{ Auth::user()->name }}</p>
                    <p class="text-[11px] text-gray-500">{{ Auth::user()->email }}</p>
                  </div>
                  
                  <a href="{{ route('user.profile') }}" 
                     class="flex items-center gap-3 px-4 py-3 text-[13px] text-black hover:bg-gray-50 transition-colors uppercase tracking-wider">
                    <i class="bi bi-person-circle text-[15px]"></i>
                    <span>Profile</span>
                  </a>
                  
                  <a href="{{ route('user.profile.settings') }}" 
                     class="flex items-center gap-3 px-4 py-3 text-[13px] text-black hover:bg-gray-50 transition-colors uppercase tracking-wider">
                    <i class="bi bi-gear text-[15px]"></i>
                    <span>Settings</span>
                  </a>

                  <a href="{{ route('user.keranjang.index') }}" 
                     class="flex items-center gap-3 px-4 py-3 text-[13px] text-black hover:bg-gray-50 transition-colors uppercase tracking-wider">
                    <i class="bi bi-bag text-[15px]"></i>
                    <span>Cart</span>
                    @php
                        $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count();
                    @endphp
                    @if($cartCount > 0)
                        <span class="ml-auto bg-black text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">
                            {{ $cartCount }}
                        </span>
                    @endif
                  </a>

                  <a href="{{ route('user.wishlist.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 text-[13px] text-black hover:bg-gray-50 transition-colors uppercase tracking-wider">
                      <i class="bi bi-heart text-[15px]"></i>
                      <span>Wishlist</span>
                      @php
                          $wishlistCount = \App\Models\Wishlist::where('user_id', Auth::id())->count();
                      @endphp
                      @if($wishlistCount > 0)
                          <span class="ml-auto bg-black text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">
                              {{ $wishlistCount }}
                          </span>
                      @endif
                  </a>
                  
                  <div class="border-t border-gray-100"></div>
                  
                  <a href="{{ route('logout') }}" 
                     class="flex items-center gap-3 px-4 py-3 text-[13px] text-black hover:bg-gray-50 transition-colors uppercase tracking-wider">
                    <i class="bi bi-box-arrow-right text-[15px]"></i>
                    <span>Logout</span>
                  </a>
                </div>
              </div>

              <!-- Cart Icon -->
              <a href="{{ route('user.keranjang.index') }}" class="nav-icon transition-colors relative" title="Cart">
                <i class="bi bi-bag text-[18px]"></i>
                @php
                    $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count();
                @endphp
                @if($cartCount > 0)
                    <span class="cart-badge absolute -top-2 -right-2 bg-black text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center">
                        {{ $cartCount }}
                    </span>
                @endif
              </a>
            @endif
          @else
            <a href="/login" class="nav-link text-[13px] font-medium tracking-wider uppercase transition-colors">
              Log In
            </a>
          @endauth
        </div>
      </div>
    </div>
  </div>

  <!-- MOBILE MENU -->
  <div id="mobile-menu" class="lg:hidden fixed inset-0 z-40 pointer-events-none">
    <div class="absolute inset-0 bg-black/50 opacity-0 transition-opacity duration-300" id="overlay"></div>
    
    <div class="absolute left-0 top-0 h-full w-80 bg-white transform -translate-x-full transition-transform duration-300 border-r border-gray-200" id="menu-panel">
      <div class="flex justify-between items-center p-6 border-b border-gray-200">
        <span class="text-xl font-black text-black">{{ env("APP_NAME") }}</span>
        <button onclick="closeMobileMenu()" class="text-black text-2xl">
          <i class="bi bi-x"></i>
        </button>
      </div>
      
      <div class="p-6 space-y-1">
        <a href="/" class="block py-4 text-[14px] font-medium text-black uppercase tracking-wider border-b border-gray-100 hover:opacity-60 transition-opacity">Home</a>
        <a href="/products" class="block py-4 text-[14px] font-medium text-black uppercase tracking-wider border-b border-gray-100 hover:opacity-60 transition-opacity">Shop</a>
        <a href="/about" class="block py-4 text-[14px] font-medium text-black uppercase tracking-wider border-b border-gray-100 hover:opacity-60 transition-opacity">Lookbooks</a>
        
        @auth
          @if(Auth::user()->role_id == 1)
            <a href="/dashboard" class="block py-4 text-[14px] font-medium text-black uppercase tracking-wider border-b border-gray-100 hover:opacity-60 transition-opacity">Dashboard</a>
            <a href="{{ route('logout') }}" class="block py-4 text-[14px] font-medium text-black uppercase tracking-wider border-b border-gray-100 hover:opacity-60 transition-opacity">Logout</a>
          @else
            <a href="{{ route('user.profile') }}" class="block py-4 text-[14px] font-medium text-black uppercase tracking-wider border-b border-gray-100 hover:opacity-60 transition-opacity">Profile</a>
            <a href="{{ route('user.keranjang.index') }}" class="block py-4 text-[14px] font-medium text-black uppercase tracking-wider border-b border-gray-100 hover:opacity-60 transition-opacity">Cart</a>
            <a href="{{ route('logout') }}" class="block py-4 text-[14px] font-medium text-black uppercase tracking-wider border-b border-gray-100 hover:opacity-60 transition-opacity">Logout</a>
          @endif
        @else
          <a href="/login" class="block py-4 text-[14px] font-medium text-black uppercase tracking-wider border-b border-gray-100 hover:opacity-60 transition-opacity">Log In</a>
        @endauth
      </div>
    </div>
  </div>
</nav>

<!-- ============================================ -->
<!-- NAVBAR STYLE & SCRIPT -->
<!-- ============================================ -->
<style>
  /* ============================================
     NAVBAR DEFAULT (Transparan - di atas hero)
     ============================================ */
  #navbar {
    background: transparent;
  }
  
  #navbar .nav-link,
  #navbar .nav-logo,
  #navbar .nav-icon {
    color: #ffffff;
  }
  
  #navbar .nav-line {
    background-color: #ffffff;
  }
  
  #navbar .cart-badge {
    background-color: #ffffff;
    color: #000000;
  }
  
  /* ============================================
     NAVBAR SCROLLED (Putih Solid)
     ============================================ */
  #navbar.scrolled {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid #e5e5e5;
  }
  
  #navbar.scrolled .nav-link,
  #navbar.scrolled .nav-logo,
  #navbar.scrolled .nav-icon {
    color: #000000;
  }
  
  #navbar.scrolled .nav-line {
    background-color: #000000;
  }
  
  #navbar.scrolled .cart-badge {
    background-color: #000000;
    color: #ffffff;
  }
  
  /* Smooth transition */
  #navbar, #mobile-menu, #menu-panel, #overlay {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }
  
  /* Dropdown animation */
  #dropdown-menu:not(.hidden) {
    animation: dropdownSlideIn 0.2s ease-out forwards;
  }
  
  @keyframes dropdownSlideIn {
    from {
      opacity: 0;
      transform: translateY(-5px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Default: Transparan KALAU di halaman hero */
body.has-hero #navbar {
    background: transparent;
}

body.has-hero #navbar .nav-link,
body.has-hero #navbar .nav-logo,
body.has-hero #navbar .nav-icon {
    color: #ffffff;
}

/* Halaman lain: Putih solid */
body:not(.has-hero) #navbar {
    background: #ffffff;
    border-bottom: 1px solid #e5e5e5;
}

body:not(.has-hero) #navbar .nav-link,
body:not(.has-hero) #navbar .nav-logo,
body:not(.has-hero) #navbar .nav-icon {
    color: #000000;
}

/* Scrolled: SELALU putih solid (di semua halaman) */
#navbar.scrolled {
    background: rgba(255, 255, 255, 0.98) !important;
    backdrop-filter: blur(10px);
    border-bottom: 1px solid #e5e5e5;
}

#navbar.scrolled .nav-link,
#navbar.scrolled .nav-logo,
#navbar.scrolled .nav-icon {
    color: #000000 !important;
}

#navbar.scrolled .nav-line {
    background-color: #000000 !important;
}

#navbar.scrolled .cart-badge {
    background-color: #000000 !important;
    color: #ffffff !important;
}
</style>

<script>
  // ============================================
  // NAVBAR SCROLL EFFECT
  // ============================================
  const navbar = document.getElementById('navbar');
  
  window.addEventListener('scroll', function() {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });

  // ============================================
  // MOBILE MENU
  // ============================================
  const menuBtn = document.getElementById('menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  const overlay = document.getElementById('overlay');
  const menuPanel = document.getElementById('menu-panel');
  const lines = [document.getElementById('line1'), document.getElementById('line2')];

  function toggleMobileMenu() {
    const isOpen = mobileMenu.classList.contains('open');
    
    if (!isOpen) {
      mobileMenu.classList.add('open');
      overlay.classList.remove('opacity-0');
      overlay.classList.add('opacity-100');
      menuPanel.classList.remove('-translate-x-full');
      menuPanel.classList.add('translate-x-0');
      mobileMenu.classList.remove('pointer-events-none');
      
      lines[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
      lines[1].style.opacity = '0';
    } else {
      closeMobileMenu();
    }
  }

  function closeMobileMenu() {
    mobileMenu.classList.remove('open');
    overlay.classList.remove('opacity-100');
    overlay.classList.add('opacity-0');
    menuPanel.classList.remove('translate-x-0');
    menuPanel.classList.add('-translate-x-full');
    setTimeout(() => {
      mobileMenu.classList.add('pointer-events-none');
    }, 300);
    
    lines[0].style.transform = 'none';
    lines[1].style.opacity = '1';
  }

  if (menuBtn) {
    menuBtn.addEventListener('click', toggleMobileMenu);
  }
  if (overlay) {
    overlay.addEventListener('click', closeMobileMenu);
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeMobileMenu();
    }
  });

  // ============================================
  // DROPDOWN
  // ============================================
  function toggleDropdown() {
    const menu = document.getElementById('dropdown-menu');
    if (menu) {
      menu.classList.toggle('hidden');
    }
  }

  document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('user-dropdown');
    if (dropdown && !dropdown.contains(event.target)) {
      const menu = document.getElementById('dropdown-menu');
      if (menu) menu.classList.add('hidden');
    }
  });
</script>