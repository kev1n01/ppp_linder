<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Ítems destacados (por ahora: los primeros 6 activos)
        $featuredItems = Item::where('ite_status', true)
            ->where('ite_stock', '>', 0)
            ->latest('id')
            ->take(6)
            ->get(['id','ite_name','ite_description','ite_price','ite_image','ite_type','ite_discount']);

        return Inertia::render('Home', [
            'featuredItems' => $featuredItems,
            'settings' => Setting::first()
        ]);
    }
}
