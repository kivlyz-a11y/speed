<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SpeedBoatModel;
use App\Models\LocationModel;
use App\Models\RouteModel;
use App\Models\ScheduleModel;
use App\Models\VoucherModel;
use App\Models\CaptainModel;
use App\Models\SeatModel;

class MasterController extends BaseController
{
    protected $boatModel;
    protected $locationModel;
    protected $routeModel;
    protected $scheduleModel;
    protected $voucherModel;
    protected $captainModel;
    protected $seatModel;

    public function __construct()
    {
        $this->boatModel     = new SpeedBoatModel();
        $this->locationModel = new LocationModel();
        $this->routeModel    = new RouteModel();
        $this->scheduleModel = new ScheduleModel();
        $this->voucherModel  = new VoucherModel();
        $this->captainModel  = new CaptainModel();
        $this->seatModel     = new SeatModel();
    }

    // ==========================================
    // LOCATIONS (KOTA ASAL & TUJUAN) CRUD
    // ==========================================
    public function locations()
    {
        return view('admin/master/locations', [
            'title'     => 'Master Kota & Lokasi Dermaga',
            'locations' => $this->locationModel->findAll()
        ]);
    }

    public function storeLocation()
    {
        $code        = strtoupper(trim($this->request->getPost('code')));
        $name        = trim($this->request->getPost('name'));
        $city        = trim($this->request->getPost('city'));
        $province    = trim($this->request->getPost('province'));
        $description = trim($this->request->getPost('description'));

        if ($this->locationModel->where('code', $code)->first()) {
            return redirect()->back()->with('error', "Kode Lokasi [{$code}] sudah digunakan!");
        }

        $this->locationModel->insert([
            'code'        => $code,
            'name'        => $name,
            'city'        => $city,
            'province'    => $province,
            'description' => $description,
            'status'      => 'active'
        ]);

        return redirect()->to('admin/master/locations')->with('success', 'Lokasi/Dermaga baru berhasil ditambahkan!');
    }

    public function updateLocation(int $id)
    {
        $location = $this->locationModel->find($id);
        if (!$location) {
            return redirect()->back()->with('error', 'Data lokasi tidak ditemukan.');
        }

        $this->locationModel->update($id, [
            'code'        => strtoupper(trim($this->request->getPost('code'))),
            'name'        => trim($this->request->getPost('name')),
            'city'        => trim($this->request->getPost('city')),
            'province'    => trim($this->request->getPost('province')),
            'description' => trim($this->request->getPost('description')),
            'status'      => $this->request->getPost('status') ?? 'active'
        ]);

        return redirect()->to('admin/master/locations')->with('success', 'Data Lokasi/Dermaga berhasil diperbarui!');
    }

    public function deleteLocation(int $id)
    {
        $this->locationModel->delete($id);
        return redirect()->to('admin/master/locations')->with('success', 'Lokasi/Dermaga berhasil dihapus.');
    }

    // ==========================================
    // SPEED BOATS CRUD
    // ==========================================
    public function boats()
    {
        return view('admin/master/boats', [
            'title' => 'Master Speed Boat',
            'boats' => $this->boatModel->findAll()
        ]);
    }

    public function storeBoat()
    {
        $code     = strtoupper(trim($this->request->getPost('code')));
        $name     = trim($this->request->getPost('name'));
        $capacity = (int) $this->request->getPost('capacity');
        $rows     = (int) ($this->request->getPost('total_rows') ?? 8);
        $cols     = (int) ($this->request->getPost('total_cols') ?? 4);

        $boatId = $this->boatModel->insert([
            'company_id'       => 1,
            'name'             => $name,
            'code'             => $code,
            'capacity'         => $capacity,
            'total_rows'       => $rows,
            'total_cols'       => $cols,
            'seat_layout_json' => json_encode(['rows' => $rows, 'cols' => $cols, 'aisle_col' => 2]),
            'status'           => 'active'
        ]);

        // Auto Generate Seats for new boat
        $colsLetters = ['A', 'B', 'C', 'D', 'E', 'F'];
        for ($r = 1; $r <= $rows; $r++) {
            for ($c = 1; $c <= $cols; $c++) {
                $seatNum = ($colsLetters[$c - 1] ?? 'A') . $r;
                $this->seatModel->insert([
                    'speed_boat_id' => $boatId,
                    'seat_number'   => $seatNum,
                    'row_num'       => $r,
                    'col_num'       => $c,
                    'seat_class'    => ($r <= 2) ? 'vip' : 'standard',
                    'is_active'     => 1
                ]);
            }
        }

        return redirect()->to('admin/master/boats')->with('success', 'Speedboat & denah kursi berhasil ditambahkan!');
    }

