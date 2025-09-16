<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    // Pending Booking Requests
    public function index()
    {
        $bookings = DB::table('bookings')
            ->where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('partials.booking-requests', ['bookings' => $bookings]);
    }

    // List of Reservations (Approved)
    public function reservations()
    {
        $reservations = DB::table('reservations')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('partials.reservations', ['reservations' => $reservations]);
    }

    // Approve Booking → Move to reservations
    public function approve($id)
    {
        $booking = DB::table('bookings')->where('id', $id)->first();
    
        if ($booking) {
            DB::table('bookings')->where('id', $id)->update(['status' => 'Approved']);

            $preferred_time_24hr = Carbon::parse($booking->preferred_time)->format('H:i:s');

            DB::table('reservations')->insert([
                'first_name' => $booking->first_name,
                'last_name' => $booking->last_name,
                'email' => $booking->email,
                'phone' => $booking->phone,
                'preferred_date' => $booking->preferred_date,
                'preferred_time' => $preferred_time_24hr,
                'service' => $booking->service,
                'instruction' => $booking->instruction,
                'status' => 'Approved', // ✅ add status column para klaro
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            return redirect()->route('dashboard', ['page' => 'reservation'])
                ->with('success', 'Booking has been approved and moved to reservations!');
        }
    
        return redirect()->back()->with('error', 'Booking not found.');
    }

    // Cancel Booking
    public function cancel($id)
    {
        $booking = DB::table('bookings')->where('id', $id)->first();

        if ($booking) {
            DB::table('bookings')->where('id', $id)->update(['status' => 'Cancelled']);
            return redirect()->route('dashboard', ['page' => 'booking'])
                ->with('success', 'Booking has been cancelled.');
        }

        return redirect()->back()->with('error', 'Booking not found.');
    }
}
