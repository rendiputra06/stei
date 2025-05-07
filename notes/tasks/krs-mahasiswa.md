# Pengembangan Fitur KRS Mahasiswa

## Daftar Tugas

### Database & Models

-   [x] Membuat migration `create_krs_table`
-   [x] Membuat migration `create_krs_detail_table`
-   [x] Membuat model `KRS` dengan relasi ke Mahasiswa, TahunAkademik
-   [x] Membuat model `KRSDetail` dengan relasi ke KRS, Jadwal, MataKuliah

### Halaman Panel Mahasiswa

-   [x] Membuat halaman Custom Page KRS di panel Filament Mahasiswa
-   [x] Membuat widget informasi Tahun Akademik aktif
-   [x] Membuat widget status mahasiswa (aktif/cuti/lulus/dll)
-   [x] Membuat widget informasi periode pengisian KRS
-   [x] Menampilkan daftar mata kuliah yang ditawarkan sesuai kurikulum aktif
-   [x] Membuat fungsi untuk memilih/menambah mata kuliah ke KRS
-   [x] Membuat fungsi untuk menghapus mata kuliah dari KRS
-   [x] Membuat tombol untuk finalisasi KRS
-   [x] Menampilkan jadwal mata kuliah yang dipilih

### Validasi

-   [x] Validasi periode pengisian KRS
-   [x] Validasi status mahasiswa (hanya mahasiswa aktif yang dapat mengisi KRS)
-   [x] Validasi mata kuliah (tidak boleh bentrok jadwal)
-   [ ] Validasi kuota kelas
-   [ ] Validasi jumlah SKS maksimal sesuai IP semester

### Policies & Authorization

-   [x] Membuat policy untuk KRS
-   [x] Mengatur authorization agar mahasiswa hanya dapat mengakses KRS miliknya
-   [x] Mengatur authorization agar mahasiswa hanya dapat mengisi KRS pada periode pengisian

### Pengujian

-   [ ] Membuat unit test untuk model KRS
-   [ ] Membuat unit test untuk model KRSDetail
-   [ ] Membuat feature test untuk halaman pengisian KRS

## Fitur yang Sudah Diimplementasikan

1. Mahasiswa dapat melihat informasi tahun akademik aktif
2. Mahasiswa dapat melihat status dan informasi akademiknya
3. Mahasiswa dapat melihat periode pengisian KRS
4. Mahasiswa dapat memilih mata kuliah yang ditawarkan sesuai kurikulum program studinya
5. Mahasiswa dapat menghapus mata kuliah yang sudah dipilih
6. Mahasiswa dapat melihat daftar mata kuliah yang dipilih
7. Sistem melakukan validasi jadwal bentrok saat pemilihan mata kuliah
8. Sistem hanya memperbolehkan mahasiswa dengan status aktif untuk mengisi KRS
9. Sistem hanya memperbolehkan pengisian KRS pada periode yang ditentukan
10. Mahasiswa dapat melakukan finalisasi KRS setelah selesai memilih mata kuliah
11. KRS yang sudah difinalisasi tidak dapat diubah lagi

## Fitur yang Akan Dikembangkan Selanjutnya

1. Validasi kuota kelas saat pemilihan mata kuliah
2. Validasi jumlah SKS maksimal berdasarkan IP semester sebelumnya
3. Persetujuan KRS oleh dosen pembimbing akademik
4. Cetak KRS yang sudah disetujui
5. Notifikasi status KRS ke mahasiswa
6. Pencarian dan filter mata kuliah yang ditawarkan
