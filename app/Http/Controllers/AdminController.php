<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hotel;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        // Nếu là admin, lấy tổng số user và hotel
        if (Auth::user()->role_id == 1) {
            $totalUsers = User::count();
            $totalHotels = Hotel::count();
        } else {
            $totalUsers = null; // Member không có quyền xem tổng user
            $totalHotels = Hotel::where('user_id', Auth::id())->count();
        }

        return view('dashboard', compact('totalUsers', 'totalHotels'));
    }
}