    public function updateBoat(int $id)
    {
        $this->boatModel->update($id, [
            'name'   => trim($this->request->getPost('name')),
            'status' => $this->request->getPost('status') ?? 'active'
        ]);
        return redirect()->to('admin/master/boats')->with('success', 'Data Armada berhasil diperbarui!');
    }

    // ==========================================
    // ROUTES CRUD
    // ==========================================
    public function routes()
    {
        $db = \Config\Database::connect();
        $routes = $db->table('routes r')
            ->select('r.*, loc1.name as origin_name, loc1.city as origin_city, loc2.name as destination_name, loc2.city as destination_city')
            ->join('locations loc1', 'loc1.id = r.origin_location_id')
            ->join('locations loc2', 'loc2.id = r.destination_location_id')
            ->where('r.deleted_at IS NULL')
            ->get()->getResultArray();

        return view('admin/master/routes', [
            'title'     => 'Master Rute Transportasi',
            'routes'    => $routes,
            'locations' => $this->locationModel->findAll()
        ]);
    }

    public function storeRoute()
    {
        $originId      = (int) $this->request->getPost('origin_location_id');
        $destinationId = (int) $this->request->getPost('destination_location_id');

        if ($originId === $destinationId) {
            return redirect()->back()->with('error', 'Kota Asal dan Kota Tujuan tidak boleh sama!');
        }

        $this->routeModel->insert([
            'origin_location_id'          => $originId,
            'destination_location_id'     => $destinationId,
            'distance_nautical_miles'     => (float) $this->request->getPost('distance_nautical_miles'),
            'estimated_duration_minutes' => (int) $this->request->getPost('estimated_duration_minutes'),
            'base_price'                  => (float) $this->request->getPost('base_price'),
            'status'                      => 'active'
        ]);

        return redirect()->to('admin/master/routes')->with('success', 'Rute Penyeberangan Baru berhasil ditambahkan!');
    }

    public function updateRoute(int $id)
    {
        $route = $this->routeModel->find($id);
        if (!$route) {
            return redirect()->back()->with('error', 'Data rute tidak ditemukan.');
        }

        $originId      = (int) $this->request->getPost('origin_location_id');
        $destinationId = (int) $this->request->getPost('destination_location_id');

        if ($originId === $destinationId) {
            return redirect()->back()->with('error', 'Kota Asal dan Kota Tujuan tidak boleh sama!');
        }

        $this->routeModel->update($id, [
            'origin_location_id'          => $originId,
            'destination_location_id'     => $destinationId,
            'distance_nautical_miles'     => (float) $this->request->getPost('distance_nautical_miles'),
            'estimated_duration_minutes' => (int) $this->request->getPost('estimated_duration_minutes'),
            'base_price'                  => (float) $this->request->getPost('base_price'),
            'status'                      => $this->request->getPost('status') ?? 'active'
        ]);

        return redirect()->to('admin/master/routes')->with('success', 'Data Rute Penyeberangan berhasil diperbarui!');
    }

    public function deleteRoute(int $id)
    {
        $this->routeModel->delete($id);
        return redirect()->to('admin/master/routes')->with('success', 'Rute penyeberangan berhasil dihapus.');
    }

