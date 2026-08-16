<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-1"><i class="bi bi-calendar-week-fill text-primary me-2"></i> Master Jadwal Keberangkatan</h5>
            <p class="text-secondary small mb-0">Kelola master jam keberangkatan dan penetapan armada speedboat</p>
        </div>
        <button class="btn btn-primary rounded-3 fw-bold px-4 py-2" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
            <i class="bi bi-plus-circle-fill me-2"></i> Buat Jadwal Baru
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-dark">
                <tr>
                    <th>Rute Penyeberangan</th>
                    <th>Jam Keberangkatan</th>
                    <th>Jam Tiba</th>
                    <th>Armada Speedboat</th>
                    <th>Kapten</th>
                    <th>Harga Tiket</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($schedules)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Belum ada master jadwal keberangkatan.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($schedules as $s): ?>
                        <tr>
                            <td>
                                <strong class="text-dark">
                                    <i class="bi bi-geo-alt-fill text-primary me-1"></i><?= esc($s['origin_city']) ?> (<?= esc($s['origin_name']) ?>)
                                    &rarr;
                                    <i class="bi bi-pin-map-fill text-danger me-1"></i><?= esc($s['destination_city']) ?> (<?= esc($s['destination_name']) ?>)
                                </strong>
                            </td>
                            <td><span class="badge bg-primary fs-6"><?= date('H:i', strtotime($s['departure_time'])) ?> WITA</span></td>
                            <td><span class="text-muted"><?= date('H:i', strtotime($s['arrival_time'])) ?> WITA</span></td>
                            <td><strong><i class="bi bi-water text-cyan me-1"></i><?= esc($s['boat_name']) ?></strong></td>
                            <td><i class="bi bi-person-badge me-1"></i><?= esc($s['captain_name'] ?? 'Kapten Made') ?></td>
                            <td><span class="fw-bold text-success">Rp <?= number_format($s['adult_price'], 0, ',', '.') ?></span></td>
                            <td>
                                <span class="badge bg-<?= ($s['status'] ?? 'active') === 'active' ? 'success' : 'danger' ?>">
                                    <?= strtoupper($s['status'] ?? 'ACTIVE') ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary me-1 rounded-3 btn-edit-schedule"
                                    data-id="<?= $s['id'] ?>"
                                    data-route="<?= $s['route_id'] ?>"
                                    data-boat="<?= $s['speed_boat_id'] ?>"
                                    data-captain="<?= $s['captain_id'] ?? '' ?>"
                                    data-departure="<?= date('H:i', strtotime($s['departure_time'])) ?>"
                                    data-arrival="<?= date('H:i', strtotime($s['arrival_time'])) ?>"
                                    data-price="<?= (float) $s['adult_price'] ?>"
                                    data-status="<?= esc($s['status'] ?? 'active') ?>"
                                    title="Edit Jadwal">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>

                                <a href="<?= base_url('admin/master/schedules/delete/' . $s['id']) ?>" 
                                   class="btn btn-sm btn-outline-danger rounded-3" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus jadwal rute <?= esc($s['origin_city']) ?> -> <?= esc($s['destination_city']) ?> (Jam <?= date('H:i', strtotime($s['departure_time'])) ?>)?')"
                                   title="Hapus Jadwal">
                                    <i class="bi bi-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Schedule -->
<div class="modal fade" id="addScheduleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i> Buat Jadwal Keberangkatan Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/master/schedules/store') ?>" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih Rute Penyeberangan</label>
                        <select name="route_id" class="form-select" required>
                            <?php foreach ($routes as $r): ?>
                                <option value="<?= $r['id'] ?>">
                                    Rute #<?= $r['id'] ?>: <?= esc($r['origin_city']) ?> (<?= esc($r['origin_name']) ?>) &rarr; <?= esc($r['destination_city']) ?> (<?= esc($r['destination_name']) ?>) - Rp <?= number_format($r['base_price'], 0, ',', '.') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Armada Speed Boat</label>
                        <select name="speed_boat_id" class="form-select" required>
                            <?php foreach ($boats as $b): ?>
                                <option value="<?= $b['id'] ?>"><?= esc($b['name']) ?> (<?= esc($b['code']) ?> - <?= esc($b['capacity']) ?> Kursi)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kapten Kapal</label>
                        <select name="captain_id" class="form-select">
                            <?php foreach ($captains as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?> (Lisensi: <?= esc($c['license_number']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Jam Keberangkatan</label>
                            <input type="time" name="departure_time" class="form-control" value="08:00" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Jam Tiba Estimasi</label>
                            <input type="time" name="arrival_time" class="form-control" value="08:45" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Harga Tiket Dewasa (Rp)</label>
                        <input type="number" name="adult_price" class="form-control" value="150000" required>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold rounded-3 px-4"><i class="bi bi-save me-1"></i> Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Schedule -->
<div class="modal fade" id="editScheduleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i> Edit Master Jadwal Keberangkatan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editScheduleForm" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Pilih Rute Penyeberangan</label>
                        <select id="editRoute" name="route_id" class="form-select" required>
                            <?php foreach ($routes as $r): ?>
                                <option value="<?= $r['id'] ?>">
                                    Rute #<?= $r['id'] ?>: <?= esc($r['origin_city']) ?> (<?= esc($r['origin_name']) ?>) &rarr; <?= esc($r['destination_city']) ?> (<?= esc($r['destination_name']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Armada Speed Boat</label>
                        <select id="editBoat" name="speed_boat_id" class="form-select" required>
                            <?php foreach ($boats as $b): ?>
                                <option value="<?= $b['id'] ?>"><?= esc($b['name']) ?> (<?= esc($b['code']) ?> - <?= esc($b['capacity']) ?> Kursi)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kapten Kapal</label>
                        <select id="editCaptain" name="captain_id" class="form-select">
                            <?php foreach ($captains as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?> (Lisensi: <?= esc($c['license_number']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Jam Keberangkatan</label>
                            <input type="time" id="editDeparture" name="departure_time" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Jam Tiba Estimasi</label>
                            <input type="time" id="editArrival" name="arrival_time" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Harga Tiket Dewasa (Rp)</label>
                        <input type="number" id="editPrice" name="adult_price" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Status Jadwal</label>
                        <select id="editStatus" name="status" class="form-select">
                            <option value="active">ACTIVE</option>
                            <option value="inactive">INACTIVE</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark fw-bold rounded-3 px-4"><i class="bi bi-save me-1"></i> Perbarui Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const editModal = new bootstrap.Modal(document.getElementById('editScheduleModal'));
    const editForm = document.getElementById('editScheduleForm');

    document.querySelectorAll('.btn-edit-schedule').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            editForm.action = '<?= base_url("admin/master/schedules/update") ?>/' + id;
            document.getElementById('editRoute').value = this.dataset.route;
            document.getElementById('editBoat').value = this.dataset.boat;
            if (this.dataset.captain) {
                document.getElementById('editCaptain').value = this.dataset.captain;
            }
            document.getElementById('editDeparture').value = this.dataset.departure;
            document.getElementById('editArrival').value = this.dataset.arrival;
            document.getElementById('editPrice').value = this.dataset.price;
            document.getElementById('editStatus').value = this.dataset.status;
            editModal.show();
        });
    });
});
</script>
<?= $this->endSection() ?>
