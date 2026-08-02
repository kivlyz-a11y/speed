<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-1"><i class="bi bi-boat-front-fill text-primary me-2"></i> Master Speed Boat & Denah Kursi</h5>
            <p class="text-secondary small mb-0">Kelola armada speed boat, kapasitas, serta konfigurasi denah kursi</p>
        </div>
        <button class="btn btn-primary rounded-3 fw-bold px-4 py-2" data-bs-toggle="modal" data-bs-target="#addBoatModal">
            <i class="bi bi-plus-circle-fill me-2"></i> Tambah Armada Baru
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-dark">
                <tr>
                    <th>Kode</th>
                    <th>Nama Speed Boat</th>
                    <th>Kapasitas Kursi</th>
                    <th>Denah Baris x Kolom</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($boats as $b): ?>
                    <tr>
                        <td><span class="badge bg-secondary font-monospace fs-6"><?= esc($b['code']) ?></span></td>
                        <td><strong class="text-dark"><?= esc($b['name']) ?></strong></td>
                        <td><span class="fw-bold text-primary"><?= esc($b['capacity']) ?> Kursi</span></td>
                        <td><?= esc($b['total_rows']) ?> Baris x <?= esc($b['total_cols']) ?> Kolom</td>
                        <td>
                            <span class="badge bg-<?= $b['status'] === 'active' ? 'success' : 'danger' ?>">
                                <?= strtoupper($b['status']) ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-primary me-1 rounded-3 btn-edit-boat"
                                data-id="<?= $b['id'] ?>"
                                data-name="<?= esc($b['name']) ?>"
                                data-status="<?= $b['status'] ?>">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Boat -->
<div class="modal fade" id="addBoatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i> Tambah Armada Speed Boat Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/master/boats/store') ?>" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kode Speedboat</label>
                        <input type="text" name="code" class="form-control text-uppercase fw-bold" placeholder="Contoh: OEXP-03" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Speed Boat</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Ocean Express 03" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <label class="form-label small fw-bold">Kapasitas</label>
                            <input type="number" name="capacity" class="form-control" value="32" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-bold">Total Baris</label>
                            <input type="number" name="total_rows" class="form-control" value="8" required>
                        </div>
                        <div class="col-4">
                            <label class="form-label small fw-bold">Total Kolom</label>
                            <input type="number" name="total_cols" class="form-control" value="4" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="bi bi-save me-1"></i> Simpan Armada</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Boat -->
<div class="modal fade" id="editBoatModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i> Edit Speed Boat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editBoatForm" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Speed Boat</label>
                        <input type="text" id="editBoatName" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Status Armada</label>
                        <select id="editBoatStatus" name="status" class="form-select">
                            <option value="active">ACTIVE</option>
                            <option value="maintenance">MAINTENANCE</option>
                            <option value="inactive">INACTIVE</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark fw-bold"><i class="bi bi-save me-1"></i> Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const editModal = new bootstrap.Modal(document.getElementById('editBoatModal'));
    const editForm = document.getElementById('editBoatForm');

    document.querySelectorAll('.btn-edit-boat').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            editForm.action = '<?= base_url("admin/master/boats/update/") ?>' + id;
            document.getElementById('editBoatName').value = this.dataset.name;
            document.getElementById('editBoatStatus').value = this.dataset.status;
            editModal.show();
        });
    });
});
</script>
<?= $this->endSection() ?>