    // ==========================================
    // SCHEDULES & VOUCHERS CRUD
    // ==========================================
    public function schedules()
    {
        $db = \Config\Database::connect();
        $schedules = $db->table('schedules sch')
            ->select('sch.*, sb.name as boat_name, c.name as captain_name,
                      loc1.city as origin_city, loc2.city as destination_city')
            ->join('speed_boats sb', 'sb.id = sch.speed_boat_id')
            ->join('routes r', 'r.id = sch.route_id')
            ->join('locations loc1', 'loc1.id = r.origin_location_id')
            ->join('locations loc2', 'loc2.id = r.destination_location_id')
            ->join('captains c', 'c.id = sch.captain_id', 'left')
            ->get()->getResultArray();

        return view('admin/master/schedules', [
            'title'     => 'Master Jadwal Keberangkatan',
            'schedules' => $schedules,
            'boats'     => $this->boatModel->findAll(),
            'routes'    => $this->routeModel->findAll(),
            'captains'  => $this->captainModel->findAll()
        ]);
    }

    public function storeSchedule()
    {
        $schId = $this->scheduleModel->insert([
            'route_id'       => (int) $this->request->getPost('route_id'),
            'speed_boat_id'  => (int) $this->request->getPost('speed_boat_id'),
            'captain_id'     => (int) ($this->request->getPost('captain_id') ?: 1),
            'departure_time' => $this->request->getPost('departure_time'),
            'arrival_time'   => $this->request->getPost('arrival_time'),
            'adult_price'    => (float) $this->request->getPost('adult_price'),
            'child_price'    => (float) ($this->request->getPost('child_price') ?: 0),
            'status'         => 'active'
        ]);

        // Auto Generate Daily Trips for next 7 days for this schedule
        $boat = $this->boatModel->find((int)$this->request->getPost('speed_boat_id'));
        $capacity = $boat ? $boat['capacity'] : 30;

        $db = \Config\Database::connect();
        for ($i = 0; $i < 7; $i++) {
            $tripDate = date('Y-m-d', strtotime("+{$i} days"));
            $tripCode = 'TRIP-' . date('Ymd', strtotime($tripDate)) . '-' . str_pad($schId, 3, '0', STR_PAD_LEFT);
            $db->table('trips')->insert([
                'schedule_id'     => $schId,
                'trip_code'       => $tripCode,
                'trip_date'       => $tripDate,
                'speed_boat_id'   => (int)$this->request->getPost('speed_boat_id'),
                'captain_id'      => (int)($this->request->getPost('captain_id') ?: 1),
                'departure_time'  => $this->request->getPost('departure_time'),
                'arrival_time'    => $this->request->getPost('arrival_time'),
                'adult_price'     => (float)$this->request->getPost('adult_price'),
                'available_seats' => $capacity,
                'status'          => 'scheduled'
            ]);
        }

        return redirect()->to('admin/master/schedules')->with('success', 'Master Jadwal & Trip Harian berhasil dibuat!');
    }

    public function vouchers()
    {
        return view('admin/master/vouchers', [
            'title'    => 'Master Promo Voucher',
            'vouchers' => $this->voucherModel->findAll()
        ]);
    }

    public function storeVoucher()
    {
        $this->voucherModel->insert([
            'code'            => strtoupper(trim($this->request->getPost('code'))),
            'title'           => trim($this->request->getPost('title')),
            'discount_type'   => $this->request->getPost('discount_type'),
            'discount_value'  => (float) $this->request->getPost('discount_value'),
            'min_transaction' => (float) $this->request->getPost('min_transaction'),
            'max_discount'    => (float) $this->request->getPost('max_discount'),
            'quota'           => (int) $this->request->getPost('quota'),
            'start_date'      => $this->request->getPost('start_date'),
            'end_date'        => $this->request->getPost('end_date'),
            'is_active'       => 1
        ]);

        return redirect()->to('admin/master/vouchers')->with('success', 'Voucher Promo berhasil ditambahkan!');
    }
}
