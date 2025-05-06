# Task List Pengembangan Fitur Status Mahasiswa

## Deskripsi

Fitur Status Mahasiswa merupakan fitur untuk menampilkan dan mengelola status mahasiswa pada tahun akademik tertentu. Status ini mencakup data semester, IP semester, IPK, SKS semester, total SKS, dan keterangan tambahan. Fitur ini akan menampilkan data berdasarkan tahun akademik yang aktif secara default, dan memungkinkan pengguna untuk memfilter data berdasarkan berbagai kriteria.

## Task List

### Setup Database & Model

-   [x] Membuat migration untuk tabel `status_mahasiswa`
-   [x] Membuat model `StatusMahasiswa`
-   [x] Membuat factory `StatusMahasiswaFactory`
-   [x] Membuat seeder `StatusMahasiswaSeeder`
-   [x] Menambahkan StatusMahasiswaSeeder ke DatabaseSeeder

### Filament Admin Panel

-   [x] Membuat Filament Resource untuk StatusMahasiswa
-   [x] Membuat form untuk StatusMahasiswa (create & edit)
-   [x] Implementasi tampilan data dalam tabel
-   [x] Menambahkan fitur filter dan pencarian
-   [x] Implementasi soft delete dan restore

### Livewire Components

-   [x] Membuat Livewire component StatusMahasiswaTable
-   [x] Membuat Livewire component StatusMahasiswaPage
-   [x] Menerapkan filter untuk tahun akademik aktif
-   [x] Menerapkan filter untuk status, program studi, dll
-   [x] Membuat view untuk komponen Livewire

### Pengembangan Fitur

-   [x] Menerapkan filter wajib untuk tahun akademik
-   [x] Menampilkan informasi tahun akademik aktif di halaman
-   [x] Menambahkan validasi untuk data akademik (IP semester, IPK, SKS)
-   [x] Menambahkan keterangan yang sesuai dengan status

### Integrasi

-   [ ] Mengintegrasikan status mahasiswa dengan fitur KRS
-   [ ] Mengintegrasikan status mahasiswa dengan fitur nilai
-   [ ] Menambahkan fitur untuk update status mahasiswa secara otomatis
-   [ ] Membuat fitur untuk export data status mahasiswa ke Excel/PDF

### Testing

-   [x] Membuat file test untuk fitur Status Mahasiswa
-   [x] Menambahkan test untuk komponen Livewire
-   [x] Menambahkan test untuk filter berdasarkan tahun akademik
-   [ ] Menambahkan test untuk validasi input
-   [ ] Menambahkan test untuk soft delete dan restore

### Dokumentasi

-   [ ] Membuat dokumentasi penggunaan fitur Status Mahasiswa
-   [ ] Menambahkan panduan cara menggunakan filter
-   [ ] Menambahkan panduan cara menginterpretasikan data status mahasiswa

## Catatan Tambahan

-   Pastikan bahwa tahun akademik aktif dipilih secara default pada filter
-   Pastikan data mahasiswa tidak ditampilkan sampai filter tahun akademik dipilih
-   Pastikan tampilan status mahasiswa memudahkan untuk melihat status per program studi
-   Pastikan ada validasi untuk mencegah duplikasi status mahasiswa pada tahun akademik yang sama
