<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-1"><i class="bi bi-ticket-perforated-fill text-primary me-2"></i> Master Promo Voucher</h5>
            <p class="text-secondary small mb-0">Kelola kode promo voucher, potongan diskon, dan batas kuota</p>
        </div>
        <button class="btn btn-primary rounded-3 fw-bold px-4 py-2" data-bs-toggle="modal" data-bs-target="#addVoucherModal">
            <i class="bi bi-plus-circle-fill me-2"></i> Tambah Promo Baru
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-dark">
                <tr>
                    <th>Kode Voucher</th>
                    <th>Judul Promo</th>
                    <th>Tipe Diskon</th>
                    <th>Nilai Diskon</th>
                    <th>Min. Transaksi</th>
                    <th>Kuota Digunakan</th>
                    <th>Masa Berlaku</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vouchers as $v): ?>
                    <tr>
                        <td><span class="badge bg-warning text-dark fw-bold font-monospace fs-6"><?= esc($v['code']) ?></span></td>
                        <td><strong class="text-dark"><?= esc($v['title']) ?></strong></td>
                        <td><?= strtoupper($v['discount_type']) ?></td>
                        <td><span class="fw-bold text-success"><?= $v['discount_type'] === 'fixed' ? 'Rp ' . number_format($v['discount_value'], 0, ',', '.') : $v['discount_value'] . '%' ?></span></td>
                        <td>Rp <?= number_format($v['min_transaction'], 0, ',', '.') ?></td>
                        <td><?= $v['used_quota'] ?> / <?= $v['quota'] ?></td>
                        <td><?= date('d M Y', strtotime($v['start_date'])) ?> - <?= date('d M Y', strtotime($v['end_date'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah Voucher -->
<div class="modal fade" id="addVoucherModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle me-2"></i> Tambah Voucher Promo Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('admin/master/vouchers/store') ?>" method="POST">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Kode Promo (Unik)</label>
                        <input type="text" name="code" class="form-control text-uppercase fw-bold" placeholder="Contoh: PROMOBALI" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Judul Promo</label>
                        <input type="text" name="title" class="form-control" placeholder="Diskon Liburan Bali 15k" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Tipe Diskon</label>
                            <select name="discount_type" class="form-select">
                                <option value="fixed">Nominal Tetap (Rp)</option>
                                <option value="percentage">Persentase (%)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Nilai Diskon</label>
                            <input type="number" name="discount_value" class="form-control" value="15000" required>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Min. Transaksi (Rp)</label>
                            <input type="number" name="min_transaction" class="form-control" value="100000" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Total Kuota</label>
                            <input type="number" name="quota" class="form-control" value="100" required>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Tgl Mulai</label>
                            <input type="date" name="start_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Tgl Selesai</label>
                            <input type="date" name="end_date" class="form-control" value="<?= date('Y-m-d', strtotime('+30 days')) ?>" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="bi bi-save me-1"></i> Simpan Voucher</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
