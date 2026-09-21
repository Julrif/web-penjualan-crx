@extends("layouts.dashboard")
@section('content')
    <div class="min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <x-sidebar />
        <!-- Main Content -->
        <main class="ml-64 p-6 transition-all duration-300">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Welcome to Sale</h2>
                <p class="text-gray-600">
                    This is the main content area. Sidebar is working perfectly!
                </p>
            </div>
        </main>
    </div>
@endsection