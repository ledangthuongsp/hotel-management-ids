<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Chặn user chưa đăng nhập
    }

    public function index()
    {
        // Debug xem user có được lưu không
        if (!Auth::check()) {
            dd("User chưa đăng nhập!");
        }

        return view('dashboard', ['user' => Auth::user()]);
    }
}

