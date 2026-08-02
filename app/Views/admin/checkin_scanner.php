<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="row g-4">
    <!-- Left: QR Code Scanner & Manual Input -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-qr-code-scan text-primary me-2"></i> Petugas Scanner Check-In</h5>

            <!-- Manual Input Form -->
            <form id="manualScanForm" class="mb-4">
                <label class="form-label small fw-bold">Input Manual / Scan Barcode Reader</label>
                <div class="input-group input-group-lg">
                    <input type="text" id="manualTicketInput" class="form-control text-uppercase fw-bold" placeholder="Contoh: TIX-A1B2C3D4" autofocus required>
                    <button class="btn btn-primary fw-bold" type="submit"><i class="bi bi-search me-1"></i> Scan Check-In</button>
                </div>
            </form>

            <hr class="my-4">

            <!-- Webcam QR Code Reader -->
            <div class="text-center">
                <h6 class="fw-bold mb-2">Webcam / Camera QR Reader</h6>
                <div id="qr-reader" style="width: 100%; max-width: 450px; margin: 0 auto;" class="border rounded-4 overflow-hidden shadow-sm"></div>
                <div id="qr-reader-results" class="mt-2 text-muted small">Kamera aktif untuk memindai QR Code E-Ticket.</div>
            </div>
        </div>
    </div>

    <!-- Right: Real-time Scan Result Card & Today's Manifest Links -->
    <div class="col-lg-6">
        <!-- Scan Result Container -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" id="resultCard" style="display: none;">
            <div class="text-center" id="resultIcon"></div>
            <h4 class="fw-bold text-center mt-2 mb-3" id="resultHeader">Status Scan</h4>
            <div class="alert small rounded-3" id="resultAlert"></div>

            <div class="bg-light p-3 rounded-3" id="resultDetails"></div>
        </div>

        <!-- Today Trips Manifest List -->
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-file-earmark-person-fill text-info me-2"></i> Manifest Keberangkatan Hari Ini</h5>
            <div class="list-group">
                <?php foreach ($trips as $t): ?>
                    <a href="<?= base_url('admin/checkin/manifest/' . $t['id']) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 rounded-3 mb-2 border">
                        <div>
                            <span class="badge bg-primary me-2"><?= date('H:i', strtotime($t['departure_time'])) ?> WITA</span>
                            <strong class="text-dark"><?= esc($t['origin_city']) ?> &rarr; <?= esc($t['destination_city']) ?></strong>
                            <div class="text-muted small mt-1"><i class="bi bi-boat-front me-1"></i> <?= esc($t['boat_name']) ?></div>
                        </div>
                        <span class="btn btn-sm btn-outline-primary fw-bold rounded-pill">Lihat Manifest <i class="bi bi-chevron-right ms-1"></i></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- HTML5 QR Code Library -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Manual Input Form Submit
    document.getElementById('manualScanForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const code = document.getElementById('manualTicketInput').value.trim();
        if (code) {
            processCheckIn(code);
            document.getElementById('manualTicketInput').value = '';
        }
    });

    // Webcam QR Scanner
    function onScanSuccess(decodedText, decodedResult) {
        processCheckIn(decodedText);
    }

    let html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: { width: 250, height: 250 } }, false);
    html5QrcodeScanner.render(onScanSuccess);

    function processCheckIn(code) {
        fetch('<?= base_url("admin/checkin/scan") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ticket_code: code })
        })
        .then(res => res.json())
        .then(data => {
            showResultCard(data);
        })
        .catch(err => {
            console.error('Scan error:', err);
        });
    }

    function showResultCard(res) {
        const card = document.getElementById('resultCard');
        const icon = document.getElementById('resultIcon');
        const header = document.getElementById('resultHeader');
        const alertBox = document.getElementById('resultAlert');
        const details = document.getElementById('resultDetails');

        card.style.display = 'block';

        if (res.success) {
            icon.innerHTML = '<i class="bi bi-check-circle-fill text-success display-1"></i>';
            header.className = 'fw-bold text-center text-success mt-2 mb-3';
            header.innerText = 'BOARDING DISETUJUI!';
            alertBox.className = 'alert alert-success small rounded-3';
            alertBox.innerText = res.message;
        } else if (res.already_scanned) {
            icon.innerHTML = '<i class="bi bi-exclamation-triangle-fill text-warning display-1"></i>';
            header.className = 'fw-bold text-center text-warning mt-2 mb-3';
            header.innerText = 'TIKET SUDAH CHECK-IN!';
            alertBox.className = 'alert alert-warning small rounded-3';
            alertBox.innerText = res.message;
        } else {
            icon.innerHTML = '<i class="bi bi-x-circle-fill text-danger display-1"></i>';
            header.className = 'fw-bold text-center text-danger mt-2 mb-3';
            header.innerText = 'TIKET DITOLAK!';
            alertBox.className = 'alert alert-danger small rounded-3';
            alertBox.innerText = res.message;
        }

        if (res.ticket) {
            const t = res.ticket;
            details.innerHTML = `
                <div class="row g-2 small">
                    <div class="col-6"><strong>Penumpang:</strong> ${t.passenger_name}</div>
                    <div class="col-6"><strong>Nomor Kursi:</strong> <span class="badge bg-primary fs-6">SEAT ${t.seat_number}</span></div>
                    <div class="col-6"><strong>Kapal:</strong> ${t.boat_name}</div>
                    <div class="col-6"><strong>Rute:</strong> ${t.origin_name} &rarr; ${t.destination_name}</div>
                    <div class="col-6"><strong>Kode Booking:</strong> ${t.booking_code}</div>
                    <div class="col-6"><strong>Waktu Scan:</strong> ${t.checked_in_at || 'Baru Saja'}</div>
                </div>
            `;
        } else {
            details.innerHTML = '';
        }
    }
});
</script>
<?= $this->endSection() ?>
