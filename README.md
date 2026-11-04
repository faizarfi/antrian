# Sistem Antrean Digital Pelayanan Statistik Terpadu (PST)
### Badan Pusat Statistik Kabupaten Karanganyar

Dokumentasi Teknis dan Panduan Implementasi Sistem Antrean Terintegrasi BPS Kabupaten Karanganyar. Sistem ini dirancang untuk operasionalisasi Pelayanan Statistik Terpadu (PST) dengan arsitektur 1 Loket Terpadu melayani 3 Kategori Pelayanan, integrasi tiket mandiri berbasis QR Code (Paperless), layar panggil televisi beresolusi tinggi, pemanggilan suara terotomatisasi Bahasa Indonesia, serta modul pelaporan berbasis akuntabilitas kinerja.

---

## Daftar Isi
1. [Ringkasan Sistem](#ringkasan-sistem)
2. [Arsitektur Pelayanan](#arsitektur-pelayanan)
3. [Prasyarat Perangkat Keras dan Lunak](#prasyarat-perangkat-keras-dan-lunak)
4. [Panduan Instalasi Langkah Demi Langkah](#panduan-instalasi-langkah-demi-langkah)
5. [Konfigurasi Basis Data dan Seeder](#konfigurasi-basis-data-dan-seeder)
6. [Menjalankan Server Aplikasi](#menjalankan-server-aplikasi)
7. [Konfigurasi Akses Jaringan Lokal (Multi-Device)](#konfigurasi-akses-jaringan-lokal-multi-device)
8. [Kredensial Pengguna Bawaan](#kredensial-pengguna-bawaan)
9. [Matriks Modul dan Rute URL](#matriks-modul-dan-rute-url)
10. [Pintasan Papan Ketik Petugas](#pintasan-papan-ketik-petugas)
11. [Penyelesaian Masalah Teknis](#penyelesaian-masalah-teknis)
12. [Kontak dan Hak Cipta](#kontak-dan-hak-cipta)

---

## Ringkasan Sistem

Sistem Antrean Digital PST dibangun menggunakan kerangka kerja Laravel dengan antarmuka pengguna berbasis Tailwind CSS. Aplikasi ini beroperasi secara mandiri di jaringan lokal kantor (LAN/Wi-Fi) tanpa ketergantungan pada koneksi internet pihak ketiga untuk operasionalisasi dasarnya.

Fitur utama meliputi:
* **Pengambilan Tiket Paperless:** Pemanfaatan kode QR dinamis yang dapat dipindai oleh kamera smartphone pengunjung tanpa memerlukan pengadaan monitor layar sentuh (*touchscreen*) yang mahal.
* **Layar Panggil TV Berkontras Tinggi:** Antarmuka TV 16:9 Full HD yang dirancang dengan rasio kontras tinggi standar aksesibilitas pemerintah untuk meminimalkan pantulan cahaya ruangan (*anti-glare*) dan memudahkan pembacaan oleh pengunjung lansia.
* **Sintesis Audio Multi-Tone:** Panggilan antrean otomatis menggabungkan nada lonceng (*Two-Tone Chime*) frekuensi 659.25 Hz dan 880 Hz dengan sintesis suara Bahasa Indonesia (*id-ID SpeechSynthesis*).
* **Survei Kepuasan Masyarakat (SKM):** Pengumpulan data indeks kepuasan pengunjung secara langsung pada perangkat smartphone pengunjung segera setelah status tiket diselesaikan oleh petugas.

---

## Arsitektur Pelayanan

Sistem ini menerapkan model antrean tersentralisasi **1 Loket Melayani 3 Layanan**:

| Kode | Nama Kategori Pelayanan | Deskripsi Operasional | Loket Penanganan |
| :---: | :--- | :--- | :---: |
| **A** | Pelayanan Perpustakaan | Pembacaan, penelusuran katalog, dan pengunduhan publikasi rilis resmi BPS | Loket 1 |
| **B** | Pelayanan Konsultasi Statistik | Konsultasi kebutuhan data mikro, metodologi, dan metadata statistik | Loket 1 |
| **C** | Rekomendasi Kegiatan Statistik | Pelayanan verifikasi instrumen kegiatan statistik sektoral (Romantik) | Loket 1 |

Masing-masing kategori memiliki penomoran urut harian mandiri (`A-001`, `B-001`, `C-001`). Seluruh panggilan diarahkan secara terpadu menuju **Loket 1**, dengan fleksibilitas pemanggilan mode FIFO (*First-In, First-Out*) atau seleksi per kategori layanan dari meja petugas.

---

## Prasyarat Perangkat Keras dan Lunak

Sebelum melakukan deployment pada komputer atau server baru, pastikan komponen berikut telah terkonfigurasi:

### 1. Perangkat Lunak Inti
* **PHP:** Versi 8.2 atau lebih tinggi
  * Ekstensi wajib: `pdo`, `pdo_mysql` (atau `pdo_sqlite`), `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`.
* **Composer:** Versi 2.5 atau lebih baru
* **Node.js & NPM:** Versi 18.x LTS atau lebih baru
* **Database Management System:** MySQL versi 8.0+ / MariaDB versi 10.4+ (mendukung SQLite 3.35+ untuk keperluan evaluasi instan)
* **Web Browser:** Google Chrome, Microsoft Edge, atau Mozilla Firefox versi modern

### 2. Kebutuhan Jaringan
* Jaringan Local Area Network (LAN) atau Wi-Fi internal kantor yang mengizinkan lalu lintas data antar-perangkat pada port aplikasi (default: TCP port 8000).

---

## Panduan Instalasi Langkah Demi Langkah

Lakukan prosedur berikut untuk menyiapkan repositori pada perangkat komputer baru:

### Langkah 1: Kloning atau Salin Direktori Proyek
Salin direktori proyek ke perangkat tujuan:
```bash
git clone https://github.com/faizarfi/antrian.git
cd antrian
```
Atau ekstrak arsip proyek ke lokasi kerja lokal Anda (contoh: `D:\antrian_bps`).

### Langkah 2: Pemasangan Dependensi Backend (PHP)
Jalankan Composer untuk mengunduh pustaka kerja aplikasi:
```bash
composer install --no-interaction --prefer-dist --optimize-autoloader
```

### Langkah 3: Konfigurasi Berkas Lingkungan (.env)
Buat salinan berkas `.env` dari contoh yang tersedia:
```bash
# Windows (PowerShell)
Copy-Item .env.example .env

# Linux / macOS / Git Bash
cp .env.example .env
```

Buka berkas `.env` menggunakan teks editor dan sesuaikan parameter berikut:
```ini
APP_NAME="Sistem Antrean PST BPS"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Pengaturan Basis Data MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=antrian
DB_USERNAME=root
DB_PASSWORD=
```

> [!NOTE]
> Pastikan skema basis data `antrian` telah dibuat pada server MySQL Anda sebelum melanjutkan ke tahap migrasi (`CREATE DATABASE antrian;`).

### Langkah 4: Pembuatan Kunci Aplikasi
Jalankan pembuatan application encryption key:
```bash
php artisan key:generate
```

### Langkah 5: Pemasangan Dependensi Frontend & Kompilasi Aset
Pasang paket pustaka Node.js dan lakukan kompilasi aset CSS dan JavaScript:
```bash
# Pemasangan modul
npm install

# Kompilasi aset produksi
npm run build
```

---

## Konfigurasi Basis Data dan Seeder

Jalankan perintah migrasi dan pembenihan data awal untuk menyiapkan skema tabel, akun operator, konfigurasi layanan, dan teks pengumuman bawaan:

```bash
php artisan migrate:fresh --seed
```

Perintah ini akan mengeksekusi kelas seeder berikut secara terstruktur:
1. `UserSeeder`: Menyiapkan akun Administrator dan Petugas Loket.
2. `ServiceSeeder`: Mendaftarkan 3 layanan resmi (Perpustakaan, Konsultasi Statistik, Romantik).
3. `CounterSeeder`: Mengonfigurasi Loket 1 sebagai sentra pelayanan terpadu.
4. `AnnouncementSeeder`: Mengisi running text pengumuman pada footer TV display.
5. `SettingSeeder`: Menginisialisasi parameter operasional audio, nama instansi, dan durasi pengalihan tiket.

---

## Menjalankan Server Aplikasi

### Metode 1: Menggunakan Perintah Otomatis (Direkomendasikan)
Gunakan skrip Composer yang telah dikonfigurasi untuk menjalankan server dengan bind ke seluruh antarmuka jaringan:
```bash
composer run dev
```

### Metode 2: Menggunakan Artisan Server Secara Langsung
Jika aset statis telah dikompilasi menggunakan `npm run build`, jalankan server PHP saja dengan konfigurasi bind host:
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

> [!IMPORTANT]
> Parameter `--host=0.0.0.0` wajib digunakan agar aplikasi dapat diakses oleh perangkat lain (smartphone pengunjung, laptop petugas, atau Android Smart TV) melalui jaringan lokal kantor.

---

## Konfigurasi Akses Jaringan Lokal (Multi-Device)

Untuk menghubungkan seluruh perangkat di lingkungan kantor ke server antrean, ikuti petunjuk berikut:

### 1. Identifikasi Alamat IP Komputer Server
Jalankan perintah berikut pada komputer yang bertindak sebagai host server:
* **Sistem Operasi Windows:**
  ```powershell
  ipconfig
  ```
  Catat nilai pada baris **IPv4 Address** (contoh: `192.168.1.16`).
* **Sistem Operasi Linux / macOS:**
  ```bash
  ip route get 1.2.3.4 | awk '{print $7}'
  ```

### 2. Membuka Port pada Windows Firewall
Jika perangkat lain mengalami kendala koneksi (*connection timed out*), buka akses port 8000 pada Windows Defender Firewall server:
```powershell
# Jalankan PowerShell sebagai Administrator
New-NetFirewallRule -DisplayName "Laravel Antrean BPS" -Direction Inbound -LocalPort 8000 -Protocol TCP -Action Allow
```

### 3. Distribusi Alamat URL Antar-Perangkat

| Peruntukan | Perangkat Sasaran | Alamat URL yang Diakses |
| :--- | :--- | :--- |
| **Pengunjung (Paperless)** | Smartphone Pribadi | Cukup pindai QR pada Standee Meja, atau buka `http://<IP_SERVER>:8000/online` |
| **Layar Ruang Tunggu** | Smart TV / PC Mini TV | `http://<IP_SERVER>:8000/display` *(Aktifkan mode F11)* |
| **Meja Petugas Loket** | Komputer / Laptop Petugas | `http://<IP_SERVER>:8000/operator` |
| **Meja Administrator** | PC Administrator | `http://<IP_SERVER>:8000/admin` |
| **Cetak Standee Meja** | PC Pengelola & Printer | `http://<IP_SERVER>:8000/cetak-qr` |

---

## Kredensial Pengguna Bawaan

Sistem dilengkapi akun pengguna siap pakai berikut:

| Peran (Role) | Username | Email | Kata Sandi | Deskripsi Wewenang |
| :--- | :--- | :--- | :--- | :--- |
| **Administrator** | `admin` | `admin@bps.go.id` | `password` | Akses penuh master data, manajemen petugas, kalibrasi audio, ekspor data, laporan kinerja |
| **Petugas Loket** | `petugas1` | `petugas1@bps.go.id` | `password` | Pemanggilan antrean, panggil ulang, lewati, dan penyelesaian pelayanan Loket 1 |
| **Petugas Cadangan** | `petugas2` | `petugas2@bps.go.id` | `password` | Akun pendukung operasional loket |

> [!TIP]
> Segera ubah kata sandi bawaan melalui menu **Administrator ➔ Petugas & Pengguna** sebelum implementasi resmi pada lingkungan produksi.

---

## Matriks Modul dan Rute URL

| Rute URL | Modul / Nama Halaman | Fungsi Utama |
| :--- | :--- | :--- |
| `/` atau `/kiosk` | Kiosk Pengambilan Tiket | Antarmuka registrasi tiket mandiri lobi kantor |
| `/cetak-qr` atau `/standee` | Standee Akrilik Meja | Dokumen format cetak A4/A5 berisi QR Code untuk meja resepsionis |
| `/online` | Kiosk Registrasi Mobile | Formulir registrasi tiket paperless melalui browser smartphone |
| `/tiket/{token}` | e-Tiket Dinamis Pengunjung | Halaman pemantauan giliran antrean dan form evaluasi SKM |
| `/display` | TV Display Informasi Antrean | Tampilan visual 16:9 pemanggilan antrean dan running text |
| `/operator` | Dashboard Petugas Loket | Panel kendali pemanggilan antrean bagi petugas front office |
| `/admin` | Dashboard Administrator | Ringkasan statistik operasional pelayanan harian |
| `/admin/reports` | Laporan dan Rekapitulasi | Filter data riwayat tiket, ekspor CSV/Excel, dan cetak PDF resmi BPS |
| `/admin/guide` atau `/panduan` | Buku Panduan Sistem | Modul dokumentasi operasional terpadu (SOP digital) |

---

## Pintasan Papan Ketik Petugas

Untuk memaksimalkan efisiensi kerja front office, seluruh alur pemanggilan pada Dashboard Petugas (`/operator`) dapat dioperasikan via tombol papan ketik (*keyboard shortcuts*):

| Tombol Pintasan | Aksi Sistem | Keterangan |
| :--- | :--- | :--- |
| <kbd>Space</kbd> atau <kbd>F2</kbd> | Panggil Berikutnya | Mengambil tiket nomor berikutnya dari antrean menunggu dan memicu suara panggilan |
| <kbd>R</kbd> | Panggil Ulang | Membunyikan kembali notifikasi audio jika pengunjung belum hadir |
| <kbd>S</kbd> | Lewati (*Skip*) | Menandai tiket dilewati apabila pengunjung tidak hadir setelah 3 kali pemanggilan |
| <kbd>Enter</kbd> atau <kbd>C</kbd> | Selesaikan (*Complete*) | Menuntaskan sesi layanan dan mengirimkan form survei SKM ke smartphone pengunjung |
| <kbd>F11</kbd> | Layar Penuh (*Fullscreen*) | Mengubah tampilan browser TV display menjadi tampilan penuh tanpa taskbar |

---

## Penyelesaian Masalah Teknis

### 1. Suara Pemanggilan TV Display Tidak Berbunyi
* **Penyebab:** Kebijakan *Autoplay Policy* pada peramban web modern melarang pemutaran audio otomatis tanpa adanya interaksi fisik pengguna terlebih dahulu.
* **Solusi:** Klik tombol **"Aktifkan Suara"** pada header TV Display sekali saat layar pertama kali dibuka di pagi hari.

### 2. Cetak Dokumen PDF Menghasilkan Lembar Kosong (Blank)
* **Penyebab:** Konflik aturan stylesheet media cetak.
* **Solusi:** Seluruh stylesheet `@media print` telah diisolasi. Pastikan Anda mengklik tombol resmi **"Cetak Laporan / PDF"** pada modul Laporan atau **"Cetak Buku Panduan"** pada modul Panduan untuk menghasilkan dokumen PDF berformat kop dinas resmi.

### 3. Smartphone Pengunjung Gagal Membuka Alamat dari QR Code
* **Penyebab:** Perangkat smartphone tidak berada dalam subnet jaringan yang sama dengan server, atau server dijalankan tanpa mengikat antarmuka jaringan luar.
* **Solusi:**
  1. Pastikan smartphone terhubung ke SSID Wi-Fi kantor yang sama dengan server.
  2. Pastikan server dijalankan dengan parameter `--host=0.0.0.0`.
  3. Konfirmasikan bahwa QR Code mengarah ke alamat IP LAN (misal: `192.168.1.16:8000/online`) dan bukan `localhost`.

### 4. Siklus Reset Antrean Harian
Nomor antrean terikat secara unik pada tanggal kalender (`service_date`). Sistem secara otomatis memulai kembali urutan nomor antrean dari `A-001`, `B-001`, dan `C-001` setiap pergantian hari pukul 00:00 WIB tanpa menghapus riwayat transaksi hari sebelumnya.

---

## Kontak dan Hak Cipta

**Badan Pusat Statistik Kabupaten Karanganyar**  
Jalan Lawu Nomor 385, Badran Asri, Karanganyar, Jawa Tengah 57712  
Telepon: (0271) 495023  
Surel: bps3313@bps.go.id  
Laman Resmi: [karanganyarkab.bps.go.id](https://karanganyarkab.bps.go.id)
