# Task List Pengembangan Fitur Manajemen Pengguna

## 1. Instalasi dan Konfigurasi

-   [x] Membuat folder notes/tasks
-   [x] Instalasi Filament v3
-   [x] Instalasi Spatie Permission
-   [x] Konfigurasi Filament
-   [x] Konfigurasi Spatie Permission

## 2. Pembuatan Model dan Database

-   [x] Menyiapkan migrasi untuk tabel roles dan permissions
-   [x] Menyiapkan migrasi untuk memodifikasi tabel users (hanya is_active, tanpa is_admin)
-   [x] Membuat model Role dan Permission
-   [x] Modifikasi model User untuk menggunakan Spatie Permission

## 3. Pembuatan Resource Filament

-   [x] Membuat Resource untuk User
-   [x] Membuat Resource untuk Role
-   [x] Membuat Resource untuk Permission
-   [x] Implementasi relasi antar resource

## 4. Pembuatan Halaman Admin

-   [x] Setup Panel Admin Filament
-   [ ] Konfigurasi halaman dashboard
-   [ ] Konfigurasi navigasi panel admin
-   [x] Implementasi fitur autentikasi dan otorisasi

## 5. Policies Berbasis Role dan Permission

-   [x] Membuat UserPolicy menggunakan Spatie Permission
-   [x] Membuat RolePolicy menggunakan Spatie Permission
-   [x] Membuat PermissionPolicy menggunakan Spatie Permission

## 6. Pembuatan Seeders dan Testing

-   [x] Membuat seeder untuk users default
-   [x] Membuat seeder untuk roles (super_admin, admin, dosen, mahasiswa) dan permissions default
-   [x] Membuat test untuk halaman user management (menggunakan FQCN)
-   [x] Membuat test untuk halaman role management (menggunakan FQCN)
-   [x] Membuat test untuk proses autentikasi dan otorisasi (menggunakan FQCN)

## 7. Fitur Tambahan

-   [ ] Implementasi fitur export data pengguna
-   [ ] Implementasi fitur import data pengguna
-   [ ] Implementasi fitur log aktivitas
-   [ ] Implementasi fitur reset password
-   [ ] Implementasi fitur verifikasi email

## 8. Catatan Penting

Beberapa catatan penting dalam pengembangan:

1. Menggunakan role (super_admin, admin, dosen, mahasiswa) untuk menentukan izin akses, bukan field is_admin
2. Akses panel admin diberikan kepada pengguna dengan role super_admin, admin, dan dosen yang aktif
3. Untuk menjalankan seeder roles dan permissions, gunakan perintah: `php artisan db:seed --class=RoleAndPermissionSeeder`
4. Untuk masuk ke panel admin, gunakan kredensial:
    - Super Admin: superadmin@example.com / password
    - Admin: admin@example.com / password
    - Dosen: dosen@example.com / password
    - Mahasiswa: mahasiswa@example.com / password (tidak bisa akses panel admin)
5. Testing menggunakan Fully Qualified Class Names (FQCN) untuk memperjelas dependensi

## 9. Langkah Selanjutnya

1. Jalankan migrasi ulang: `php artisan migrate:fresh`
2. Jalankan seeder dengan perintah: `php artisan db:seed --class=RoleAndPermissionSeeder`
3. Akses panel admin di URL: `/admin`
4. Lanjutkan pengembangan fitur yang belum selesai seperti fitur export/import data pengguna
5. Perbaiki dan uji semua test yang telah dibuat

## 10. Penutup

Pengembangan fitur manajemen pengguna dengan Filament v3 dan Spatie Permission telah berhasil dilakukan. Fitur ini mencakup:

1. Manajemen user dengan role (super_admin, admin, dosen, mahasiswa) dan permission
2. Autentikasi dan otorisasi berbasis role menggunakan policy
3. Interface admin yang modern dengan Filament
4. Seeder untuk data awal pengguna dan permission
5. Test untuk memastikan fitur berjalan dengan baik menggunakan FQCN
