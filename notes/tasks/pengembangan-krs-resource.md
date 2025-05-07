# Pengembangan KRS Resource di Filament

## Deskripsi

Proyek ini bertujuan untuk mengubah halaman pengisian KRS yang sebelumnya dibuat dengan custom page menjadi Filament Resource. Dengan menggunakan Filament Resource, kita dapat memperoleh fitur CRUD yang lebih lengkap dan standar untuk pengelolaan KRS mahasiswa.

## Tasks

### 1. Setup Resource KRS

-   [x] Membuat KRSResource.php di app/Filament/Mahasiswa/Resources
-   [x] Mendefinisikan form dan table untuk KRS
-   [x] Menambahkan filter dan pencarian yang relevan

### 2. Setup Resource KRSDetail

-   [x] Membuat KRSDetailResource.php di app/Filament/Mahasiswa/Resources
-   [x] Mendefinisikan form dan table untuk KRSDetail
-   [x] Menambahkan relasi ke KRS Resource

### 3. Menyiapkan Pages untuk KRS Resource

-   [x] Kustomisasi ListRecords untuk menampilkan hanya KRS milik mahasiswa yang login
-   [x] Kustomisasi CreateRecord untuk otomatis mengisi data mahasiswa
-   [x] Kustomisasi EditRecord untuk validasi status dan periode KRS
-   [x] Membuat page khusus untuk Submit KRS

### 4. Menambahkan Action Khusus

-   [x] Menambahkan action Submit KRS
-   [x] Menambahkan action Tambah Mata Kuliah
-   [x] Menambahkan validasi untuk bentrok jadwal
-   [x] Menambahkan validasi untuk batas SKS

### 5. Pengembangan Relasi dan Widget

-   [x] Membuat RelationManager untuk KRSDetail
-   [x] Menambahkan widget untuk informasi KRS
-   [x] Menambahkan widget untuk status periode KRS

### 6. Pengembangan Data Seeder

-   [x] Membuat KRSSeeder.php
-   [x] Membuat KRSDetailSeeder.php
-   [x] Menambahkan data dummy untuk pengujian

### 7. Implementasi Policy

-   [x] Mengimplementasikan KRSPolicy untuk KRS resource
-   [x] Mengimplementasikan KRSDetailPolicy

### 8. Pengujian

-   [x] Menguji alur pengisian KRS
-   [x] Menguji validasi bentrok jadwal
-   [x] Menguji fungsionalitas submit KRS
-   [x] Menguji otorisasi dan pembatasan akses

### 9. Dokumentasi

-   [x] Mendokumentasikan alur penggunaan KRS resource
-   [x] Menambahkan komentar pada kode yang kompleks
-   [x] Memperbarui README

## Kebutuhan Data Seeder

1. KRS dummy untuk beberapa mahasiswa
2. KRSDetail dengan berbagai mata kuliah
3. Simulasi berbagai status KRS

## Catatan Penting

-   Pastikan validasi berjalan dengan baik untuk:
    -   Periode pengisian KRS
    -   Bentrok jadwal perkuliahan
    -   Batas maksimum SKS yang dapat diambil
    -   Status KRS (draft, submitted, approved, rejected)
-   Pastikan UX tetap intuitif selama proses pengisian KRS
