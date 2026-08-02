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
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedules as $s): ?>
                    <tr>
                        <td><strong class="text-dark"><?= esc($s['origin_city']) ?> &rarr; <?= esc($s['destination_city']) ?></strong></td>
                        <td><span class="badge bg-primary fs-6"><?= date('H:i', strtotime($s['departure_time'])) ?> WITA</span></td>
                        <td><span class="text-muted"><?= date('H:i', strtotime($s['arrival_time'])) ?> WITA</span></td>
                        <td><strong><?= esc($s['boat_name']) ?></strong></td>
                        <td><?= esc($s['captain_name'] ?? 'Kapten Made') ?></td>
                        <td><span class="fw-bold text-success">Rp <?= number_format($s['adult_price'], 0, ',', '.') ?></span></td>
                        <td><span class="badge bg-success">ACTIVE</span></td>
                    </tr>
                <?php endforeach; ?>
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
                        <label class="form-label small fw-bold">Pilih Rute</label>
                        <select name="route_id" class="form-select" required>
                            <?php foreach ($routes as $r): ?>
                                <option value="<?= $r['id'] ?>">ID: <?= $r['id'] ?> (Rp <?= number_format($r['base_price'], 0, ',', '.') ?>)</option>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="bi bi-save me-1"></i> Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
