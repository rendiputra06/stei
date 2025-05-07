# Pengembangan Fitur Jadwal Akademik

## Deskripsi

Fitur ini digunakan untuk mengelola jadwal perkuliahan di universitas. Jadwal akan berelasi dengan mata kuliah, dosen, ruangan, dan tahun akademik.

## Tugas Utama

### 1. Pembuatan Database

-   [x] Analisis struktur relasi database
-   [x] Membuat migration untuk tabel `jadwal`
-   [x] Membuat model `Jadwal`
-   [x] Menambahkan relasi di model terkait (MataKuliah, Dosen, Ruangan, TahunAkademik)

### 2. Pembuatan Resource Filament

-   [x] Membuat `JadwalResource.php`
-   [x] Membuat form untuk menambah/edit jadwal
-   [x] Membuat tampilan list dengan filter dan pencarian
-   [x] Menambahkan validasi untuk mencegah jadwal bentrok (dosen, ruangan, waktu)
-   [ ] Implementasi fitur impor/ekspor jadwal (CSV, Excel)

### 3. Pembuatan Database Seeder

-   [x] Membuat `JadwalSeeder.php` untuk pengujian
-   [x] Membuat data dummy jadwal yang realistis

### 4. Pembuatan Testing

-   [x] Membuat test untuk validasi model
-   [x] Membuat Filament test untuk validasi resource
-   [x] Membuat test untuk fitur pencegahan bentrok jadwal
-   [ ] Membuat test untuk fitur impor/ekspor

### 5. Dokumentasi

-   [x] Membuat dokumentasi penggunaan fitur jadwal
-   [ ] Membuat dokumentasi API untuk pengembangan lebih lanjut

## Detail Tabel Jadwal

Tabel `jadwal` akan berisi kolom-kolom berikut:

-   `id` (primary key)
-   `tahun_akademik_id` (foreign key)
-   `mata_kuliah_id` (foreign key)
-   `dosen_id` (foreign key)
-   `ruangan_id` (foreign key)
-   `hari` (enum: 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')
-   `jam_mulai` (time)
-   `jam_selesai` (time)
-   `kelas` (string, misal: 'A', 'B', 'C')
-   `is_active` (boolean)
-   timestamps (`created_at`, `updated_at`)

## Aturan Validasi

-   Jadwal tidak boleh bentrok untuk dosen yang sama
-   Jadwal tidak boleh bentrok untuk ruangan yang sama
-   Jam selesai harus lebih besar dari jam mulai
-   Jadwal harus berada dalam rentang waktu tahun akademik yang aktif
