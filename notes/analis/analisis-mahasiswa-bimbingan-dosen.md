# Analisis Halaman Mahasiswa Bimbingan (Panel Dosen)

## 1. Tujuan & Logika Bisnis

-   Halaman ini bertujuan untuk menampilkan daftar mahasiswa yang berada di bawah bimbingan akademik (PA) dari dosen yang sedang login.
-   Dosen dapat memonitor data akademik dasar setiap mahasiswa bimbingannya, seperti NIM, nama, program studi, semester berjalan, IPK, dan status aktif.
-   Halaman ini berfungsi sebagai dashboard bagi dosen untuk memantau kemajuan akademik dan melakukan aksi terkait pembimbingan.

## 2. Hubungan dengan Halaman KRS Mahasiswa

Hubungan antara halaman "Mahasiswa Bimbingan" dan halaman KRS bersifat **langsung dan krusial**.

-   **Tombol Aksi "Lihat KRS"**: Pada setiap baris data mahasiswa, terdapat tombol aksi "Lihat KRS".
-   **Fungsi Tombol**: Tombol ini mengarahkan dosen ke halaman daftar KRS milik mahasiswa yang dipilih, namun di dalam **panel dosen**. Dosen tidak masuk ke panel mahasiswa.
-   **Tujuan**: Alur ini dirancang untuk mempermudah dosen pembimbing dalam menjalankan tugasnya, yaitu:
    -   Melihat riwayat KRS mahasiswa bimbingan.
    -   Memverifikasi mata kuliah yang diambil pada KRS yang sedang diajukan.
    -   Menyetujui (approve) atau menolak (reject) KRS yang telah diajukan oleh mahasiswa.

## 3. Alur Kerja Dosen dalam Persetujuan KRS

1. Dosen login ke panel dosen.
2. Dosen membuka menu "Mahasiswa Bimbingan".
3. Dosen melihat daftar mahasiswa bimbingannya. Jika ada mahasiswa yang mengajukan KRS (biasanya ditandai dengan status "submitted" pada data KRS), dosen akan melakukan verifikasi.
4. Dosen mengklik tombol "Lihat KRS" pada baris mahasiswa yang relevan.
5. Dosen akan diarahkan ke halaman detail KRS mahasiswa tersebut, di mana ia dapat melihat daftar mata kuliah yang diambil, total SKS, dan melakukan aksi persetujuan (approve/reject) disertai catatan jika perlu.

## 4. File & Komponen Terkait

-   **Resource Utama**: `app/Filament/Dosen/Resources/MahasiswaBimbinganResource.php`
    -   Mengatur query untuk hanya menampilkan mahasiswa bimbingan dari dosen yang login.
    -   Mendefinisikan tabel dan kolom yang ditampilkan.
    -   Mendefinisikan `Action` untuk tombol "Lihat KRS" yang mengarah ke route resource `KrsMahasiswaResource`.
-   **Resource Terkait**: `app/Filament/Dosen/Resources/KrsMahasiswaResource.php`
    -   Mengatur halaman KRS di sisi dosen, yang menampilkan KRS milik mahasiswa yang dipilih dari halaman bimbingan.
-   **Model**: `Pembimbingan`, `Dosen`, `Mahasiswa`, `KRS`.
    -   Relasi antara dosen dan mahasiswa bimbingan diatur melalui tabel `pembimbingan`.

## Kesimpulan

Halaman "Mahasiswa Bimbingan" adalah **titik awal (entry point)** bagi dosen untuk melakukan salah satu tugas utamanya, yaitu validasi dan persetujuan KRS. Keterhubungan antara kedua halaman ini membentuk alur kerja yang efisien untuk proses pembimbingan akademik.
