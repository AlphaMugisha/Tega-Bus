<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Bus;
use App\Models\Company;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\SeatBooked;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RichDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── Companies ─────────────────────────────────────────────────
        $owner1 = User::firstOrCreate(['phone' => '+250788100001'], [
            'name' => 'Amina Uwase', 'email' => 'amina@ritualexpress.rw',
            'password' => bcrypt('password'), 'recovery_phone' => '+250788100002', 'status' => 'active',
        ]);
        $owner2 = User::firstOrCreate(['phone' => '+250788200001'], [
            'name' => 'Jean Habimana', 'email' => 'jean@jaguar.rw',
            'password' => bcrypt('password'), 'recovery_phone' => '+250788200002', 'status' => 'active',
        ]);
        $owner3 = User::firstOrCreate(['phone' => '+250788300001'], [
            'name' => 'Claude Bizimana', 'email' => 'claude@virunga.rw',
            'password' => bcrypt('password'), 'recovery_phone' => '+250788300002', 'status' => 'active',
        ]);

        // Demo passenger account
        User::firstOrCreate(['phone' => '+250788000100'], [
            'name' => 'Kevin Mugisha', 'email' => 'kevin@demo.rw',
            'password' => bcrypt('Demo@1234'), 'recovery_phone' => '+250788000101', 'status' => 'active',
        ]);

        $companies = [
            ['id' => Str::uuid(), 'company_name' => 'Ritual Express',         'registration_number' => 'REG-2020-0001', 'owner_id' => $owner1->id, 'verification_status' => 'verified'],
            ['id' => Str::uuid(), 'company_name' => 'Jaguar Executive Coaches','registration_number' => 'REG-2019-0002', 'owner_id' => $owner2->id, 'verification_status' => 'verified'],
            ['id' => Str::uuid(), 'company_name' => 'Virunga Express',         'registration_number' => 'REG-2021-0003', 'owner_id' => $owner3->id, 'verification_status' => 'verified'],
            ['id' => Str::uuid(), 'company_name' => 'Volcano Express',         'registration_number' => 'REG-2026-0041', 'owner_id' => $owner1->id, 'verification_status' => 'pending'],
        ];

        foreach ($companies as $co) {
            Company::firstOrCreate(['registration_number' => $co['registration_number']], $co);
        }

        $ritual  = Company::where('registration_number', 'REG-2020-0001')->first();
        $jaguar  = Company::where('registration_number', 'REG-2019-0002')->first();
        $virunga = Company::where('registration_number', 'REG-2021-0003')->first();

        // ── Routes ────────────────────────────────────────────────────
        $routeData = [
            ['origin_station' => 'Kigali',  'destination_station' => 'Gisenyi',    'distance_km' => 160,  'estimated_duration' => '03:00:00', 'is_active' => true],
            ['origin_station' => 'Kigali',  'destination_station' => 'Huye',       'distance_km' => 135,  'estimated_duration' => '02:30:00', 'is_active' => true],
            ['origin_station' => 'Kigali',  'destination_station' => 'Musanze',    'distance_km' => 108,  'estimated_duration' => '02:00:00', 'is_active' => true],
            ['origin_station' => 'Kigali',  'destination_station' => 'Rusizi',     'distance_km' => 225,  'estimated_duration' => '04:00:00', 'is_active' => true],
            ['origin_station' => 'Kigali',  'destination_station' => 'Kampala',    'distance_km' => 497,  'estimated_duration' => '09:00:00', 'is_active' => true],
            ['origin_station' => 'Kigali',  'destination_station' => 'Bujumbura',  'distance_km' => 314,  'estimated_duration' => '06:00:00', 'is_active' => true],
            ['origin_station' => 'Kigali',  'destination_station' => 'Kayonza',    'distance_km' => 85,   'estimated_duration' => '01:30:00', 'is_active' => true],
            ['origin_station' => 'Gisenyi', 'destination_station' => 'Kigali',     'distance_km' => 160,  'estimated_duration' => '03:00:00', 'is_active' => true],
        ];

        foreach ($routeData as $rd) {
            Route::firstOrCreate(
                ['origin_station' => $rd['origin_station'], 'destination_station' => $rd['destination_station']],
                $rd
            );
        }

        $rKigaliGisenyi  = Route::where('origin_station', 'Kigali')->where('destination_station', 'Gisenyi')->first();
        $rKigaliHuye     = Route::where('origin_station', 'Kigali')->where('destination_station', 'Huye')->first();
        $rKigaliMusanze  = Route::where('origin_station', 'Kigali')->where('destination_station', 'Musanze')->first();
        $rKigaliKampala  = Route::where('origin_station', 'Kigali')->where('destination_station', 'Kampala')->first();
        $rKigaliBuja     = Route::where('origin_station', 'Kigali')->where('destination_station', 'Bujumbura')->first();
        $rKigaliRusizi   = Route::where('origin_station', 'Kigali')->where('destination_station', 'Rusizi')->first();

        // ── Buses ─────────────────────────────────────────────────────
        $layout60 = json_encode(['rows' => 15, 'cols' => 4]);
        $layout49 = json_encode(['rows' => 13, 'cols' => 4, 'extra' => 1]);
        $layout55 = json_encode(['rows' => 14, 'cols' => 4, 'extra' => 3]);

        $buses = [
            ['company_id' => $ritual->id,  'plate_number' => 'RAE 450 C', 'model' => 'Scania K360',    'total_seats' => 60, 'seat_layout' => $layout60, 'status' => 'active'],
            ['company_id' => $ritual->id,  'plate_number' => 'RAC 120 B', 'model' => 'Yutong ZK6119',  'total_seats' => 55, 'seat_layout' => $layout55, 'status' => 'active'],
            ['company_id' => $ritual->id,  'plate_number' => 'RAB 881 D', 'model' => 'Higer KLQ6129',  'total_seats' => 49, 'seat_layout' => $layout49, 'status' => 'maintenance'],
            ['company_id' => $jaguar->id,  'plate_number' => 'RAD 334 A', 'model' => 'Volvo 9700',     'total_seats' => 55, 'seat_layout' => $layout55, 'status' => 'active'],
            ['company_id' => $jaguar->id,  'plate_number' => 'RAG 210 E', 'model' => 'Mercedes Tourismo','total_seats' => 49,'seat_layout' => $layout49,'status' => 'active'],
            ['company_id' => $virunga->id, 'plate_number' => 'RAF 560 F', 'model' => 'Golden Dragon',   'total_seats' => 49, 'seat_layout' => $layout49, 'status' => 'active'],
        ];

        foreach ($buses as $busData) {
            Bus::firstOrCreate(['plate_number' => $busData['plate_number']], $busData);
        }

        $busRAE = Bus::where('plate_number', 'RAE 450 C')->first();
        $busRAC = Bus::where('plate_number', 'RAC 120 B')->first();
        $busRAD = Bus::where('plate_number', 'RAD 334 A')->first();
        $busRAG = Bus::where('plate_number', 'RAG 210 E')->first();
        $busRAF = Bus::where('plate_number', 'RAF 560 F')->first();

        // ── Schedules (today + tomorrow) ─────────────────────────────
        $today = now()->startOfDay();

        $schedules = [
            // Fixed simulation schedule
            [
                'id'             => '550e8400-e29b-41d4-a716-446655440000',
                'bus_id'         => $busRAE->id,
                'route_id'       => $rKigaliGisenyi->id,
                'departure_time' => $today->copy()->setTime(8, 30),
                'arrival_time'   => $today->copy()->setTime(11, 30),
                'base_price'     => 5000,
                'status'         => 'scheduled',
                'available_seats'=> 60,
            ],
            [
                'id'             => Str::uuid(),
                'bus_id'         => $busRAC->id,
                'route_id'       => $rKigaliGisenyi->id,
                'departure_time' => $today->copy()->setTime(14, 0),
                'arrival_time'   => $today->copy()->setTime(17, 0),
                'base_price'     => 5000,
                'status'         => 'scheduled',
                'available_seats'=> 55,
            ],
            [
                'id'             => Str::uuid(),
                'bus_id'         => $busRAD->id,
                'route_id'       => $rKigaliHuye->id,
                'departure_time' => $today->copy()->setTime(7, 0),
                'arrival_time'   => $today->copy()->setTime(9, 30),
                'base_price'     => 4000,
                'status'         => 'scheduled',
                'available_seats'=> 55,
            ],
            [
                'id'             => Str::uuid(),
                'bus_id'         => $busRAF->id,
                'route_id'       => $rKigaliMusanze->id,
                'departure_time' => $today->copy()->setTime(9, 0),
                'arrival_time'   => $today->copy()->setTime(11, 0),
                'base_price'     => 3500,
                'status'         => 'scheduled',
                'available_seats'=> 49,
            ],
            [
                'id'             => Str::uuid(),
                'bus_id'         => $busRAG->id,
                'route_id'       => $rKigaliKampala->id,
                'departure_time' => $today->copy()->setTime(6, 0),
                'arrival_time'   => $today->copy()->setTime(15, 0),
                'base_price'     => 15000,
                'status'         => 'scheduled',
                'available_seats'=> 49,
            ],
            [
                'id'             => Str::uuid(),
                'bus_id'         => $busRAD->id,
                'route_id'       => $rKigaliBuja->id,
                'departure_time' => $today->copy()->addDay()->setTime(7, 30),
                'arrival_time'   => $today->copy()->addDay()->setTime(13, 30),
                'base_price'     => 12000,
                'status'         => 'scheduled',
                'available_seats'=> 55,
            ],
            [
                'id'             => Str::uuid(),
                'bus_id'         => $busRAC->id,
                'route_id'       => $rKigaliRusizi->id,
                'departure_time' => $today->copy()->addDay()->setTime(8, 0),
                'arrival_time'   => $today->copy()->addDay()->setTime(12, 0),
                'base_price'     => 7000,
                'status'         => 'scheduled',
                'available_seats'=> 55,
            ],
        ];

        foreach ($schedules as $s) {
            Schedule::firstOrCreate(['id' => $s['id']], $s);
        }

        // ── Sample booking for passenger Kevin ────────────────────────
        $kevin = User::where('phone', '+250788000100')->first();
        $mainSchedule = Schedule::find('550e8400-e29b-41d4-a716-446655440000');

        if ($kevin && $mainSchedule && !Booking::where('user_id', $kevin->id)->exists()) {
            $booking = Booking::create([
                'schedule_id'          => $mainSchedule->id,
                'user_id'              => $kevin->id,
                'total_amount'         => 10000,
                'payment_status'       => 'paid',
                'transaction_reference'=> 'TXN' . strtoupper(Str::random(8)),
                'qr_code_hash'         => hash('sha256', 'kevin-booking-1'),
                'expires_at'           => now()->addDays(7),
            ]);

            SeatBooked::create(['booking_id' => $booking->id, 'schedule_id' => $mainSchedule->id, 'seat_number' => 12, 'passenger_name' => 'Kevin Mugisha',  'passenger_phone' => '+250788000100']);
            SeatBooked::create(['booking_id' => $booking->id, 'schedule_id' => $mainSchedule->id, 'seat_number' => 13, 'passenger_name' => 'Diane Uwingabire','passenger_phone' => '+250788000102']);
        }
    }
}
