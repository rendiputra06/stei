# Analisis Proyek STEI

## Ringkasan Proyek

Proyek ini adalah sistem informasi akademik untuk STEI (Sekolah Tinggi Elektronika dan Informatika) yang dibangun menggunakan framework Laravel dengan panel administrasi Filament. Sistem ini mengelola berbagai aspek administrasi akademik seperti data mahasiswa, dosen, mata kuliah, KRS, dan jadwal perkuliahan.

## Halaman dan Fitur yang Sudah Dibuat

### Panel Admin (Filament)

1. **Manajemen Pengguna**

    - Pengelolaan user/pengguna
    - Manajemen peran (roles) dan izin (permissions)

2. **Manajemen Data Master**

    - Program Studi
    - Gedung
    - Ruangan
    - Kurikulum
    - Mata Kuliah
    - Tahun Akademik
    - Status Mahasiswa
    - Jadwal Perkuliahan

3. **Manajemen Civitas Akademik**

    - Data Mahasiswa
    - Data Dosen
    - Pembimbingan (relasi dosen-mahasiswa)

4. **Manajemen Akademik**
    - KRS (Kartu Rencana Studi)
    - Status Mahasiswa (Aktif/Cuti/dll)

### Panel Mahasiswa

1. **Manajemen KRS**

    - Pembuatan KRS baru
    - Penambahan mata kuliah ke KRS
    - Cetak KRS

2. **Halaman Status Mahasiswa**
    - Melihat status akademik mahasiswa (aktif/cuti/dll)

### Fitur Autentikasi

1. **Login Multi-Role**

    - Login sebagai admin/staff
    - Login sebagai dosen
    - Login sebagai mahasiswa

2. **Fitur Impersonasi**
    - Login sebagai pengguna lain (untuk admin)
    - Return to admin (kembali ke akun admin)

## Saran Pengembangan Selanjutnya

### 1. Pengembangan Panel Dosen

Berdasarkan struktur folder, panel dosen sudah disiapkan tetapi belum dikembangkan secara penuh. Saran pengembangan:

-   **Manajemen Perkuliahan**

    -   Daftar mata kuliah yang diampu
    -   Input nilai mahasiswa
    -   Presensi mahasiswa
    -   Upload materi perkuliahan

-   **Bimbingan Akademik**
    -   Melihat daftar mahasiswa bimbingan
    -   Persetujuan KRS mahasiswa
    -   Monitoring perkembangan akademik mahasiswa bimbingan

### 2. Pengembangan Panel Mahasiswa

-   **Kartu Hasil Studi (KHS)**

    -   Halaman untuk melihat dan mencetak KHS per semester
    -   Rekap nilai keseluruhan

-   **Transkrip Nilai**

    -   Halaman untuk melihat dan mencetak transkrip nilai
    -   Perhitungan IPK otomatis

-   **Presensi/Kehadiran**
    -   Melihat rekap kehadiran per mata kuliah
    -   Status kehadiran (hadir/izin/sakit/alpa)

### 3. Pengembangan Fitur Akademik

-   **Sistem Penjadwalan Otomatis**

    -   Algoritma untuk menghindari bentrok jadwal
    -   Optimasi penggunaan ruangan

-   **Skripsi/Tugas Akhir**

    -   Pengajuan judul
    -   Bimbingan dengan dosen pembimbing
    -   Penjadwalan sidang

-   **Manajemen Perwalian**
    -   Penjadwalan perwalian
    -   Laporan hasil perwalian

### 4. Pengembangan UI/UX

-   **Tema dan Branding**

    -   Penyesuaian tampilan dengan identitas institusi
    -   Implementasi tema responsif untuk mobile

-   **Dashboard Analitik**
    -   Visualisasi data akademik untuk pengambilan keputusan
    -   Statistik performa mahasiswa dan dosen

### 5. Integrasi dan Ekspansi

-   **Integrasi dengan Sistem Keuangan**

    -   Status pembayaran kuliah
    -   Pembayaran online

-   **Sistem Notifikasi**

    -   Email/SMS untuk pengumuman penting
    -   Notifikasi deadline KRS/pembayaran

-   **API untuk Aplikasi Mobile**
    -   Pengembangan REST API untuk aplikasi mobile
    -   Autentikasi JWT untuk keamanan

### 6. Peningkatan Keamanan

-   **Audit Log**

    -   Pencatatan aktivitas pengguna
    -   Monitoring akses sistem

-   **Backup dan Recovery**
    -   Sistem backup otomatis
    -   Prosedur disaster recovery

## Kesimpulan

Proyek STEI sudah memiliki fondasi yang kuat dengan implementasi fitur dasar untuk administrasi akademik. Fokus pengembangan selanjutnya sebaiknya pada fitur yang meningkatkan pengalaman pengguna, terutama untuk dosen dan mahasiswa, serta penambahan fitur-fitur yang mendukung proses akademik lanjutan seperti manajemen skripsi dan analitik data.
