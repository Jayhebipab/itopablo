@extends('layouts.app')

@section('title', 'Tattoo Reservation Dashboard')

@section('content')
<div class="flex h-screen bg-gray-100">
    {{-- Sidebar --}}
    <aside class="w-64 bg-gray-900 text-white flex flex-col">
        <div class="p-6 border-b border-gray-700 flex flex-col items-center justify-center">
            <img src="{{ asset('images/pic4.png') }}" alt="Logo" class="h-20" />
            <span class="text-2xl font-bold"></span>
        </div>

        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('dashboard', ['page' => 'dashboard']) }}"
                class="flex items-center w-full px-4 py-2 rounded-lg hover:bg-gray-700 transition {{ $page === 'dashboard' ? 'bg-gray-800' : '' }}">
                🏠 Dashboard
            </a>

            <a href="{{ route('dashboard', ['page' => 'reservation']) }}"
                class="flex items-center w-full px-4 py-2 rounded-lg hover:bg-gray-700 transition {{ $page === 'reservation' ? 'bg-gray-800' : '' }}">
                ➕ Reservation
            </a>

            <a href="{{ route('dashboard', ['page' => 'booking']) }}"
                class="flex items-center w-full px-4 py-2 text-left rounded-lg hover:bg-gray-700 transition {{ $page === 'booking' ? 'bg-gray-800' : '' }}">
                📥 Booking Request
            </a>
            
            <a href="{{ route('dashboard', ['page' => 'shop']) }}"
                class="flex items-center w-full px-4 py-2 rounded-lg hover:bg-gray-700 transition {{ $page === 'shop' ? 'bg-gray-800' : '' }}">
                🛒 Shop
            </a>

            <a href="{{ route('dashboard', ['page' => 'dashboard']) }}"
                class="flex items-center w-full px-4 py-2 rounded-lg hover:bg-gray-700 transition {{ $page === 'artist' ? 'bg-gray-800' : '' }}">
                ✒️ Artist
            </a>

            <div x-data="{ open: false }" class="relative">
        <button @click="open = !open"
                class="flex items-center w-full px-4 py-2 rounded-lg hover:bg-gray-700 transition {{ $page === 'reports' ? 'bg-gray-800' : '' }}">
            📊 Reports
            <svg class="ml-auto w-4 h-4 transform transition-transform"
                    :class="{ 'rotate-180': open }"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        {{-- Tinanggal ang 'absolute left-0' para mag-flow nang tama ang element --}}
        <div x-show="open" 
            @click.away="open = false"
            x-transition
            class="mt-1 w-full bg-gray-700 rounded-lg shadow-lg overflow-hidden">
            <a href="{{ route('dashboard', ['page' => 'audit']) }}"
                class="flex items-center w-full px-4 py-2 rounded-lg hover:bg-gray-600 transition text-gray-200 {{ $page === 'audit' ? 'bg-gray-800' : '' }}">
                📜 Audit Trail
            </a>
        </div>
    </div>

    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open"
                class="flex items-center w-full px-4 py-2 rounded-lg hover:bg-gray-700 transition {{ $page === 'maintenance' ? 'bg-gray-800' : '' }}">
            ⚙️ Maintenance
            <svg class="ml-auto w-4 h-4 transform transition-transform"
                    :class="{ 'rotate-180': open }"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        {{-- Tinanggal ang 'absolute left-0' para mag-flow nang tama ang element --}}
        <div x-show="open" 
            @click.away="open = false"
            x-transition
            class="mt-1 w-full bg-gray-700 rounded-lg shadow-lg overflow-hidden">
<a href="{{ route('dashboard', ['page' => 'users']) }}"
   class="flex items-center w-full px-4 py-2 rounded-lg hover:bg-gray-600 transition text-gray-200 {{ $page === 'users' ? 'bg-gray-800' : '' }}">
   👥 Users
</a>
        </div>
    </div>
        </nav>

        <a href="{{ route('logout') }}"
            class="m-4 mt-auto px-4 py-2 text-left text-red-500 hover:bg-gray-800 rounded-lg transition">
            🔓 Logout
        </a>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 p-10 bg-gray-100 overflow-y-auto">
        @if ($page === 'booking')
            {{-- Passing the bookings data to the partial --}}
            @include('partials.booking-requests', ['bookings' => $bookings])

        @elseif ($page === 'reservation')
            {{-- Passing the reservations data to the partial --}}
            @include('partials.reservations.create', ['reservations' => $reservations])

            {{-- New Reservation Popup --}}
            @if (!empty($showCreateForm))
                @include('partials.reservations.popup-create')
            @endif

            @if(session('editReservation'))
            @include('partials.reservations.popup-edit', ['reservation' => session('editReservation')])
            @endif

        @elseif ($page === 'artist')
            <h2 class="text-2xl font-bold mb-4">Artist Management Page (Coming Soon)</h2>
        @elseif ($page === 'users')
            @include('partials.maintenance.users', ['users' => $users])
        @else
            {{-- Dashboard Default --}}
            <h1 class="text-3xl font-semibold mb-6">Welcome, {{ $name ?? 'Admin' }}</h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow flex items-center space-x-4 border-l-4 border-blue-500">
                    <div class="bg-blue-100 p-3 rounded-full">📨</div>
                    <div>
                        <p class="text-sm text-gray-500">Total Booking Requests</p>
                        <h2 class="text-2xl font-bold">8</h2>
                        <p class="text-sm text-gray-400">All booking requests in system</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow flex items-center space-x-4 border-l-4 border-green-500">
                    <div class="bg-green-100 p-3 rounded-full">✅</div>
                    <div>
                        <p class="text-sm text-gray-500">Approved Bookings</p>
                        <h2 class="text-2xl font-bold">3</h2>
                        <p class="text-sm text-gray-400">Confirmed reservations</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow flex items-center space-x-4 border-l-4 border-yellow-500">
                    <div class="bg-yellow-100 p-3 rounded-full">🕒</div>
                    <div>
                        <p class="text-sm text-gray-500">Pending Bookings</p>
                        <h2 class="text-2xl font-bold">5</h2>
                        <p class="text-sm text-gray-400">Awaiting approval</p>
                    </div>
                </div>
            </div>
        @endif
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection
