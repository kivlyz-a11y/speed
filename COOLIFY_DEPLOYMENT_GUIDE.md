# 🚀 Panduan Environment Variables Coolify (Fix Command Not Found)

## ⚠️ Penyebab Error:
Coolify menggunakan script Bash saat memuat environment variables. Variabel yang menggunakan **tanda titik (`.`)** seperti `database.default.hostname` akan dianggap sebagai perintah Bash sehingga menyebabkan error:
`build-time.env: line 7: database.default.hostname: command not found`.

---

## ✅ Solusi (Gunakan Format Di Bawah Ini di Coolify Dashboard):

Di dashboard Coolify pada menu **Environment Variables**, ganti variabel yang menggunakan titik menjadi format standar **`DB_*`** atau **`database_default_*`** (menggunakan garis bawah `_`):

### 📋 Salin & Tempel ke Coolify Environment Variables:

```env
CI_ENVIRONMENT=development
APP_BASEURL=https://speed.sintesacorp.id/

DB_HOST=tpe3zvjvg81c6n9h5yx1jf91
DB_NAME=speed_12334
DB_USER=speed-2313
DB_PASS=Hu2AkEzo
DB_PORT=3306
DB_DRIVER=MySQLi

DB_AUTO_MIGRATE=true
```

*(Atau jika ingin menggunakan awalan `database_default` dengan garis bawah):*

```env
CI_ENVIRONMENT=development
app_baseURL=https://speed.sintesacorp.id/

database_default_hostname=tpe3zvjvg81c6n9h5yx1jf91
database_default_database=speed_12334
database_default_username=speed-2313
database_default_password=Hu2AkEzo
database_default_port=3306
database_default_DBDriver=MySQLi

DB_AUTO_MIGRATE=true
```

---

## ⚠️ Trouble Shooting Database: `Unknown database 'speed_12334'`

Jika terjadi error `Main connection [MySQLi]: Unknown database 'speed_12334'` saat deployment:
1. `.docker/entrypoint.sh` telah diperbarui untuk **otomatis membuat database** (via `CREATE DATABASE IF NOT EXISTS`) jika database belum ada pada server MySQL saat container dinyalakan.
2. Pastikan `DB_USER` yang Anda gunakan di Coolify memiliki hak akses untuk membuat database (atau jika menggunakan database yang sudah dibuat sebelumnya, pastikan nama `DB_NAME` di Coolify sesuai persis dengan nama database yang ada di server MySQL Anda).

Aplikasi CodeIgniter 4 (`App.php` & `Database.php`) dan `.docker/entrypoint.sh` sudah diperbarui untuk otomatis mendeteksi format `DB_*` dan `database_default_*` serta memastikan database telah dibuat secara otomatis sebelum menjalankan `spark migrate`.
