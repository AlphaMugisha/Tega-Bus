<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\Company;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Seeder;

class SimulationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Driver User',
            'email' => 'driver@ritual.rw',
            'phone' => '+250788000000',
            'password' => bcrypt('password'),
        ]);

        $company = Company::create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'company_name' => 'Ritual Express',
            'registration_number' => 'REG123456',
            'owner_id' => $user->id,
            'verification_status' => 'verified',
        ]);

        $bus = Bus::create([
            'company_id' => $company->id,
            'plate_number' => 'RAE 450 C',
            'model' => 'Scania K360',
            'total_seats' => 60,
            'seat_layout' => json_encode(['rows' => 15, 'cols' => 4]),
            'status' => 'active',
        ]);

        $route = Route::create([
            'origin_station' => 'Kigali',
            'destination_station' => 'Gisenyi',
            'distance_km' => 150.00,
            'estimated_duration' => '03:00:00',
            'is_active' => true,
        ]);

        Schedule::create([
            'id' => '550e8400-e29b-41d4-a716-446655440000',
            'bus_id' => $bus->id,
            'route_id' => $route->id,
            'departure_time' => now()->addHour(),
            'arrival_time' => now()->addHours(4),
            'base_price' => 5000,
            'status' => 'scheduled',
            'available_seats' => 60,
        ]);
    }
}
