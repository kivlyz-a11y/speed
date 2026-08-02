<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-1"><i class="bi bi-signpost-split-fill text-primary me-2"></i> Master Rute Transportasi</h5>
            <p class="text-secondary small mb-0">Kelola rute penyeberangan antar kota/dermaga, jarak nautical miles, estimasi durasi, dan harga dasar</p>
        </div>
        <button class="btn btn-primary rounded-3 fw-bold px-4 py-2" data-bs-toggle="modal" data-bs-target="#addRouteModal">
            <i class="bi bi-plus-circle-fill me-2"></i> Tambah Rute Baru
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-dark">
                <tr>
                    <th>Kota / Dermaga Asal</th>
                    <th>Kota / Dermaga Tujuan</th>
                    <th>Jarak (NM)</th>
                    <th>Estimasi Durasi</th>
                    <th>Harga Dasar</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($routes)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada rute penyeberangan.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($routes as $r): ?>
                        <tr>
                            <td><strong class="text-dark"><i class="bi bi-geo-alt-fill text-primary me-1"></i> <?= esc($r['origin_name']) ?> (<?= esc($r['origin_city']) ?>)</strong></td>
                            <td><strong class="text-dark"><i class="bi bi-pin-map-fill text-danger me-1"></i> <?= esc($r['destination_name']) ?> (<?= esc($r['destination_city']) ?>)</strong></td>
                            <td><span class="badge bg-secondary"><?= esc($r['distance_nautical_miles']) ?> NM</span></td>
                            <td><span class="badge bg-info text-dark"><i class="bi bi-clock me-1"></i> <?= esc($r['estimated_duration_minutes']) ?> mnt</span></td>
                            <td><span class="fw-extrabold text-success">Rp <?= number_format($r['base_price'], 0, ',', '.') ?></span></td>
                            <td>
                                <span class="badge bg-<?= $r['status'] === 'active' ? 'success' : 'danger' ?>">
                                    <?= strtoupper($r['status']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary me-1 rounded-3 btn-edit-route"
                                    data-id="<?= $r['id'] ?>"
                                    data-origin="<?= $r['origin_location_id'] ?>"
                                    data-destination="<?= $r['destination_location_id'] ?>"
                                    data-distance="<?= $r['distance_nautical_miles'] ?>"
                                    data-duration="<?= $r['estimated_duration_minutes'] ?>"
                                    data-price="<?= $r['base_price'] ?>"
                                    data-status="<?= $r['status'] ?>"
                                    title="Edit Rute">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>

                                <a href="<?= base_url('admin/master/routes/delete/' . $r['id']) ?>" 
                                   class="btn btn-sm btn-outline-danger rounded-3" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus rute <?= esc($r['origin_name']) ?> -> <?= esc($r['destination_name']) ?>?')"
                                   title="Hapus Rute">
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

<!-- Modal Tambah Route -->
<div class="modal fade" id="addRouteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i> Tambah Rute Penyeberangan Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/master/routes/store') ?>" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kota / Dermaga Asal</label>
                        <select name="origin_location_id" class="form-select" required>
                            <?php foreach ($locations as $loc): ?>
                                <option value="<?= $loc['id'] ?>"><?= esc($loc['name']) ?> (<?= esc($loc['city']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kota / Dermaga Tujuan</label>
                        <select name="destination_location_id" class="form-select" required>
                            <?php foreach ($locations as $idx => $loc): ?>
                                <option value="<?= $loc['id'] ?>" <?= $idx == 1 ? 'selected' : '' ?>><?= esc($loc['name']) ?> (<?= esc($loc['city']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Jarak (Nautical Miles)</label>
                            <input type="number" step="0.1" name="distance_nautical_miles" class="form-control" value="15" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Estimasi Durasi (Menit)</label>
                            <input type="number" name="estimated_duration_minutes" class="form-control" value="45" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Harga Dasar Tiket (Rp)</label>
                        <input type="number" name="base_price" class="form-control" value="150000" required>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold rounded-3 px-4"><i class="bi bi-save me-1"></i> Simpan Rute</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Route -->
<div class="modal fade" id="editRouteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i> Edit Data Rute Penyeberangan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editRouteForm" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kota / Dermaga Asal</label>
                        <select id="editOrigin" name="origin_location_id" class="form-select" required>
                            <?php foreach ($locations as $loc): ?>
                                <option value="<?= $loc['id'] ?>"><?= esc($loc['name']) ?> (<?= esc($loc['city']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kota / Dermaga Tujuan</label>
                        <select id="editDestination" name="destination_location_id" class="form-select" required>
                            <?php foreach ($locations as $loc): ?>
                                <option value="<?= $loc['id'] ?>"><?= esc($loc['name']) ?> (<?= esc($loc['city']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Jarak (Nautical Miles)</label>
                            <input type="number" step="0.1" id="editDistance" name="distance_nautical_miles" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Estimasi Durasi (Menit)</label>
                            <input type="number" id="editDuration" name="estimated_duration_minutes" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Harga Dasar Tiket (Rp)</label>
                        <input type="number" id="editPrice" name="base_price" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Status Rute</label>
                        <select id="editStatus" name="status" class="form-select">
                            <option value="active">ACTIVE</option>
                            <option value="inactive">INACTIVE</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark fw-bold rounded-3 px-4"><i class="bi bi-save me-1"></i> Perbarui Rute</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const editModal = new bootstrap.Modal(document.getElementById('editRouteModal'));
    const editForm = document.getElementById('editRouteForm');

    document.querySelectorAll('.btn-edit-route').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            editForm.action = '<?= base_url("admin/master/routes/update/") ?>' + id;
            document.getElementById('editOrigin').value = this.dataset.origin;
            document.getElementById('editDestination').value = this.dataset.destination;
            document.getElementById('editDistance').value = this.dataset.distance;
            document.getElementById('editDuration').value = this.dataset.duration;
            document.getElementById('editPrice').value = this.dataset.price;
            document.getElementById('editStatus').value = this.dataset.status;
            editModal.show();
        });
    });
});
</script>
<?= $this->endSection() ?>
