# DOKUMENTASI SISTEM PEMESANAN TIKET SPEED BOAT (CODEIGNITER 4 ENTERPRISE)

Aplikasi **Pemesanan Tiket Speed Boat** berbasis **CodeIgniter 4** ini dirancang dengan kualitas **Enterprise**, menggunakan **Clean Architecture** (Repository & Service Layer Pattern), UI modern terinspirasi oleh **Cititrans**, integrasi **Midtrans Payment Gateway**, e-ticket QR Code, scanner boarding, serta dashboard operasional AdminLTE 4.

---

## 1. ERD & Skema Database Normalisasi (3NF)

Database `speed_boat_db` mencakup 27 entitas utama yang saling terelasi secara konsisten:

```mermaid
erDiagram
    COMPANIES ||--o{ SPEED_BOATS : owns
    SPEED_BOATS ||--o{ SEATS : contains
    COMPANIES ||--o{ CAPTAINS : employs
    COMPANIES ||--o{ CREWS : employs
    LOCATIONS ||--o{ ROUTES : "origin / destination"
    ROUTES ||--o{ SCHEDULES : defines
    SPEED_BOATS ||--o{ SCHEDULES : operates
    SCHEDULES ||--o{ TRIPS : instantiates
    TRIPS ||--o{ BOOKINGS : reserves
    BOOKINGS ||--o{ BOOKING_PASSENGERS : includes
    SEATS ||--o{ BOOKING_PASSENGERS : assigned
    BOOKINGS ||--o{ PAYMENTS : settles
    BOOKINGS ||--o{ TICKETS : generates
    TICKETS ||--o{ CHECK_IN_LOGS : scans
    TRIPS ||--o{ BOARDING_MANIFESTS : summarizes
    BOOKINGS ||--o{ REFUNDS : requests
    BOOKINGS ||--o{ RESCHEDULES : changes
```

---

## 2. Diagram Alur Sistem (Flowchart)

```mermaid
flowchart TD
    A[Public Landing Page / Search Box] --> B[Cari Kota Asal, Kota Tujuan & Tanggal]
    B --> C[AJAX List Jadwal Tersedia]
    C --> D[Visual Interactive Seat Map Selector]
    D --> E[Form Penumpang: Nama & No. WA Saja]
    E --> F[Temporary Seat Lock 10 Mnt]
    F --> G[Submit Booking Order]
    G --> H[Midtrans Payment Gateway / Snap QRIS / VA]
    H -->|Bayar Lunas| I[Webhook Callback / Auto Mark Paid]
    I --> J[Auto Generate E-Ticket & QR Code]
    J --> K[Notifikasi WhatsApp & Email PDF]
    K --> L[Scan QR Check-In di Dermaga]
    L --> M[Boarding & Update Manifest Penumpang]
```

---

## 3. Diagram Arsitektur & UML

### A. Use Case Diagram
- **Member / Penumpang**: Cari Jadwal, Pilih Kursi, Input Nama & Kontak, Bayar via Midtrans, Download E-Ticket PDF, Request Refund/Reschedule.
- **Petugas Dermaga**: Scan QR Code Check-In, Validasi Boarding, Lihat Manifest Keberangkatan.
- **Kasir / Admin Ops**: Booking Offline, Kelola Jadwal, Armada, Rute, & Tarif.
- **Supervisor / Manajer**: Approval Refund, Monitoring Manifest, Lihat Laporan Penjualan & Grafik.
- **Super Admin**: Akses penuh RBAC & Sistem.

### B. Sequence Diagram (Booking & Check-In)

```mermaid
sequenceDiagram
    autonumber
    actor Customer
    participant FrontUI as Cititrans UI
    participant Service as Booking/Payment Service
    participant Midtrans as Midtrans Gateway
    participant Officer as Petugas Dermaga

    Customer->>FrontUI: Pilih Rute & Tanggal
    FrontUI->>Service: GET /search (AJAX)
    Service-->>FrontUI: Returns Available Trips
    Customer->>FrontUI: Pilih Kursi pada Interactive Seat Map
    Customer->>FrontUI: Input Nama & No. WA
    FrontUI->>Service: POST /booking/store
    Service->>Midtrans: Create Snap Payment Token
    Midtrans-->>FrontUI: Popup QRIS / VA Payment
    Customer->>Midtrans: Selesaikan Pembayaran
    Midtrans->>Service: Webhook Callback (Settlement)
    Service->>Service: Generate E-Ticket QR Code
    Customer->>Officer: Tunjukkan QR Code E-Ticket
    Officer->>Service: Scan QR Code Check-In
    Service-->>Officer: Check-In Sukses & Update Manifest
```

---

## 4. Panduan Instalasi & Deployment

### Persyaratan Server / Runtime:
- PHP >= 8.3 dengan ekstensi `mysqli`, `gd`, `zip`, `mbstring`, `openssl`, `curl`
- MySQL / MariaDB >= 8.0
- Composer v2.x

### Langkah Instalasi:
1. Clone / Salin proyek ke folder webserver (misal `c:\xampp\htdocs\speed`).
2. Buat database MySQL `speed_boat_db`.
3. Jalankan migrasi dan seeder awal:
   ```bash
   php spark migrate
   php spark db:seed DatabaseSeeder
   ```
4. Sesuaikan konfigurasi `.env` (Midtrans Server & Client Key).
5. Jalankan server pengujian:
   ```bash
   php spark serve
   ```
6. Akses aplikasi:
   - **Frontend Publik**: `http://localhost:8080/`
   - **Dashboard Admin**: `http://localhost:8080/login`
   - **Kredensial Login Admin**: `admin@speed.test` / `password123`
