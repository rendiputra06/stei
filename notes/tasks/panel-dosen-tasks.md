# Task List Pengembangan Panel Dosen

## 1. Pengembangan Database

### Manajemen Perkuliahan

-   [ ] Buat tabel `presensi` dengan field:

    -   id (PK)
    -   jadwal_id (FK)
    -   pertemuan_ke (integer)
    -   tanggal (date)
    -   keterangan (text, nullable)
    -   created_at, updated_at

-   [ ] Buat tabel `presensi_detail` dengan field:

    -   id (PK)
    -   presensi_id (FK)
    -   mahasiswa_id (FK)
    -   status (enum: 'hadir', 'izin', 'sakit', 'alpa')
    -   keterangan (text, nullable)
    -   created_at, updated_at

-   [ ] Buat tabel `materi_perkuliahan` dengan field:

    -   id (PK)
    -   jadwal_id (FK)
    -   judul (string)
    -   deskripsi (text, nullable)
    -   file_path (string, nullable)
    -   pertemuan_ke (integer)
    -   tanggal (date)
    -   created_at, updated_at

-   [ ] Buat tabel `nilai` dengan field:
    -   id (PK)
    -   krs_detail_id (FK)
    -   nilai_tugas (decimal, nullable)
    -   nilai_uts (decimal, nullable)
    -   nilai_uas (decimal, nullable)
    -   nilai_kehadiran (decimal, nullable)
    -   nilai_akhir (decimal, nullable)
    -   grade (string, nullable)
    -   keterangan (text, nullable)
    -   created_at, updated_at

### Migrasi Tabel

-   [ ] Buat file migrasi untuk tabel `presensi`
-   [ ] Buat file migrasi untuk tabel `presensi_detail`
-   [ ] Buat file migrasi untuk tabel `materi_perkuliahan`
-   [ ] Buat file migrasi untuk tabel `nilai`
-   [ ] Jalankan migrasi

## 2. Pengembangan Model

-   [ ] Buat model `Presensi`
-   [ ] Buat model `PresensiDetail`
-   [ ] Buat model `MateriPerkuliahan`
-   [ ] Buat model `Nilai`
-   [ ] Perbaharui model terkait untuk relasi (Jadwal, KrsDetail, Mahasiswa)

## 3. Seeder Data

-   [ ] Buat seeder `PresensiSeeder`
-   [ ] Buat seeder `PresensiDetailSeeder`
-   [ ] Buat seeder `MateriPerkuliahanSeeder`
-   [ ] Buat seeder `NilaiSeeder`
-   [ ] Update `DatabaseSeeder.php` untuk memanggil seeder baru

## 4. Panel Dosen - Manajemen Perkuliahan

-   [ ] Buat resource `JadwalDosenResource` untuk menampilkan mata kuliah yang diampu
-   [ ] Buat resource `PresensiResource` untuk manajemen presensi mahasiswa
-   [ ] Buat resource `MateriPerkuliahanResource` untuk upload materi perkuliahan
-   [ ] Buat resource `NilaiResource` untuk input nilai mahasiswa

## 5. Panel Dosen - Bimbingan Akademik

-   [ ] Buat resource `MahasiswaBimbinganResource` untuk melihat daftar mahasiswa bimbingan
-   [ ] Buat resource `KrsMahasiswaResource` untuk melihat dan menyetujui KRS mahasiswa
-   [ ] Buat resource `MonitoringAkademikResource` untuk monitoring perkembangan akademik mahasiswa

## 6. Widget dan Dashboard

-   [ ] Buat widget `JadwalMengajarWidget` untuk menampilkan jadwal mengajar di dashboard
-   [ ] Buat widget `PendingKrsWidget` untuk menampilkan daftar KRS yang menunggu persetujuan
-   [ ] Buat widget `MahasiswaBimbinganWidget` untuk menampilkan jumlah mahasiswa bimbingan
-   [ ] Redesign dashboard dosen

## 7. Kebijakan Akses (Policy)

-   [ ] Buat policy untuk `Presensi` dan `PresensiDetail`
-   [ ] Buat policy untuk `MateriPerkuliahan`
-   [ ] Buat policy untuk `Nilai`
-   [ ] Update policy untuk `KRS` terkait persetujuan dosen

## 8. Pengujian

-   [ ] Uji semua fitur panel dosen
-   [ ] Perbaiki bug jika ditemukan
-   [ ] Dokumentasikan fitur-fitur baru

## 9. Deployment

-   [ ] Pastikan migrasi berjalan dengan baik
-   [ ] Pastikan seeder berjalan dengan baik
-   [ ] Pastikan policy berfungsi dengan baik
