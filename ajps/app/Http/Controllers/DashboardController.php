<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the Super Admin confirmation form (before accessing Users page).
     */
public function usersAuthForm()
{
    return view('auth.superadmin-confirm');
}

public function checkUsersAuth(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required'
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();

    if ($user && $user->password === $request->password) {
        session(['superadmin_verified' => true]);

        return redirect()->route('dashboard', ['page' => 'users'])
                         ->with('success', 'Super Admin access confirmed.');
    }

    return back()->with('error', 'Invalid email or password.');
}


    public function index(Request $request)
    {
        // Require login (manual check sa session, kasi hindi tayo gamit Auth::check())
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Please log in first.');
        }

        $page = $request->get('page', 'dashboard');

        $data = [
            'name'         => session('user_name'),
            'page'         => $page,
            'reservations' => collect(),
            'bookings'     => collect(),
            'users'        => collect(),
        ];

        // Reservations page
        if ($page === 'reservation') {
            $data['reservations'] = Reservation::all();
        }

        // Booking requests page
        if ($page === 'booking') {
            $data['bookings'] = DB::table('bookings')
                                  ->where('status', 'Pending')
                                  ->get();
        }

        // Users page (only if verified superadmin)
        if ($page === 'users') {
            if (!session('superadmin_verified')) {
                return redirect()->route('superadmin.auth.form')
                                 ->with('error', 'Please confirm Super Admin access.');
            }
            $data['users'] = User::all();
        }

        return view('dashboard', $data);
    }
}
