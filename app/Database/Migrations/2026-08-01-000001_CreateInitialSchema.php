<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInitialSchema extends Migration
{
    public function up()
    {
        // 1. Roles Table
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 50],
            'slug'        => ['type' => 'VARCHAR', 'constraint' => 50],
            'description' => ['type' => 'TEXT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('roles', true);

        // 2. Permissions Table
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'slug'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'module'      => ['type' => 'VARCHAR', 'constraint' => 50],
            'description' => ['type' => 'TEXT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->createTable('permissions', true);

        // 3. Role Permissions Pivot
        $this->forge->addField([
            'role_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'permission_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addKey(['role_id', 'permission_id'], true);
        $this->forge->addForeignKey('role_id', 'roles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('permission_id', 'permissions', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('role_permissions', true);

        // 4. Users Table
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'uuid'          => ['type' => 'VARCHAR', 'constraint' => 36],
            'role_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'name'          => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'phone'         => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'password_hash' => ['type' => 'VARCHAR', 'constraint' => 255],
            'avatar'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'is_active'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addForeignKey('role_id', 'roles', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('users', true);

        // 5. User Activity Logs
        $this->forge->addField([
            'id'          => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'user_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'action'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'description' => ['type' => 'TEXT', 'null' => true],
            'ip_address'  => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'user_agent'  => ['type' => 'TEXT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('user_activity_logs', true);

        // 6. Companies Table (Operator Speedboat)
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'code'       => ['type' => 'VARCHAR', 'constraint' => 20],
            'phone'      => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'address'    => ['type' => 'TEXT', 'null' => true],
            'logo'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('companies', true);

        // 7. Locations Table (Kota Asal / Kota Tujuan / Dermaga)
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'code'        => ['type' => 'VARCHAR', 'constraint' => 20],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'city'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'province'    => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'address'     => ['type' => 'TEXT', 'null' => true],
            'description' => ['type' => 'TEXT', 'null' => true],
            'status'      => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('locations', true);

        // 8. Speed Boats Table
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'company_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'name'             => ['type' => 'VARCHAR', 'constraint' => 100],
            'code'             => ['type' => 'VARCHAR', 'constraint' => 30],
            'capacity'         => ['type' => 'INT', 'constraint' => 5, 'default' => 30],
            'total_rows'       => ['type' => 'INT', 'constraint' => 3, 'default' => 8],
            'total_cols'       => ['type' => 'INT', 'constraint' => 3, 'default' => 4],
            'seat_layout_json' => ['type' => 'LONGTEXT', 'null' => true],
            'status'           => ['type' => 'ENUM', 'constraint' => ['active', 'maintenance', 'inactive'], 'default' => 'active'],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code');
        $this->forge->addForeignKey('company_id', 'companies', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('speed_boats', true);

        // 9. Seats Master Table
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'speed_boat_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'seat_number'   => ['type' => 'VARCHAR', 'constraint' => 10],
            'row_num'       => ['type' => 'INT', 'constraint' => 3],
            'col_num'       => ['type' => 'INT', 'constraint' => 3],
            'seat_class'    => ['type' => 'ENUM', 'constraint' => ['standard', 'vip'], 'default' => 'standard'],
            'is_active'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('speed_boat_id', 'speed_boats', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('seats', true);

        // 10. Captains Table
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'company_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'name'           => ['type' => 'VARCHAR', 'constraint' => 100],
            'license_number' => ['type' => 'VARCHAR', 'constraint' => 50],
            'phone'          => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'status'         => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('company_id', 'companies', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('captains', true);

        // 11. Crews Table
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'company_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'role_title' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'Abk'],
            'phone'      => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'status'     => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('company_id', 'companies', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('crews', true);

        // 12. Routes Table
        $this->forge->addField([
            'id'                          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'origin_location_id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'destination_location_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'distance_nautical_miles'     => ['type' => 'DECIMAL', 'constraint' => '8,2', 'default' => 0.00],
            'estimated_duration_minutes' => ['type' => 'INT', 'constraint' => 5, 'default' => 45],
            'base_price'                  => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 150000.00],
            'status'                      => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'created_at'                  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'                  => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'                  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('origin_location_id', 'locations', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('destination_location_id', 'locations', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('routes', true);

        // 13. Master Schedules Table
        $this->forge->addField([
            'id'                    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'route_id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'speed_boat_id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'captain_id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'departure_time'        => ['type' => 'TIME'],
            'arrival_time'          => ['type' => 'TIME'],
            'operational_days_mask' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => '1,2,3,4,5,6,7'],
            'adult_price'           => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'child_price'           => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0.00],
            'status'                => ['type' => 'ENUM', 'constraint' => ['active', 'inactive'], 'default' => 'active'],
            'created_at'            => ['type' => 'DATETIME', 'null' => true],
            'updated_at'            => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'            => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('route_id', 'routes', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('speed_boat_id', 'speed_boats', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('captain_id', 'captains', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('schedules', true);

        // 14. Schedule Holidays
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'schedule_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'holiday_date' => ['type' => 'DATE'],
            'reason'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('schedule_id', 'schedules', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('schedule_holidays', true);

        // 15. Trips (Daily Trip Instances)
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'schedule_id'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'trip_code'       => ['type' => 'VARCHAR', 'constraint' => 40],
            'trip_date'       => ['type' => 'DATE'],
            'speed_boat_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'captain_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'departure_time'  => ['type' => 'TIME'],
            'arrival_time'    => ['type' => 'TIME'],
            'adult_price'     => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'child_price'     => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0.00],
            'available_seats' => ['type' => 'INT', 'constraint' => 5],
            'status'          => ['type' => 'ENUM', 'constraint' => ['scheduled', 'boarding', 'departed', 'arrived', 'cancelled'], 'default' => 'scheduled'],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('trip_code');
        $this->forge->addForeignKey('schedule_id', 'schedules', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('speed_boat_id', 'speed_boats', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('captain_id', 'captains', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('trips', true);

        // 16. Trip Crews Pivot
        $this->forge->addField([
            'id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'trip_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'crew_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'role'    => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'ABK'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('trip_id', 'trips', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('crew_id', 'crews', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('trip_crews', true);

        // 17. Vouchers / Promos
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'code'            => ['type' => 'VARCHAR', 'constraint' => 30],
            'title'           => ['type' => 'VARCHAR', 'constraint' => 100],
            'discount_type'   => ['type' => 'ENUM', 'constraint' => ['fixed', 'percentage'], 'default' => 'fixed'],
            'discount_value'  => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'min_transaction' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0.00],
            'max_discount'    => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0.00],
            'quota'           => ['type' => 'INT', 'constraint' => 11, 'default' => 100],
            'used_quota'      => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'start_date'      => ['type' => 'DATE'],
            'end_date'        => ['type' => 'DATE'],
            'is_active'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('vouchers', true);

        // 18. Bookings Table
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'uuid'             => ['type' => 'VARCHAR', 'constraint' => 36],
            'booking_code'     => ['type' => 'VARCHAR', 'constraint' => 30],
            'user_id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'trip_id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'booking_type'     => ['type' => 'ENUM', 'constraint' => ['online', 'offline', 'agent', 'corporate'], 'default' => 'online'],
            'customer_name'    => ['type' => 'VARCHAR', 'constraint' => 100],
            'customer_email'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'customer_phone'   => ['type' => 'VARCHAR', 'constraint' => 20],
            'total_passengers' => ['type' => 'INT', 'constraint' => 3],
            'total_amount'     => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'discount_amount'  => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0.00],
            'final_amount'     => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'voucher_code'     => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'status'           => ['type' => 'ENUM', 'constraint' => ['pending', 'confirmed', 'completed', 'cancelled', 'rescheduled', 'refunded'], 'default' => 'pending'],
            'payment_status'   => ['type' => 'ENUM', 'constraint' => ['unpaid', 'paid', 'expired', 'failed'], 'default' => 'unpaid'],
            'expired_at'       => ['type' => 'DATETIME'],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('booking_code');
        $this->forge->addForeignKey('trip_id', 'trips', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('bookings', true);

        // 19. Booking Passengers Table (Nama & Kontak Saja)
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'booking_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'seat_id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'passenger_name'  => ['type' => 'VARCHAR', 'constraint' => 100],
            'passenger_phone' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'passenger_type'  => ['type' => 'ENUM', 'constraint' => ['adult', 'child'], 'default' => 'adult'],
            'seat_number'     => ['type' => 'VARCHAR', 'constraint' => 10],
            'price'           => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'created_at'      => ['type' => 'DATETIME', 'null' => true],
            'updated_at'      => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('seat_id', 'seats', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('booking_passengers', true);

        // 20. Seat Locks Table (Locking sementara)
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'trip_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'seat_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'session_id'   => ['type' => 'VARCHAR', 'constraint' => 100],
            'user_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'locked_until' => ['type' => 'DATETIME'],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('trip_id', 'trips', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('seat_id', 'seats', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('seat_locks', true);

        // 21. Payments Table
        $this->forge->addField([
            'id'                 => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'uuid'               => ['type' => 'VARCHAR', 'constraint' => 36],
            'booking_id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'payment_code'       => ['type' => 'VARCHAR', 'constraint' => 50],
            'gateway_type'       => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'midtrans'],
            'payment_method'     => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'gross_amount'       => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'transaction_status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'pending'],
            'transaction_time'   => ['type' => 'DATETIME', 'null' => true],
            'snap_token'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'redirect_url'       => ['type' => 'TEXT', 'null' => true],
            'raw_response'       => ['type' => 'LONGTEXT', 'null' => true],
            'created_at'         => ['type' => 'DATETIME', 'null' => true],
            'updated_at'         => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('payment_code');
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('payments', true);

        // 22. Payment Logs
        $this->forge->addField([
            'id'           => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'payment_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'status'       => ['type' => 'VARCHAR', 'constraint' => 50],
            'payload_json' => ['type' => 'LONGTEXT', 'null' => true],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('payment_id', 'payments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('payment_logs', true);

        // 23. Tickets Table
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'uuid'          => ['type' => 'VARCHAR', 'constraint' => 36],
            'ticket_code'   => ['type' => 'VARCHAR', 'constraint' => 40],
            'booking_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'passenger_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'trip_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'seat_number'   => ['type' => 'VARCHAR', 'constraint' => 10],
            'qr_code_path'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status'        => ['type' => 'ENUM', 'constraint' => ['active', 'checked_in', 'cancelled', 'refunded'], 'default' => 'active'],
            'checked_in_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('ticket_code');
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('passenger_id', 'booking_passengers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('trip_id', 'trips', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('tickets', true);

        // 24. Check In Logs
        $this->forge->addField([
            'id'                 => ['type' => 'BIGINT', 'constraint' => 20, 'unsigned' => true, 'auto_increment' => true],
            'ticket_id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'trip_id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'scanned_by_user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'scan_time'          => ['type' => 'DATETIME'],
            'device_info'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'status'             => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'success'],
            'created_at'         => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('ticket_id', 'tickets', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('trip_id', 'trips', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('scanned_by_user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('check_in_logs', true);

        // 25. Boarding Manifests Table
        $this->forge->addField([
            'id'                   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'trip_id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'total_checked_in'     => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'total_absent'         => ['type' => 'INT', 'constraint' => 5, 'default' => 0],
            'manifest_pdf_path'    => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'finalized_at'         => ['type' => 'DATETIME', 'null' => true],
            'finalized_by_user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'created_at'           => ['type' => 'DATETIME', 'null' => true],
            'updated_at'           => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('trip_id', 'trips', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('finalized_by_user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('boarding_manifests', true);

        // 26. Refunds Table
        $this->forge->addField([
            'id'                   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'booking_id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'refund_code'          => ['type' => 'VARCHAR', 'constraint' => 30],
            'reason'               => ['type' => 'TEXT'],
            'total_paid'           => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'deduction_percentage' => ['type' => 'DECIMAL', 'constraint' => '5,2', 'default' => 10.00],
            'deduction_amount'     => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'refund_amount'        => ['type' => 'DECIMAL', 'constraint' => '12,2'],
            'bank_name'            => ['type' => 'VARCHAR', 'constraint' => 50],
            'account_number'       => ['type' => 'VARCHAR', 'constraint' => 50],
            'account_holder'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'status'               => ['type' => 'ENUM', 'constraint' => ['requested', 'approved', 'rejected', 'completed'], 'default' => 'requested'],
            'approved_by_user_id'  => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'processed_at'         => ['type' => 'DATETIME', 'null' => true],
            'created_at'           => ['type' => 'DATETIME', 'null' => true],
            'updated_at'           => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('refund_code');
        $this->forge->addForeignKey('booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('approved_by_user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('refunds', true);

        // 27. Reschedules Table
        $this->forge->addField([
            'id'                   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'old_booking_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'new_booking_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'reschedule_code'      => ['type' => 'VARCHAR', 'constraint' => 30],
            'old_trip_id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'new_trip_id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'fee_amount'           => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 25000.00],
            'price_diff_amount'    => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0.00],
            'total_additional_pay' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 25000.00],
            'status'               => ['type' => 'ENUM', 'constraint' => ['pending', 'approved', 'completed', 'rejected'], 'default' => 'pending'],
            'processed_by_user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'created_at'           => ['type' => 'DATETIME', 'null' => true],
            'updated_at'           => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('reschedule_code');
        $this->forge->addForeignKey('old_booking_id', 'bookings', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('old_trip_id', 'trips', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->addForeignKey('new_trip_id', 'trips', 'id', 'RESTRICT', 'CASCADE');
        $this->forge->createTable('reschedules', true);
    }

    public function down()
    {
        $tables = [
            'reschedules', 'refunds', 'boarding_manifests', 'check_in_logs', 'tickets',
            'payment_logs', 'payments', 'seat_locks', 'booking_passengers', 'bookings',
            'vouchers', 'trip_crews', 'trips', 'schedule_holidays', 'schedules',
            'routes', 'crews', 'captains', 'seats', 'speed_boats', 'locations',
            'companies', 'user_activity_logs', 'users', 'role_permissions',
            'permissions', 'roles'
        ];

        foreach ($tables as $table) {
            $this->forge->dropTable($table, true);
        }
    }
}
