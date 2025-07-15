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

## Analisa dan Saran Pengembangan Panel Filament Dosen

### Fitur yang Sudah Ada

Panel dosen pada sistem ini sudah memiliki beberapa fitur utama, antara lain:

-   **Dashboard Dosen**: Menampilkan ringkasan dan akses cepat ke fitur-fitur utama dosen.
-   **Jadwal Mengajar**: Dosen dapat melihat daftar jadwal mengajar yang aktif, lengkap dengan detail mata kuliah, ruangan, kelas, dan jumlah mahasiswa.
-   **Presensi Mahasiswa**: Dosen dapat mengelola presensi mahasiswa per pertemuan, termasuk detail kehadiran, keterangan, dan statistik kehadiran.
-   **Manajemen Materi Perkuliahan**: Fitur untuk mengunggah dan mengelola materi perkuliahan setiap pertemuan.
-   **Input dan Manajemen Nilai**: Dosen dapat menginput nilai tugas, UTS, UAS, dan nilai akhir mahasiswa per kelas.
-   **Persetujuan KRS Mahasiswa**: Dosen pembimbing dapat melihat dan menyetujui KRS mahasiswa bimbingan.
-   **Daftar Mahasiswa Bimbingan**: Dosen dapat melihat data mahasiswa bimbingan, status akademik, dan perkembangan IPK/total SKS.
-   **Widget Statistik**: Tersedia widget untuk menampilkan statistik jumlah mahasiswa bimbingan, KRS yang menunggu persetujuan, dan distribusi status mahasiswa.
-   **Absensi QR Code**: Fitur untuk generate QR code absensi perkuliahan.

### Saran Pengembangan Fitur Baru Panel Dosen

Berdasarkan analisa kebutuhan dosen dan tren sistem akademik modern, berikut beberapa saran pengembangan fitur baru yang dapat meningkatkan produktivitas dan pengalaman dosen:

1. **Notifikasi Otomatis**

    - Notifikasi pengingat jadwal mengajar, deadline input nilai, dan pengajuan KRS mahasiswa bimbingan.
    - Notifikasi via email atau aplikasi mobile (jika ada integrasi).

2. **Rekapitulasi Kehadiran dan Nilai**

    - Fitur untuk mengunduh rekap kehadiran dan nilai mahasiswa dalam format Excel/PDF.
    - Visualisasi grafik kehadiran dan performa nilai kelas.

3. **Forum Diskusi Kelas**

    - Fasilitas diskusi antara dosen dan mahasiswa per kelas/mata kuliah.
    - Mendukung pengumuman, tanya jawab, dan pengumpulan tugas ringan.

4. **Penjadwalan Konsultasi/Bimbingan**

    - Fitur booking jadwal konsultasi antara dosen dan mahasiswa bimbingan.
    - Integrasi dengan kalender (Google Calendar/Outlook) untuk sinkronisasi jadwal.

5. **Manajemen Tugas dan Penilaian Online**

    - Dosen dapat membuat tugas, menerima pengumpulan tugas online, dan melakukan penilaian langsung di sistem.
    - Mendukung upload file tugas dan feedback langsung ke mahasiswa.

6. **Riwayat Aktivitas Dosen**

    - Log aktivitas dosen seperti input nilai, presensi, upload materi, dan persetujuan KRS.
    - Dapat digunakan untuk pelaporan beban kerja dosen (BKD).

7. **Integrasi Penilaian Kinerja Dosen (Edom)**

    - Dosen dapat melihat hasil evaluasi kinerja dari mahasiswa secara privat.
    - Fitur feedback dan tindak lanjut hasil evaluasi.

8. **Fitur Penunjang Akreditasi**

    - Otomatisasi rekap data yang dibutuhkan untuk akreditasi prodi (misal: rekap presensi, nilai, materi, dan aktivitas bimbingan).

9. **Mobile Friendly & Akses Cepat**

    - Optimalisasi tampilan panel dosen untuk perangkat mobile.
    - Shortcut/quick action untuk fitur yang sering digunakan.

10. **Sistem Penilaian Rubrik**
    - Mendukung penilaian berbasis rubrik untuk tugas/proyek tertentu.
    - Dosen dapat membuat rubrik penilaian sendiri sesuai kebutuhan.

### Catatan Tambahan

-   Pastikan setiap fitur baru memperhatikan aspek keamanan data dan privasi, terutama untuk data nilai dan evaluasi.
-   Lakukan uji coba usability pada dosen sebelum fitur baru dirilis secara penuh.
-   Dokumentasi penggunaan fitur panel dosen sebaiknya disediakan dalam bentuk video/tutorial singkat.

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
