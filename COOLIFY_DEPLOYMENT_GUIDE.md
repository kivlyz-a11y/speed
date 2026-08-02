# 🚀 Panduan Deployment Ke Coolify (SpeedExpress CodeIgniter 4)

Dokumen ini berisi petunjuk langkah demi langkah untuk mendeploy aplikasi **SpeedExpress Pemesanan Tiket Speed Boat** ke server VPS menggunakan **Coolify**.

---

## 🛠️ berkas Docker yang Disediakan:

1. **`Dockerfile`**: Image berbasis `php:8.3-apache` dengan ekstensi lengkap (`mysqli`, `pdo_mysql`, `gd`, `intl`, `mbstring`, `zip`, `opcache`).
2. **`.docker/apache-ci4.conf`**: Konfigurasi Apache VirtualHost dengan `DocumentRoot` mengarah ke `/var/www/html/public`.
3. **`.docker/entrypoint.sh`**: Script otomatis untuk mengatur hak akses `writable/` dan migrasi database.
4. **`docker-compose.yml`**: Berkas komposisi multi-service untuk mendeploy **Aplikasi Web + Database MySQL 8.0** secara bersamaan di Coolify.
5. **`.dockerignore`**: Mengabaikan berkas `vendor`, `.env`, dan `writable/cache` lokal saat build image.

---

## 📋 Langkah-Langkah Deployment di Coolify:

### Opsi A: Deploy via GitHub / GitLab Repository (Rekomendasi)

1. **Push Source Code ke Repository Git**:
   ```bash
   git add .
   git commit -m "Add Dockerfile & Coolify configuration"
   git push origin main
   ```

2. **Buka Dashboard Coolify**:
   - Masuk ke dashboard Coolify Anda (`https://coolify.your-domain.com`).
   - Pilih **Project** & **Environment** (misal: `Production`).

3. **Tambah Resource Baru**:
   - Klik **+ New Resource** &rarr; Pilih **Public / Private Repository** (GitHub / GitLab).
   - Hubungkan repository `speed` Anda.

4. **Pilih Build Pack**:
   - Pilih **Dockerfile** atau **Docker Compose**.
   - Jika memilih **Docker Compose**, masukkan path: `./docker-compose.yml`.

5. **Atur Environment Variables (ENV) di Coolify**:
   Tambahkan variabel lingkungan di tab **Environment Variables**:
   ```env
   CI_ENVIRONMENT=production
   app.baseURL=https://tiket.domain-anda.com/
   database.default.hostname=db
   database.default.database=speed_boat_db
   database.default.username=speed_user
   database.default.password=UbahPasswordRahasia123!
   database.default.DBDriver=MySQLi
   database.default.port=3306
   MIDTRANS_IS_PRODUCTION=false
   MIDTRANS_SERVER_KEY=SB-Mid-server-xxxx
   MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxx
   DB_AUTO_MIGRATE=true
   ```

6. **Deploy**:
   - Klik tombol **Deploy**.
   - Coolify akan melakukan `docker build`, mengatur ssl domain otomatis via Traefik, dan mendeploy aplikasi Anda!

---

## ⚙️ Port & Healthcheck

- **Port Internal Container**: `80` (Apache)
- **Document Root**: `/var/www/html/public`
- **Healthcheck Path**: `/` atau `/login`
