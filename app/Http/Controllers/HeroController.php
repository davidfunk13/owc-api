<?php

namespace App\Http\Controllers;

use App\Models\Hero;
use Illuminate\Http\JsonResponse;

class HeroController extends Controller
{
    public function index(): JsonResponse
    {
        $heroes = Hero::orderBy('role')
            ->orderBy('sub_role')
            ->orderBy('name')
            ->get();

        return response()->json($heroes);
    }
}
