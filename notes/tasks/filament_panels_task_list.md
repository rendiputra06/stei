# Task List: Implementasi Panel Filament dan Login Kustom

## Panel Filament

-   [x] Analisis struktur proyek dan konfigurasi yang ada
-   [x] Buat Provider untuk panel dosen
-   [x] Buat Provider untuk panel mahasiswa
-   [x] Implementasi middleware untuk pembatasan akses berdasarkan role
-   [x] Nonaktifkan halaman login default untuk semua panel

## Custom Login Page

-   [x] Buat halaman login kustom
-   [x] Buat controller untuk autentikasi
-   [x] Implementasi fitur quick login untuk dev environment
-   [x] Hubungkan halaman login kustom dengan ketiga panel (admin, dosen, mahasiswa)
-   [x] Buat sistem redirect ke panel yang sesuai setelah login berdasarkan role

## Langkah Selanjutnya

-   [x] Buat folder struktur untuk panel Dosen
    -   [x] Buat folder `app/Filament/Dosen/Pages`
    -   [x] Buat folder `app/Filament/Dosen/Resources`
    -   [x] Buat folder `app/Filament/Dosen/Widgets`
-   [x] Buat folder struktur untuk panel Mahasiswa
    -   [x] Buat folder `app/Filament/Mahasiswa/Pages`
    -   [x] Buat folder `app/Filament/Mahasiswa/Resources`
    -   [x] Buat folder `app/Filament/Mahasiswa/Widgets`
-   [x] Buat dashboard untuk panel Dosen
-   [x] Buat dashboard untuk panel Mahasiswa

## Testing & Deployment

-   [ ] Test login dan akses ke panel admin dengan role admin
-   [ ] Test login dan akses ke panel dosen dengan role dosen
-   [ ] Test login dan akses ke panel mahasiswa dengan role mahasiswa
-   [ ] Test pembatasan akses (dosen tidak bisa akses panel mahasiswa, dan sebaliknya)
-   [ ] Test fitur quick login di dev environment

## Langkah Selanjutnya

-   [ ] Tambahkan lebih banyak fitur dan halaman untuk panel Dosen
    -   [ ] Halaman untuk mengelola mata kuliah yang diajar
    -   [ ] Halaman untuk melihat jadwal mengajar
    -   [ ] Halaman untuk melihat mahasiswa yang dibimbing
-   [ ] Tambahkan lebih banyak fitur dan halaman untuk panel Mahasiswa
    -   [ ] Halaman untuk melihat jadwal kuliah
    -   [ ] Halaman untuk melihat nilai
    -   [ ] Halaman untuk mengisi KRS
-   [ ] Tingkatkan keamanan dengan menambahkan pencatatan aktivitas (activity logging)
