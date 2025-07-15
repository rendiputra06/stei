# Analisis Halaman KRS di Panel Mahasiswa

## Ringkasan Fitur & Alur Bisnis

1. **Akses & Tampilan**

    - Hanya mahasiswa yang login yang dapat mengakses halaman KRS mereka sendiri.
    - Data KRS yang ditampilkan difilter berdasarkan mahasiswa yang login dan tahun akademik aktif.

2. **Pembuatan & Pengisian KRS**

    - Mahasiswa dapat membuat KRS baru hanya jika:
        - Ada tahun akademik aktif.
        - Status mahasiswa pada tahun akademik tersebut adalah 'aktif'.
        - Periode pengisian KRS sedang berlangsung.
        - KRS untuk tahun akademik tersebut belum pernah dibuat.
    - Jika syarat tidak terpenuhi, mahasiswa tidak dapat membuat/mengisi KRS dan akan mendapat notifikasi error.
    - Saat KRS baru dibuat, semester dihitung otomatis berdasarkan tahun masuk dan tahun akademik.

3. **Edit, Submit, dan Status KRS**

    - KRS dapat diedit selama status masih 'draft'.
    - Setelah diajukan ('submitted'), KRS tidak dapat diedit lagi.
    - Status KRS: draft, submitted, approved, rejected.
    - Mahasiswa dapat melihat riwayat KRS dan statusnya.

4. **Validasi & Notifikasi**

    - Validasi status mahasiswa, periode pengisian, dan duplikasi KRS sudah diterapkan.
    - Notifikasi diberikan jika terjadi error atau aksi berhasil.

5. **Relasi Data**
    - KRS terhubung ke mahasiswa, tahun akademik, dan detail KRS (mata kuliah, jadwal, dsb).
    - Data KRS juga digunakan untuk proses lain seperti presensi, nilai, dan evaluasi dosen.

## Saran Pengembangan Selanjutnya

1. **Preview & Simulasi SKS**
    - Tambahkan fitur simulasi beban SKS dan prediksi IPK sebelum submit KRS.
2. **Validasi Prasyarat Mata Kuliah**
    - Otomatis cek prasyarat mata kuliah saat memilih mata kuliah di KRS.
3. **Integrasi Tagihan/Keuangan**
    - Hanya mahasiswa yang sudah melunasi tagihan tertentu yang bisa submit KRS.
4. **Riwayat Perubahan KRS**
    - Tampilkan log perubahan KRS (tambah/hapus mata kuliah, waktu submit, dsb).
5. **Notifikasi Multi-Channel**
    - Kirim notifikasi ke email/WA jika KRS disetujui/ditolak.
6. **Export/Print KRS**
    - Fitur ekspor dan cetak KRS dalam format PDF/Excel.
7. **Integrasi Penjadwalan Otomatis**
    - Saran jadwal otomatis untuk menghindari bentrok mata kuliah.
8. **Akses Orang Tua/Wali**
    - Opsi agar orang tua/wali bisa melihat KRS anaknya.
