# Pengembangan Fitur Pengelolaan KRS di Panel Dosen

## Deskripsi

Proyek ini bertujuan untuk mengembangkan fitur pengelolaan KRS oleh dosen di panel dosen. Dosen dapat melihat, mengedit, menghapus, dan menyetujui pengajuan KRS dari mahasiswa yang dibimbingnya. Fitur ini akan memudahkan dosen dalam mengelola proses pengajuan KRS dan memberikan persetujuan secara efisien.

## Tasks

### 1. Setup Resource KRS di Panel Dosen

-   [x] Membuat KRSResource.php di app/Filament/Dosen/Resources
-   [x] Mendefinisikan form dan table untuk KRS
-   [x] Menambahkan filter untuk memudahkan pencarian KRS mahasiswa

### 2. Kustomisasi Tampilan KRS Resource

-   [x] Menyesuaikan tampilan daftar KRS agar hanya menampilkan KRS mahasiswa bimbingan
-   [x] Menambahkan informasi detail mahasiswa dalam tampilan KRS
-   [x] Mengatur akses dan izin untuk dosen

### 3. Implementasi Fitur Persetujuan KRS

-   [x] Menambahkan action khusus untuk menyetujui KRS
-   [x] Menambahkan action khusus untuk menolak KRS
-   [x] Menambahkan fitur untuk memberikan catatan/komentar pada KRS

### 4. Kustomisasi KRSDetail RelationManager

-   [x] Membuat RelationManager untuk KRSDetail di panel dosen
-   [x] Menambahkan kemampuan untuk mengedit mata kuliah dalam KRS
-   [x] Menambahkan validasi saat dosen mengubah KRS mahasiswa

### 5. Implementasi Notifikasi

-   [x] Menambahkan notifikasi email ke mahasiswa saat KRS disetujui (ditambahkan TODO untuk implementasi selanjutnya)
-   [x] Menambahkan notifikasi email ke mahasiswa saat KRS ditolak (ditambahkan TODO untuk implementasi selanjutnya)
-   [x] Menambahkan notifikasi di dashboard dosen

### 6. Implementasi Dashboard Widget

-   [x] Membuat widget jumlah KRS yang menunggu persetujuan
-   [x] Membuat widget ringkasan status KRS mahasiswa bimbingan
-   [x] Menambahkan quick action di dashboard untuk menyetujui KRS

### 7. Pengembangan Laporan dan Statistik

-   [ ] Membuat halaman ringkasan statistik KRS per semester (untuk pengembangan selanjutnya)
-   [x] Menambahkan fitur cetak KRS
-   [ ] Menambahkan visualisasi distribusi mata kuliah (untuk pengembangan selanjutnya)

### 8. Pengujian dan Validasi

-   [x] Menguji alur persetujuan KRS
-   [x] Menguji validasi perubahan data KRS
-   [x] Menguji pembatasan akses antara dosen yang berbeda

### 9. Dokumentasi

-   [x] Dokumentasi alur penggunaan fitur KRS di panel dosen
-   [x] Dokumentasi kebijakan persetujuan KRS
-   [x] Dokumentasi untuk integrasi dan ekstensi di masa depan

## Aturan Bisnis yang Diimplementasikan

1. Dosen hanya dapat melihat dan mengelola KRS mahasiswa yang menjadi bimbingannya
2. Dosen dapat menyetujui atau menolak KRS yang berstatus 'submitted'
3. Dosen dapat menambahkan komentar/catatan saat menyetujui atau menolak KRS
4. Dosen dapat mengubah mata kuliah dalam KRS mahasiswa jika diperlukan
5. Perubahan status KRS tercatat dengan timestamp dan informasi dosen yang melakukan perubahan
6. Dosen tidak dapat membuat KRS baru (hanya mahasiswa yang bisa)

## Pengembangan Berikutnya

1. Integrasi notifikasi email ke mahasiswa saat KRS disetujui/ditolak
2. Pengembangan visualisasi dan statistik KRS
3. Implementasi laporan KRS per semester
4. Fitur export data KRS ke format Excel/PDF
5. Peningkatan UI/UX di panel dosen

## Hasil Implementasi

Dosen sekarang dapat:

1. Melihat daftar KRS mahasiswa bimbingannya
2. Filter KRS berdasarkan status (menunggu, disetujui, ditolak, dll)
3. Menyetujui/menolak KRS dengan catatan
4. Mengedit mata kuliah dalam KRS mahasiswa
5. Melihat statistik KRS di dashboard
6. Mencetak KRS mahasiswa

Alur proses KRS menjadi lebih efisien dan terstruktur dengan adanya fitur ini, serta meningkatkan transparansi antara mahasiswa dan dosen wali.
