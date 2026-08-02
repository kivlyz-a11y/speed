<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use App\Models\LocationModel;

class HomeController extends BaseController
{
    protected $locationModel;

    public function __construct()
    {
        $this->locationModel = new LocationModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        // Query ONLY origin locations that have active routes in database
        $locations = $db->table('routes r')
            ->select('loc.id, loc.name, loc.city, loc.code')
            ->join('locations loc', 'loc.id = r.origin_location_id')
            ->where('r.status', 'active')
            ->where('r.deleted_at IS NULL')
            ->where('loc.status', 'active')
            ->where('loc.deleted_at IS NULL')
            ->groupBy('loc.id')
            ->get()->getResultArray();

        if (empty($locations)) {
            $locations = $this->locationModel->where('status', 'active')->findAll();
        }

        $popularRoutes = $db->table('routes r')
            ->select('r.*, loc1.name as origin_name, loc1.city as origin_city, loc2.name as destination_name, loc2.city as destination_city')
            ->join('locations loc1', 'loc1.id = r.origin_location_id')
            ->join('locations loc2', 'loc2.id = r.destination_location_id')
            ->where('r.status', 'active')
            ->where('r.deleted_at IS NULL')
            ->groupBy('r.origin_location_id, r.destination_location_id')
            ->limit(6)
            ->get()->getResultArray();

        $data = [
            'title'         => 'SpeedExpress - Pemesanan Tiket Speed Boat Modern',
            'locations'     => $locations,
            'popularRoutes' => $popularRoutes
        ];

        return view('front/home', $data);
    }
}
