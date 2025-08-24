<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Models\POS;

class DosyatController extends Controller
{
    public function index()
    {
        $userId = Session::get('user_id');
        $userName = Session::get('user_name');

        if (!$userId) {
            return redirect()->route('home');
        }

        return view('sections.dosyat', compact('userName'));
    }

    public function getMaktabat()
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $maktabat = POS::all();
        
        return response()->json([
            'success' => true,
            'data' => $maktabat
        ]);
    }

    public function getDelivery()
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // You can store these in database or config file
        $deliveryPhones = [
            [
                'name' => 'خدمة التوصيل الأولى',
                'phone' => '0796691306',
                'description' => 'متوفر 24/7'
            ],
            [
                'name' => 'خدمة التوصيل الثانية', 
                'phone' => '0799635444',
                'description' => 'توصيل سريع'
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $deliveryPhones
        ]);
    }
}