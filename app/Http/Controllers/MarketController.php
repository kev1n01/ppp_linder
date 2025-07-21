<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MarketController extends Controller
{
    public function index(Request $request) {
        // Leer filtros desde querystring
        $search = $request->query('search');          // nombre
        $type   = $request->query('type');            // producto | servicio
        $min    = $request->query('min_price');       // número
        $max    = $request->query('max_price');       // número
        $perPage= $request->query('per_page', 6);     // items por página

        $query = Item::query()->where('ite_status', true)->where('ite_stock', '>', 0);

        if ($search) {
            $query->where('ite_name', 'like', "%{$search}%");
        }

        if ($type && in_array($type, ['producto', 'servicio'])) {
            $query->where('ite_type', $type);
        }

        if (is_numeric($min)) {
            $query->where('ite_price', '>=', $min);
        }

        if (is_numeric($max)) {
            $query->where('ite_price', '<=', $max);
        }

        $items = $query->orderBy('ite_name')
            ->paginate($perPage)
            ->withQueryString(); // mantiene filtros en paginación

        return Inertia::render('Market', [
            'items'   => $items,
            'filters' => [
                'search'    => $search,
                'type'      => $type,
                'min_price' => $min,
                'max_price' => $max,
                'per_page'  => $perPage,
            ],
        ]);
    }
}
