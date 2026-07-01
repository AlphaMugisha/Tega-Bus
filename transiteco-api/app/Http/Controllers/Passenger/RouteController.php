<?php

declare(strict_types=1);

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\JsonResponse;

class RouteController extends Controller
{
    public function index(): JsonResponse
    {
        $routes = Route::where('is_active', true)->orderBy('origin_station')->get();

        return response()->json(['data' => $routes]);
    }
}
