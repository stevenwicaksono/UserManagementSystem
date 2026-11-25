# User Management System

Aplikasi web sederhana untuk registrasi, login, dan pengelolaan data pengguna (User Management System). Pengguna yang berhasil login dapat melihat daftar akun serta menambah, mengubah, dan menghapus data pengguna melalui halaman dashboard.

Aplikasi ini dibuat menggunakan **PHP, Slim Framework 4, MySQL/MariaDB, Medoo, dan Twig**.

---

## Daftar Isi

1. [Tentang Aplikasi](#tentang-aplikasi)
2. [Fitur Aplikasi](#fitur-aplikasi)
3. [Teknologi yang Digunakan](#teknologi-yang-digunakan)
4. [Struktur Folder](#struktur-folder)
5. [Persyaratan Sistem](#persyaratan-sistem)
6. [Persiapan Sebelum Menjalankan](#persiapan-sebelum-menjalankan)
7. [Instalasi di Windows Menggunakan XAMPP](#instalasi-di-windows-menggunakan-xampp)
8. [Menjalankan Aplikasi](#menjalankan-aplikasi)
9. [Panduan Penggunaan Aplikasi](#panduan-penggunaan-aplikasi)
10. [Struktur Database](#struktur-database)
11. [Daftar URL / Route](#daftar-url--route)
12. [Troubleshooting](#troubleshooting)
13. [Catatan Keamanan](#catatan-keamanan)
14. [Pengembangan Selanjutnya](#pengembangan-selanjutnya)

---

## Tentang Aplikasi

**User Management System** adalah aplikasi berbasis web untuk mengelola data akun pengguna dalam satu dashboard. Aplikasi menyediakan halaman registrasi untuk membuat akun baru dan halaman login untuk masuk ke sistem. Setelah login, pengguna dapat mengelola data yang tersimpan pada tabel `users`.

Proyek ini menggunakan Slim Framework 4 sebagai framework backend. Halaman antarmuka dibuat menggunakan template Twig, sedangkan Medoo digunakan untuk berkomunikasi dengan database MySQL/MariaDB.

Aplikasi ini dapat digunakan sebagai proyek pembelajaran untuk memahami:
- Pengembangan aplikasi web menggunakan PHP.
- Pembuatan route menggunakan Slim Framework.
- Autentikasi sederhana menggunakan session.
- Penyimpanan password menggunakan `password_hash()`.
- Verifikasi password menggunakan `password_verify()`.
- Operasi CRUD pada database.
- Pemisahan controller, template, konfigurasi, dan database.

> **Status akses:** Arsip proyek berisi source code dan file SQL, tetapi tidak menyertakan alamat hosting/domain. Karena itu, tidak dapat dipastikan bahwa aplikasi masih tersedia secara online. Aplikasi dapat dicoba secara lokal setelah kebutuhan sistem, database, dan konfigurasi dipersiapkan.

## Fitur Aplikasi

### 1. Registrasi akun
Pengguna dapat membuat akun baru melalui halaman Register dengan mengisi:
- Username/email.
- Password.

Password diproses menggunakan `password_hash()` sebelum disimpan ke database.

### 2. Login
Pengguna masuk melalui halaman Login dengan memasukkan username/email dan password yang sudah didaftarkan. Sistem mengambil akun berdasarkan username, kemudian memeriksa password menggunakan `password_verify()`.

Jika data cocok, ID pengguna disimpan ke session dan pengguna diarahkan ke dashboard. Jika tidak cocok, pengguna diarahkan kembali ke halaman login dengan parameter error.

### 3. Dashboard
Dashboard menampilkan daftar akun yang tersimpan pada tabel `users`, dengan informasi:
- ID pengguna.
- Username/email.
- Aksi Edit.
- Aksi Hapus.

Dashboard hanya dapat dibuka jika session login tersedia.

### 4. Tambah pengguna
Pengguna yang sudah login dapat membuka formulir tambah pengguna, mengisi username/email dan password, lalu menyimpannya. Data baru akan ditambahkan ke tabel `users`.

### 5. Edit pengguna
Pengguna dapat mengubah username/email dari akun yang dipilih. Form edit juga menyediakan kolom password. Jika password dibiarkan kosong, password lama tidak diubah. Jika diisi, password baru akan di-hash sebelum disimpan.

### 6. Hapus pengguna
Pengguna dapat menghapus akun melalui tautan Hapus pada dashboard. Browser menampilkan konfirmasi sebelum permintaan penghapusan dikirim.

### 7. Logout
Pengguna dapat mengakhiri sesi dengan memilih Logout. Aplikasi menjalankan `session_destroy()` dan mengarahkan kembali ke halaman Login.

> **Catatan hak akses:** Pada source code saat ini, semua pengguna yang berhasil login dapat melihat dan mengelola seluruh data pada tabel `users`. Belum terdapat pembagian role seperti Admin dan User atau pembatasan agar pengguna hanya dapat mengubah akunnya sendiri.

## Teknologi yang Digunakan

| Teknologi | Kegunaan |
|---|---|
| PHP `^7.4` atau `^8.0` | Bahasa pemrograman backend sesuai batas versi pada `composer.json` |
| Slim Framework `^4.14` | Routing dan fondasi aplikasi web |
| MySQL / MariaDB | Penyimpanan data pengguna |
| Medoo `^2.2` | Membantu operasi database |
| Twig `^3.4` | Template halaman web |
| PHP-DI | Dependency injection |
| Monolog | Library pencatatan log |
| Composer | Instalasi dan pengelolaan library PHP |
| HTML dan CSS | Struktur dan tampilan halaman |

---

## Struktur Folder

Struktur utama aplikasi di dalam arsip:

```text
belajar-steven/
├── app/
│   ├── dependencies.php
│   ├── middleware.php
│   ├── repositories.php
│   ├── routes.php
│   └── settings.php
├── logs/
│   └── app.log
├── public/
│   ├── .htaccess
│   └── index.php
├── src/
│   ├── Application/
│   │   ├── Actions/
│   │   ├── Handlers/
│   │   ├── Middleware/
│   │   ├── ResponseEmitter/
│   │   └── Settings/
│   ├── Controller/
│   │   ├── AuthController.php
│   │   └── UserController.php
│   ├── Domain/
│   └── Infrastructure/
├── templates/
│   ├── dashboard.twig
│   ├── form_user.twig
│   ├── login.twig
│   ├── register.twig
│   └── errors/
├── tests/
├── uploads/
├── var/
├── vendor/
├── composer.json
├── composer.lock
└── docker-compose.yml

db_steven (1).sql
```

Keterangan folder/file penting:

| Folder/File | Fungsi |
|---|---|
| `app/settings.php` | Konfigurasi database dan pengaturan aplikasi |
| `app/routes.php` | Daftar URL dan controller yang menangani permintaan |
| `app/dependencies.php` | Pendaftaran dependency pada container |
| `src/Controller/AuthController.php` | Proses register, login, dan logout |
| `src/Controller/UserController.php` | Proses dashboard dan CRUD pengguna |
| `templates/` | Tampilan halaman berbasis Twig |
| `public/index.php` | Entry point aplikasi |
| `logs/` | Lokasi file log aplikasi |
| `tests/` | File pengujian |
| `vendor/` | Library yang dipasang oleh Composer |
| `composer.json` | Daftar dependensi dan perintah Composer |
| `composer.lock` | Versi dependensi yang terkunci |
| `db_steven (1).sql` | Dump database untuk membuat tabel `users` |

## Persyaratan Sistem

Sebelum instalasi, siapkan:

1. **PHP 7.4 atau PHP 8.x** yang kompatibel dengan dependensi proyek.
2. **Composer** untuk mengelola dependensi PHP.
3. **MySQL atau MariaDB** sebagai database.
4. **Web browser** seperti Chrome, Firefox, atau Edge.
5. **Terminal/Command Prompt** untuk menjalankan perintah.

Jika menggunakan XAMPP, pastikan modul Apache dan MySQL tersedia. Untuk cara menjalankan yang dijelaskan di bawah, PHP CLI dan Composer tetap harus dapat dijalankan dari terminal.

Ekstensi PHP yang perlu tersedia antara lain:
- `pdo`
- `pdo_mysql`
- `json`

Periksa versi PHP dan Composer melalui terminal:

```bash
php -v
composer --version
```

Periksa ekstensi PDO MySQL:

```bash
php -m
```

Pastikan daftar modul menampilkan `PDO` dan `pdo_mysql`.

---

## Persiapan Sebelum Menjalankan

### 1. Ekstrak file proyek

Ekstrak `TugasAkhir.zip`. Masuk ke folder aplikasi yang memiliki file `composer.json`.

Contoh struktur lokasi setelah diekstrak:

```text
TugasAkhir/
├── belajar-steven/
│   ├── app/
│   ├── public/
│   ├── src/
│   ├── templates/
│   └── composer.json
└── db_steven (1).sql
```

Nama folder hasil ekstraksi bisa berbeda. Yang penting, terminal dibuka pada folder yang berisi `composer.json`.

### 2. Siapkan database

Jalankan MySQL/MariaDB melalui XAMPP atau layanan database yang digunakan.

Buat database bernama:

```sql
CREATE DATABASE db_steven
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;
```

### 3. Impor file SQL

**Menggunakan phpMyAdmin:**

1. Jalankan Apache dan MySQL pada XAMPP.
2. Buka `http://localhost/phpmyadmin`.
3. Pilih database `db_steven`.
4. Buka tab **Import**.
5. Klik **Choose File**.
6. Pilih file `db_steven (1).sql` dari arsip.
7. Klik **Import** atau **Go**.
8. Pastikan tabel `users` berhasil dibuat.

**Menggunakan terminal:**

```bash
mysql -u root -p db_steven < "db_steven (1).sql"
```

Sesuaikan lokasi file SQL dan username database dengan komputer masing-masing. Jika MySQL tidak menggunakan password, ikuti konfigurasi lokal yang berlaku.

> File SQL yang disertakan juga berisi dua baris data akun contoh. Untuk penggunaan pribadi atau publikasi repository, tinjau dan hapus data contoh tersebut jika tidak diperlukan. Jangan menganggap akun contoh sebagai akun yang wajib digunakan.

### 4. Atur koneksi database

Buka file:

```text
app/settings.php
```

Cari konfigurasi berikut:

```php
$dbConfig = [
    'database_type' => 'mysql',
    'database_name' => 'db_steven',
    'server' => 'localhost',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
];
```

Sesuaikan dengan pengaturan MySQL/MariaDB di komputer Anda.

Contoh untuk instalasi XAMPP lokal yang menggunakan user `root` tanpa password:

```php
'username' => 'root',
'password' => '',
```

Jika database memiliki password, isi nilai `password` dengan password database tersebut. Jangan mengunggah kredensial pribadi ke repository publik.

### 5. Instal dependensi Composer

Buka terminal pada folder proyek, yaitu folder yang berisi `composer.json`, kemudian jalankan:

```bash
composer install
```

Composer akan memasang library yang dibutuhkan berdasarkan `composer.lock`.

Jika proses selesai tanpa error, folder `vendor/` akan tersedia dan aplikasi dapat melanjutkan proses startup.

---

## Instalasi di Windows Menggunakan XAMPP

Berikut contoh langkah dari awal untuk pengguna Windows.

1. Instal dan buka XAMPP.
2. Jalankan modul **MySQL**. Apache dapat dijalankan jika ingin menggunakan phpMyAdmin.
3. Ekstrak folder aplikasi ke lokasi yang mudah ditemukan, misalnya:

   ```text
   C:\xampp\htdocs\belajar-steven
   ```

4. Pastikan file `composer.json` berada di dalam folder tersebut.
5. Buka Command Prompt atau terminal VS Code.
6. Pindah ke folder proyek:

   ```bash
   cd C:\xampp\htdocs\belajar-steven
   ```

7. Instal dependensi:

   ```bash
   composer install
   ```

8. Buka phpMyAdmin:

   ```text
   http://localhost/phpmyadmin
   ```

9. Buat database `db_steven`, lalu impor file SQL.
10. Periksa konfigurasi database pada `app/settings.php`.
11. Jalankan aplikasi dengan:

   ```bash
   composer start
   ```

12. Buka browser ke:

   ```text
   http://localhost:8080
   ```

**Catatan:** Perintah `composer start` menggunakan PHP development server bawaan PHP dan menjalankan folder `public` sebagai document root. Jadi, aplikasi tidak dijalankan melalui URL folder XAMPP seperti `/belajar-steven`; akses melalui `http://localhost:8080` ketika server Composer sedang berjalan.

---

## Menjalankan Aplikasi

Pada direktori proyek, jalankan:

```bash
composer start
```

Perintah tersebut menjalankan script berikut yang didefinisikan pada `composer.json`:

```bash
php -S localhost:8080 -t public
```

Jika berhasil, terminal akan menunjukkan bahwa development server berjalan. Biarkan terminal tetap terbuka selama aplikasi digunakan.

Buka browser dan akses:

**http://localhost:8080**

Untuk menghentikan server, kembali ke terminal dan tekan:

```text
Ctrl + C
```

> PHP built-in server ditujukan untuk pengembangan lokal, bukan untuk deployment produksi atau menerima trafik publik.

---

## Panduan Penggunaan Aplikasi

### A. Membuka halaman aplikasi

1. Pastikan MySQL/MariaDB sedang berjalan.
2. Pastikan konfigurasi database sudah benar.
3. Jalankan `composer start`.
4. Buka `http://localhost:8080`.
5. Halaman utama akan mengarahkan ke halaman Login.

### B. Membuat akun baru (Register)

Jika belum memiliki akun, lakukan langkah berikut:

1. Pada halaman Login, klik tautan **Daftar di sini**.
2. Browser akan membuka halaman Register di `/register`.
3. Isi kolom **Email** dengan username/email yang ingin digunakan.
4. Isi kolom **Password**.
5. Klik tombol **Register**.
6. Jika proses penyimpanan berhasil, aplikasi akan mengarahkan kembali ke halaman Login.
7. Masukkan akun yang baru didaftarkan untuk login.

Contoh data untuk pengujian:

```text
Email    : pengguna@example.com
Password : password123
```

Contoh tersebut hanya data ilustrasi. Buat password sendiri saat mencoba aplikasi.

**Perlu diketahui:** Form registrasi pada source code saat ini memeriksa kolom kosong, tetapi belum menampilkan validasi lengkap atau pesan sukses khusus. Pastikan username/email belum digunakan karena validasi duplikasi belum diterapkan secara eksplisit.

### C. Login ke aplikasi

1. Buka halaman `/login`.
2. Masukkan username/email yang sudah didaftarkan.
3. Masukkan password.
4. Klik tombol **Login**.
5. Jika username dan password sesuai, aplikasi akan mengarahkan ke `/dashboard`.
6. Jika tidak sesuai, aplikasi akan kembali ke halaman Login dan menampilkan pesan bahwa username atau password salah.

### D. Melihat daftar pengguna

Setelah login berhasil:

1. Anda akan berada di halaman Dashboard.
2. Halaman menampilkan tabel **Data Users**.
3. Tabel berisi kolom:
   - **ID:** nomor identitas data pengguna di database.
   - **Email:** nilai username yang tersimpan.
   - **Aksi:** pilihan Edit dan Hapus.
4. Daftar tersebut berasal dari tabel `users` di database `db_steven`.

### E. Menambahkan pengguna

Untuk menambahkan akun melalui dashboard:

1. Klik tombol **+ Tambah User**.
2. Formulir tambah pengguna akan terbuka.
3. Isi kolom **Email**.
4. Isi kolom **Password**.
5. Klik tombol **Simpan**.
6. Jika proses berhasil, aplikasi kembali ke Dashboard.
7. Periksa tabel untuk memastikan data baru sudah muncul.

Password akan diproses menggunakan `password_hash()` sebelum disimpan ke database.

### F. Mengedit pengguna

Untuk mengubah data akun:

1. Cari akun yang ingin diubah pada tabel Dashboard.
2. Klik **Edit** pada baris akun tersebut.
3. Formulir edit akan menampilkan username/email yang tersimpan.
4. Ubah nilai Email jika diperlukan.
5. Jika ingin mengganti password, isi kolom Password dengan password baru.
6. Jika tidak ingin mengganti password, biarkan kolom Password kosong.
7. Klik **Simpan**.
8. Aplikasi akan kembali ke Dashboard.
9. Periksa data yang telah diperbarui.

**Penting:** Mengosongkan kolom password pada halaman edit berarti password lama tetap digunakan.

### G. Menghapus pengguna

Untuk menghapus akun:

1. Cari akun pada tabel Dashboard.
2. Klik **Hapus** pada baris akun yang dipilih.
3. Browser akan menampilkan konfirmasi **Hapus user?**
4. Pilih **OK** jika yakin ingin menghapus.
5. Pilih **Cancel** jika tidak jadi menghapus.
6. Jika penghapusan dikonfirmasi, aplikasi menghapus data dan kembali ke Dashboard.

**Perhatian:** Penghapusan menghilangkan baris pengguna dari database. Pastikan akun yang dipilih benar sebelum mengonfirmasi.

### H. Logout

Untuk keluar dari aplikasi:

1. Klik tombol **Logout** pada Dashboard.
2. Session login akan diakhiri.
3. Aplikasi mengarahkan kembali ke halaman Login.
4. Untuk masuk kembali, isi username/email dan password.

---

## Struktur Database

Nama database:

```text
db_steven
```

Nama tabel:

```text
users
```

Struktur tabel berdasarkan file SQL yang disertakan:

| Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | `INT(11)` | Primary key dan auto increment |
| `username` | `VARCHAR(100)` | Username/email pengguna |
| `password` | `VARCHAR(255)` | Password dalam bentuk hash |

Relasi tabel tidak didefinisikan pada dump SQL yang disertakan. Aplikasi menggunakan tabel `users` untuk proses registrasi, login, dan CRUD.

Contoh membuat tabel secara manual jika file SQL tidak tersedia:

```sql
CREATE TABLE users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_general_ci;
```

---

## Daftar URL / Route

Aplikasi berjalan pada alamat dasar `http://localhost:8080`.

| Method | URL | Keterangan |
|---|---|---|
| GET | `/` | Mengarahkan pengguna ke halaman Login |
| GET | `/login` | Menampilkan formulir Login |
| POST | `/login` | Memproses autentikasi |
| GET | `/register` | Menampilkan formulir Register |
| POST | `/register` | Menyimpan akun baru |
| GET | `/logout` | Mengakhiri session |
| GET | `/dashboard` | Menampilkan daftar pengguna |
| GET | `/user/create` | Menampilkan formulir tambah pengguna |
| POST | `/user/store` | Menyimpan pengguna baru |
| GET | `/user/edit/{id}` | Menampilkan formulir edit pengguna |
| POST | `/user/update/{id}` | Menyimpan perubahan pengguna |
| GET | `/user/delete/{id}` | Menghapus pengguna berdasarkan ID |

Route dashboard dan CRUD memeriksa session login melalui controller. Jika belum login, pengguna diarahkan ke halaman Login.

---

## Troubleshooting

### 1. `composer` tidak dikenali

**Penyebab:** Composer belum terinstal atau belum masuk ke PATH Windows.

**Solusi:**
- Instal Composer.
- Tutup dan buka kembali terminal.
- Jalankan `composer --version` untuk memeriksa instalasi.

### 2. `php` tidak dikenali

**Penyebab:** PHP belum masuk ke PATH.

**Solusi untuk XAMPP:**
- Pastikan PHP tersedia di folder `C:\xampp\php`.
- Tambahkan folder tersebut ke Environment Variables `Path`, atau jalankan PHP menggunakan path lengkap.
- Buka terminal baru dan jalankan `php -v`.

### 3. Database connection error

**Kemungkinan penyebab:**
- MySQL/MariaDB belum berjalan.
- Nama database tidak sesuai.
- Username atau password database salah.
- Ekstensi `pdo_mysql` belum aktif.

**Solusi:**
1. Pastikan MySQL aktif.
2. Pastikan database `db_steven` sudah dibuat.
3. Periksa `app/settings.php`.
4. Pastikan ekstensi `pdo_mysql` aktif pada PHP yang digunakan terminal.

### 4. Tabel `users` tidak ditemukan

**Penyebab:** Database belum diimpor atau nama tabel berbeda.

**Solusi:**
- Buka phpMyAdmin.
- Pilih database `db_steven`.
- Pastikan tabel `users` tersedia.
- Jika belum ada, impor ulang file SQL.

### 5. Halaman tidak dapat dibuka

**Solusi:**
- Pastikan `composer start` masih berjalan.
- Pastikan alamat yang dibuka adalah `http://localhost:8080`.
- Periksa apakah port 8080 sedang digunakan aplikasi lain.
- Periksa pesan error pada terminal.

### 6. Halaman menampilkan error setelah `composer install`

**Solusi:**
- Pastikan terminal berada di folder yang benar, yaitu folder yang berisi `composer.json`.
- Jalankan kembali `composer install`.
- Periksa versi PHP dengan `php -v`.
- Periksa pesan error lengkap untuk mengetahui dependensi atau ekstensi yang belum terpenuhi.

### 7. Login selalu gagal

**Solusi:**
- Pastikan username/email sama dengan yang tersimpan pada tabel `users`.
- Pastikan password sesuai dengan password ketika registrasi.
- Jika menggunakan data SQL bawaan, jangan menebak password dari hash. Buat akun baru melalui halaman Register.

---

## Catatan Keamanan

Proyek ini merupakan aplikasi sederhana untuk pembelajaran dan pengembangan. Sebelum digunakan pada lingkungan publik atau produksi, beberapa hal berikut perlu ditingkatkan:

1. Tambahkan validasi format username/email dan panjang password di sisi server.
2. Tambahkan pemeriksaan agar username/email tidak terdaftar ganda.
3. Terapkan perlindungan CSRF pada form yang mengubah data.
4. Ubah aksi hapus dari method GET menjadi POST atau DELETE.
5. Terapkan pengelolaan session yang konsisten, termasuk regenerasi session ID setelah login dan penghapusan cookie saat logout.
6. Batasi hak akses pengguna dengan role/permission yang jelas.
7. Pastikan pengguna tidak dapat mengubah atau menghapus akun yang tidak menjadi kewenangannya.
8. Nonaktifkan detail error/debug pada lingkungan produksi.
9. Simpan kredensial database di environment variable, bukan konfigurasi yang diunggah ke repository publik.
10. Gunakan HTTPS ketika aplikasi dipublikasikan.
11. Tinjau file SQL dan log sebelum dipublikasikan agar tidak membagikan data pribadi atau informasi sensitif.

---

## Pengembangan Selanjutnya

Fitur yang dapat ditambahkan untuk pengembangan aplikasi:
- Role pengguna, misalnya Admin dan User.
- Hak akses berdasarkan role.
- Profil dan pengaturan akun.
- Pencarian serta pagination data pengguna.
- Validasi form dan notifikasi yang lebih informatif.
- Fitur reset password.
- Audit log aktivitas.
- Proteksi CSRF dan penguatan autentikasi.
- Pengujian otomatis untuk alur registrasi, login, dan CRUD.
- Deployment ke hosting atau VPS.

## Menjalankan Pengujian

Proyek menyediakan script pengujian Composer:

```bash
composer test
```

Perintah ini menjalankan PHPUnit sesuai konfigurasi proyek. Hasil pengujian perlu diperiksa pada lingkungan lokal; keberadaan file test tidak otomatis berarti seluruh fitur aplikasi telah lulus pengujian.

---

## Penutup

User Management System merupakan proyek aplikasi web sederhana yang berfokus pada autentikasi dan pengelolaan data pengguna. Melalui proyek ini, konsep dasar pengembangan backend PHP, routing, session, template engine, koneksi database, dan operasi CRUD dapat dipelajari serta dikembangkan lebih lanjut.

