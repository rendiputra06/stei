# Dokumentasi Fitur Jadwal Akademik

## Pendahuluan

Fitur Jadwal Akademik adalah modul yang digunakan untuk mengelola dan mengatur jadwal perkuliahan di universitas. Fitur ini terintegrasi dengan sistem manajemen akademik yang memungkinkan admin dan staf akademik untuk mengatur jadwal mata kuliah, dosen, dan ruangan.

## Akses Fitur

Fitur ini dapat diakses melalui panel admin Filament di menu Akademik:

1. Login ke sistem dengan akun yang memiliki hak akses admin
2. Navigasi ke menu "Akademik"
3. Klik "Jadwal Kuliah"

## Fitur Utama

### 1. Melihat Daftar Jadwal

Halaman utama menampilkan daftar semua jadwal perkuliahan yang terdaftar dalam sistem. Daftar ini menampilkan informasi penting seperti tahun akademik, mata kuliah, dosen, ruangan, hari, dan jam perkuliahan.

Fitur pada halaman ini:

-   **Filter**: Anda dapat memfilter jadwal berdasarkan tahun akademik, hari, dosen, ruangan, dan status aktif
-   **Pencarian**: Anda dapat mencari jadwal berdasarkan kata kunci terkait
-   **Pagination**: Menampilkan jadwal dalam format halaman untuk memudahkan navigasi

### 2. Menambahkan Jadwal Baru

Untuk menambahkan jadwal baru:

1. Klik tombol "Tambah Jadwal" di pojok kanan atas
2. Isi formulir dengan informasi jadwal:
    - Tahun Akademik
    - Mata Kuliah
    - Dosen
    - Ruangan
    - Hari
    - Jam Mulai
    - Jam Selesai
    - Kelas
    - Status Aktif
3. Klik "Simpan" untuk menyimpan jadwal

**Catatan Penting**:

-   Sistem akan otomatis memeriksa bentrok jadwal untuk dosen dan ruangan yang sama
-   Jam selesai harus lebih besar dari jam mulai
-   Jadwal harus berada dalam rentang waktu tahun akademik yang aktif

### 3. Mengedit Jadwal

Untuk mengedit jadwal yang sudah ada:

1. Klik tombol "Edit" (ikon pensil) pada baris jadwal yang ingin diedit
2. Ubah informasi yang diperlukan
3. Klik "Simpan" untuk menyimpan perubahan

### 4. Menghapus Jadwal

Untuk menghapus jadwal:

1. Klik tombol "Hapus" (ikon tempat sampah) pada baris jadwal
2. Konfirmasi penghapusan pada dialog yang muncul

Anda juga dapat menghapus beberapa jadwal sekaligus dengan mencentang kotak di sebelah kiri jadwal, kemudian menggunakan opsi "Hapus" di bagian aksi massal.

### 5. Mengaktifkan atau Menonaktifkan Jadwal

Untuk mengubah status aktif jadwal:

1. Gunakan toggle switch pada kolom "Aktif"
2. Klik toggle untuk mengubah status

## Pencegahan Bentrok Jadwal

Sistem telah dilengkapi dengan fitur pencegahan bentrok jadwal untuk memastikan bahwa:

1. Seorang dosen tidak memiliki jadwal yang bentrok pada hari dan waktu yang sama
2. Sebuah ruangan tidak digunakan oleh lebih dari satu kelas pada hari dan waktu yang sama

Jika terjadi bentrok jadwal, sistem akan menampilkan pesan error dan menolak untuk menyimpan jadwal tersebut.

## Tips Penggunaan

1. **Filter Tahun Akademik**: Selalu filter jadwal berdasarkan tahun akademik yang sedang aktif untuk memudahkan pengelolaan
2. **Pencarian Cepat**: Gunakan fitur pencarian untuk menemukan jadwal spesifik dengan cepat
3. **Penamaan Kelas**: Gunakan penamaan kelas yang konsisten (A, B, C, dll) untuk memudahkan pengelolaan kelas paralel
4. **Pemeriksaan Reguler**: Lakukan pemeriksaan rutin untuk memastikan semua jadwal sudah sesuai dan tidak ada bentrok
