# Task List Pengembangan Fitur Tahun Akademik

## Deskripsi

Fitur Tahun Akademik merupakan fitur untuk manajemen tahun akademik pada sistem akademik universitas. Fitur ini memungkinkan admin untuk membuat, mengatur, dan mengelola data tahun akademik yang mencakup periode pengisian KRS dan periode pengisian nilai oleh dosen.

## Task List

### Setup Database & Model

-   [x] Membuat migration untuk tabel `tahun_akademik`
-   [x] Membuat model `TahunAkademik`
-   [x] Membuat factory `TahunAkademikFactory`
-   [x] Membuat seeder `TahunAkademikSeeder`
-   [x] Menambahkan TahunAkademikSeeder ke DatabaseSeeder

### Filament Admin Panel

-   [x] Membuat Filament Resource untuk TahunAkademik
-   [x] Membuat form untuk TahunAkademik (create & edit)
-   [x] Implementasi tampilan data dalam tabel
-   [x] Menambahkan fitur filter dan pencarian
-   [x] Implementasi soft delete dan restore
-   [x] Membuat fungsi untuk mengaktifkan/menonaktifkan tahun akademik

### Pengembangan Fitur

-   [x] Membuat sistem untuk memastikan hanya satu tahun akademik yang aktif
-   [x] Menambahkan validasi untuk tanggal mulai dan tanggal selesai
-   [x] Menambahkan validasi untuk periode KRS
-   [x] Menambahkan validasi untuk periode pengisian nilai
-   [x] Menambahkan notifikasi saat mengubah status aktif tahun akademik

### Testing

-   [x] Membuat file test untuk fitur Tahun Akademik
-   [ ] Menambahkan test untuk validasi input
-   [ ] Menambahkan test untuk pengaktifan/penonaktifan tahun akademik
-   [ ] Menambahkan test untuk soft delete dan restore

### Integrasi

-   [ ] Mengintegrasikan data tahun akademik dengan fitur KRS
-   [ ] Mengintegrasikan data tahun akademik dengan fitur pengisian nilai
-   [ ] Menampilkan informasi tahun akademik aktif di dashboard

### Dokumentasi

-   [ ] Membuat dokumentasi penggunaan fitur Tahun Akademik
-   [ ] Menambahkan panduan cara mengaktifkan/menonaktifkan tahun akademik
-   [ ] Menambahkan panduan cara mengatur periode KRS dan pengisian nilai

## Catatan Tambahan

-   Pastikan bahwa hanya admin yang memiliki akses ke fitur Tahun Akademik
-   Pastikan bahwa saat mengaktifkan satu tahun akademik, semua tahun akademik lainnya dinonaktifkan secara otomatis
-   Pastikan ada validasi untuk periode KRS dan periode pengisian nilai agar tidak tumpang tindih dan sesuai dengan periode tahun akademik
