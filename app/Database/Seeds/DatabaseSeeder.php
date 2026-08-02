<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $now = date('Y-m-d H:i:s');
        $today = date('Y-m-d');

        // 1. Seed Roles
        $rolesData = [
            ['name' => 'Super Admin', 'slug' => 'super-admin', 'description' => 'Akses penuh ke seluruh sistem', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Admin Operasional', 'slug' => 'admin-operasional', 'description' => 'Kelola jadwal, armada, rute, dan booking', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Kasir', 'slug' => 'kasir', 'description' => 'Melayani booking offline dan cetak tiket', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Petugas Dermaga', 'slug' => 'petugas-dermaga', 'description' => 'Scan QR Code check-in dan validasi boarding', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Supervisor', 'slug' => 'supervisor', 'description' => 'Monitoring operasional dan pengajuan refund/reschedule', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Manajer', 'slug' => 'manajer', 'description' => 'Melihat laporan penjualan dan kinerja bisnis', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Member', 'slug' => 'member', 'description' => 'Pelanggan/Penumpang publik', 'created_at' => $now, 'updated_at' => $now],
        ];
        $db->table('roles')->insertBatch($rolesData);

        // 2. Seed Permissions
        $permissionsData = [
            ['name' => 'Manage Master Data', 'slug' => 'manage_master_data', 'module' => 'Master Data', 'description' => 'CRUD Armada, Rute, Lokasi'],
            ['name' => 'Manage Schedules', 'slug' => 'manage_schedules', 'module' => 'Schedules', 'description' => 'CRUD Master Jadwal & Trip'],
            ['name' => 'Manage Bookings', 'slug' => 'manage_bookings', 'module' => 'Bookings', 'description' => 'Kelola transaksi & booking'],
            ['name' => 'Check-in & Boarding', 'slug' => 'check_in_boarding', 'module' => 'Checkin', 'description' => 'Scan QR & Boarding Penumpang'],
            ['name' => 'Approve Refund & Reschedule', 'slug' => 'approve_refund_reschedule', 'module' => 'Refund', 'description' => 'Persetujuan refund & reschedule'],
            ['name' => 'View Reports', 'slug' => 'view_reports', 'module' => 'Reports', 'description' => 'Akses laporan & statistik'],
        ];
        $db->table('permissions')->insertBatch($permissionsData);

        // Assign All Permissions to Super Admin
        $superAdminRoleId = $db->table('roles')->where('slug', 'super-admin')->get()->getRow()->id;
        $allPermissions = $db->table('permissions')->get()->getResult();
        foreach ($allPermissions as $perm) {
            $db->table('role_permissions')->insert([
                'role_id'       => $superAdminRoleId,
                'permission_id' => $perm->id
            ]);
        }

        // 3. Seed Users
        $defaultPassword = password_hash('password123', PASSWORD_BCRYPT);
        $usersData = [
            [
                'uuid'          => '11111111-1111-1111-1111-111111111111',
                'role_id'       => 1, // Super Admin
                'name'          => 'Super Admin Speed',
                'email'         => 'admin@speed.test',
                'phone'         => '081234567890',
                'password_hash' => $defaultPassword,
                'is_active'     => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'uuid'          => '22222222-2222-2222-2222-222222222222',
                'role_id'       => 2, // Admin Ops
                'name'          => 'Ops Speedboat',
                'email'         => 'operasional@speed.test',
                'phone'         => '081234567891',
                'password_hash' => $defaultPassword,
                'is_active'     => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'uuid'          => '33333333-3333-3333-3333-333333333333',
                'role_id'       => 3, // Kasir
                'name'          => 'Kasir Dermaga Sanur',
                'email'         => 'kasir@speed.test',
                'phone'         => '081234567892',
                'password_hash' => $defaultPassword,
                'is_active'     => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'uuid'          => '44444444-4444-4444-4444-444444444444',
                'role_id'       => 4, // Petugas Dermaga
                'name'          => 'Petugas Scan Sanur',
                'email'         => 'dermaga@speed.test',
                'phone'         => '081234567893',
                'password_hash' => $defaultPassword,
                'is_active'     => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'uuid'          => '55555555-5555-5555-5555-555555555555',
                'role_id'       => 6, // Manajer
                'name'          => 'Budi Santoso (Manajer)',
                'email'         => 'manager@speed.test',
                'phone'         => '081234567894',
                'password_hash' => $defaultPassword,
                'is_active'     => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'uuid'          => '66666666-6666-6666-6666-666666666666',
                'role_id'       => 7, // Member
                'name'          => 'Dewi Lestari',
                'email'         => 'dewi@gmail.com',
                'phone'         => '085712345678',
                'password_hash' => $defaultPassword,
                'is_active'     => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ];
        $db->table('users')->insertBatch($usersData);

        // 4. Seed Companies
        $db->table('companies')->insert([
            'name'       => 'Ocean Express Speed Boat',
            'code'       => 'OCEANEXP',
            'phone'      => '0361-9876543',
            'email'      => 'info@oceanexpress.com',
            'address'    => 'Pelabuhan Sanur, Denpasar, Bali',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $companyId = $db->insertID();

        // 5. Seed Locations (Kota / Dermaga)
        $locationsData = [
            ['code' => 'SANUR', 'name' => 'Pelabuhan Sanur', 'city' => 'Sanur, Denpasar', 'province' => 'Bali', 'description' => 'Dermaga Keberangkatan Sanur Bali', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'NPN', 'name' => 'Banjar Nyuh (Nusa Penida)', 'city' => 'Nusa Penida', 'province' => 'Bali', 'description' => 'Dermaga Utama Banjar Nyuh Nusa Penida', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'NLB', 'name' => 'Jungut Batu (Nusa Lembongan)', 'city' => 'Nusa Lembongan', 'province' => 'Bali', 'description' => 'Pelabuhan Utama Lembongan', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'PDB', 'name' => 'Pelabuhan Padangbai', 'city' => 'Karangasem', 'province' => 'Bali', 'description' => 'Pelabuhan Padangbai Karangasem', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['code' => 'GTI', 'name' => 'Gili Trawangan', 'city' => 'Lombok Utara', 'province' => 'Nusa Tenggara Barat', 'description' => 'Pelabuhan Gili Trawangan', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ];
        $db->table('locations')->insertBatch($locationsData);

        // Get Location IDs
        $sanurId = $db->table('locations')->where('code', 'SANUR')->get()->getRow()->id;
        $npnId   = $db->table('locations')->where('code', 'NPN')->get()->getRow()->id;
        $nlbId   = $db->table('locations')->where('code', 'NLB')->get()->getRow()->id;
        $gtiId   = $db->table('locations')->where('code', 'GTI')->get()->getRow()->id;

        // 6. Seed Speed Boats & Seats
        $boats = [
            ['name' => 'Ocean Express 01', 'code' => 'OEXP-01', 'capacity' => 32, 'rows' => 8, 'cols' => 4],
            ['name' => 'Ocean Express 02', 'code' => 'OEXP-02', 'capacity' => 32, 'rows' => 8, 'cols' => 4],
            ['name' => 'Sea Runner VIP', 'code' => 'SRVIP-01', 'capacity' => 24, 'rows' => 6, 'cols' => 4],
        ];

        $boatIds = [];
        foreach ($boats as $b) {
            $db->table('speed_boats')->insert([
                'company_id'       => $companyId,
                'name'             => $b['name'],
                'code'             => $b['code'],
                'capacity'         => $b['capacity'],
                'total_rows'       => $b['rows'],
                'total_cols'       => $b['cols'],
                'seat_layout_json' => json_encode(['rows' => $b['rows'], 'cols' => $b['cols'], 'aisle_col' => 2]),
                'status'           => 'active',
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);
            $bId = $db->insertID();
            $boatIds[$b['code']] = $bId;

            // Generate Seats
            $colsLetters = ['A', 'B', 'C', 'D'];
            for ($r = 1; $r <= $b['rows']; $r++) {
                for ($c = 1; $c <= $b['cols']; $c++) {
                    $seatNum = $colsLetters[$c - 1] . $r;
                    $db->table('seats')->insert([
                        'speed_boat_id' => $bId,
                        'seat_number'   => $seatNum,
                        'row_num'       => $r,
                        'col_num'       => $c,
                        'seat_class'    => ($r <= 2) ? 'vip' : 'standard',
                        'is_active'     => 1,
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ]);
                }
            }
        }

        // 7. Seed Captains & Crews
        $db->table('captains')->insertBatch([
            ['company_id' => $companyId, 'name' => 'Kapten Made Wijaya', 'license_number' => 'CPT-BALI-8891', 'phone' => '081999888777', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $companyId, 'name' => 'Kapten Wayan Suardana', 'license_number' => 'CPT-BALI-8892', 'phone' => '081999888778', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ]);
        $captain1Id = $db->table('captains')->get()->getFirstRow()->id;

        $db->table('crews')->insertBatch([
            ['company_id' => $companyId, 'name' => 'Ketut Sukarma', 'role_title' => 'ABK Senior', 'phone' => '081777666555', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['company_id' => $companyId, 'name' => 'Agus Prasetya', 'role_title' => 'ABK Engine', 'phone' => '081777666556', 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 8. Seed Routes
        $routesData = [
            ['origin_location_id' => $sanurId, 'destination_location_id' => $npnId, 'distance_nautical_miles' => 15.00, 'estimated_duration_minutes' => 45, 'base_price' => 150000.00, 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['origin_location_id' => $npnId, 'destination_location_id' => $sanurId, 'distance_nautical_miles' => 15.00, 'estimated_duration_minutes' => 45, 'base_price' => 150000.00, 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['origin_location_id' => $sanurId, 'destination_location_id' => $nlbId, 'distance_nautical_miles' => 12.00, 'estimated_duration_minutes' => 35, 'base_price' => 135000.00, 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['origin_location_id' => $sanurId, 'destination_location_id' => $gtiId, 'distance_nautical_miles' => 45.00, 'estimated_duration_minutes' => 120, 'base_price' => 350000.00, 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ];
        $db->table('routes')->insertBatch($routesData);

        $routeSanurNpn = $db->table('routes')->where('origin_location_id', $sanurId)->where('destination_location_id', $npnId)->get()->getRow()->id;
        $routeNpnSanur = $db->table('routes')->where('origin_location_id', $npnId)->where('destination_location_id', $sanurId)->get()->getRow()->id;

        // 9. Seed Master Schedules
        $schedulesData = [
            ['route_id' => $routeSanurNpn, 'speed_boat_id' => $boatIds['OEXP-01'], 'captain_id' => $captain1Id, 'departure_time' => '07:30:00', 'arrival_time' => '08:15:00', 'adult_price' => 150000.00, 'child_price' => 100000.00, 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['route_id' => $routeSanurNpn, 'speed_boat_id' => $boatIds['OEXP-02'], 'captain_id' => $captain1Id, 'departure_time' => '09:00:00', 'arrival_time' => '09:45:00', 'adult_price' => 150000.00, 'child_price' => 100000.00, 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['route_id' => $routeSanurNpn, 'speed_boat_id' => $boatIds['SRVIP-01'], 'captain_id' => $captain1Id, 'departure_time' => '11:30:00', 'arrival_time' => '12:15:00', 'adult_price' => 200000.00, 'child_price' => 150000.00, 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['route_id' => $routeSanurNpn, 'speed_boat_id' => $boatIds['OEXP-01'], 'captain_id' => $captain1Id, 'departure_time' => '14:00:00', 'arrival_time' => '14:45:00', 'adult_price' => 150000.00, 'child_price' => 100000.00, 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
            ['route_id' => $routeNpnSanur, 'speed_boat_id' => $boatIds['OEXP-01'], 'captain_id' => $captain1Id, 'departure_time' => '16:30:00', 'arrival_time' => '17:15:00', 'adult_price' => 150000.00, 'child_price' => 100000.00, 'status' => 'active', 'created_at' => $now, 'updated_at' => $now],
        ];
        $db->table('schedules')->insertBatch($schedulesData);

        // 10. Generate Trips for Next 7 Days
        $schedules = $db->table('schedules')->get()->getResult();
        for ($i = 0; $i < 7; $i++) {
            $tripDate = date('Y-m-d', strtotime("+{$i} days"));
            foreach ($schedules as $sch) {
                $boat = $db->table('speed_boats')->where('id', $sch->speed_boat_id)->get()->getRow();
                $tripCode = 'TRIP-' . date('Ymd', strtotime($tripDate)) . '-' . str_pad($sch->id, 3, '0', STR_PAD_LEFT);
                $db->table('trips')->insert([
                    'schedule_id'     => $sch->id,
                    'trip_code'       => $tripCode,
                    'trip_date'       => $tripDate,
                    'speed_boat_id'   => $sch->speed_boat_id,
                    'captain_id'      => $sch->captain_id,
                    'departure_time'  => $sch->departure_time,
                    'arrival_time'    => $sch->arrival_time,
                    'adult_price'     => $sch->adult_price,
                    'child_price'     => $sch->child_price,
                    'available_seats' => $boat->capacity,
                    'status'          => 'scheduled',
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ]);
            }
        }

        // 11. Seed Vouchers
        $db->table('vouchers')->insertBatch([
            [
                'code'            => 'CITIEXPRESS',
                'title'           => 'Promo Diskon Cititrans Speed Rp 20.000',
                'discount_type'   => 'fixed',
                'discount_value'  => 20000.00,
                'min_transaction' => 150000.00,
                'max_discount'    => 20000.00,
                'quota'           => 500,
                'used_quota'      => 0,
                'start_date'      => date('Y-m-d', strtotime('-10 days')),
                'end_date'        => date('Y-m-d', strtotime('+60 days')),
                'is_active'       => 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'code'            => 'SPEEDLIBURAN',
                'title'           => 'Promo Diskon Liburan 10%',
                'discount_type'   => 'percentage',
                'discount_value'  => 10.00,
                'min_transaction' => 200000.00,
                'max_discount'    => 50000.00,
                'quota'           => 200,
                'used_quota'      => 0,
                'start_date'      => date('Y-m-d', strtotime('-10 days')),
                'end_date'        => date('Y-m-d', strtotime('+60 days')),
                'is_active'       => 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]
        ]);
    }
}
