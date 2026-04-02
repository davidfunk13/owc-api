<?php

namespace App\Http\Controllers;

use App\Models\Map;
use Illuminate\Http\JsonResponse;

class MapController extends Controller
{
    public function index(): JsonResponse
    {
        $maps = Map::with('submaps')
            ->orderBy('map_type')
            ->orderBy('name')
            ->get();

        return response()->json($maps);
    }
}
