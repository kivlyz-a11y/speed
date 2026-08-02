<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-1"><i class="bi bi-geo-alt-fill text-primary me-2"></i> Master Kota Asal & Tujuan Penyeberangan</h5>
            <p class="text-secondary small mb-0">Kelola daftar lokasi kota asal, kota tujuan, dan dermaga penyeberangan speed boat</p>
        </div>
        <button class="btn btn-primary rounded-3 fw-bold px-4 py-2" data-bs-toggle="modal" data-bs-target="#addLocationModal">
            <i class="bi bi-plus-circle-fill me-2"></i> Tambah Lokasi / Dermaga
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-dark">
                <tr>
                    <th>Kode</th>
                    <th>Nama Dermaga / Pelabuhan</th>
                    <th>Kota / Wilayah</th>
                    <th>Provinsi</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($locations)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada data lokasi dermaga.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($locations as $loc): ?>
                        <tr>
                            <td><span class="badge bg-secondary font-monospace fs-6"><?= esc($loc['code']) ?></span></td>
                            <td><strong class="text-dark"><?= esc($loc['name']) ?></strong></td>
                            <td><?= esc($loc['city']) ?></td>
                            <td><?= esc($loc['province']) ?></td>
                            <td><small class="text-muted"><?= esc($loc['description'] ?? '-') ?></small></td>
                            <td>
                                <span class="badge bg-<?= $loc['status'] === 'active' ? 'success' : 'danger' ?>">
                                    <?= strtoupper($loc['status']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-primary me-1 rounded-3 btn-edit-loc" 
                                    data-id="<?= $loc['id'] ?>"
                                    data-code="<?= esc($loc['code']) ?>"
                                    data-name="<?= esc($loc['name']) ?>"
                                    data-city="<?= esc($loc['city']) ?>"
                                    data-province="<?= esc($loc['province']) ?>"
                                    data-description="<?= esc($loc['description'] ?? '') ?>"
                                    data-status="<?= $loc['status'] ?>"
                                    title="Edit Lokasi">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>

                                <a href="<?= base_url('admin/master/locations/delete/' . $loc['id']) ?>" 
                                   class="btn btn-sm btn-outline-danger rounded-3" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus lokasi <?= esc($loc['name']) ?>?')"
                                   title="Hapus Lokasi">
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

<!-- Modal Tambah Lokasi -->
<div class="modal fade" id="addLocationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i> Tambah Lokasi / Dermaga Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/master/locations/store') ?>" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kode Lokasi (Singkat & Unik)</label>
                        <input type="text" name="code" class="form-control text-uppercase fw-bold" placeholder="Contoh: SANUR / NPN / GILI" required maxlength="20">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Dermaga / Pelabuhan</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Pelabuhan Sanur" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Kota / Wilayah</label>
                            <input type="text" name="city" class="form-control" placeholder="Contoh: Sanur, Denpasar" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Provinsi</label>
                            <input type="text" name="province" class="form-control" placeholder="Contoh: Bali" value="Bali" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Keterangan / Alamat Singkat</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Dermaga Utama Keberangkatan Sanur"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold rounded-3 px-4"><i class="bi bi-save me-1"></i> Simpan Lokasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Lokasi -->
<div class="modal fade" id="editLocationModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i> Edit Data Lokasi / Dermaga</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editLocationForm" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kode Lokasi</label>
                        <input type="text" id="editCode" name="code" class="form-control text-uppercase fw-bold" required maxlength="20">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Dermaga / Pelabuhan</label>
                        <input type="text" id="editName" name="name" class="form-control" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Kota / Wilayah</label>
                            <input type="text" id="editCity" name="city" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Provinsi</label>
                            <input type="text" id="editProvince" name="province" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Status Lokasi</label>
                        <select id="editStatus" name="status" class="form-select">
                            <option value="active">ACTIVE</option>
                            <option value="inactive">INACTIVE</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Keterangan / Alamat Singkat</label>
                        <textarea id="editDescription" name="description" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark fw-bold rounded-3 px-4"><i class="bi bi-save me-1"></i> Perbarui Lokasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const editModal = new bootstrap.Modal(document.getElementById('editLocationModal'));
    const editForm = document.getElementById('editLocationForm');

    document.querySelectorAll('.btn-edit-loc').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            editForm.action = '<?= base_url("admin/master/locations/update/") ?>' + id;
            document.getElementById('editCode').value = this.dataset.code;
            document.getElementById('editName').value = this.dataset.name;
            document.getElementById('editCity').value = this.dataset.city;
            document.getElementById('editProvince').value = this.dataset.province;
            document.getElementById('editDescription').value = this.dataset.description;
            document.getElementById('editStatus').value = this.dataset.status;
            editModal.show();
        });
    });
});
</script>
<?= $this->endSection() ?>
