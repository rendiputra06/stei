# Task List Pengembangan Fitur Kurikulum dan Mata Kuliah

## Persiapan

-   [x] Analisis kebutuhan fitur kurikulum dan mata kuliah
-   [x] Merancang struktur database untuk entitas Kurikulum dan Mata Kuliah
-   [x] Merancang relasi antar entitas (ProgramStudi, Kurikulum, MataKuliah)

## Implementasi Database

-   [x] Membuat migration untuk tabel `kurikulum`
-   [x] Membuat migration untuk tabel `mata_kuliah`
-   [x] Menerapkan relasi dan foreign key constraints
-   [x] Verifikasi struktur database (relasi dan constraints)

## Model

-   [x] Membuat model `Kurikulum`
-   [x] Membuat model `MataKuliah`
-   [x] Mengimplementasikan relasi pada model `Kurikulum`:
    -   [x] Relasi `belongsTo` ke `ProgramStudi`
    -   [x] Relasi `hasMany` ke `MataKuliah`
-   [x] Mengimplementasikan relasi pada model `MataKuliah`:
    -   [x] Relasi `belongsTo` ke `Kurikulum`
    -   [x] Relasi `belongsTo` ke `ProgramStudi`
-   [x] Mengupdate model `ProgramStudi` untuk menambahkan relasi dengan `Kurikulum` dan `MataKuliah`

## Seeder

-   [x] Membuat `KurikulumSeeder`
-   [x] Membuat `MataKuliahSeeder`
-   [x] Menambahkan kedua seeder ke `DatabaseSeeder`

## Filament Resources

-   [x] Membuat `KurikulumResource`
    -   [x] Implementasi form untuk create/edit
    -   [x] Implementasi tabel untuk list
    -   [x] Implementasi filter dan search
    -   [x] Implementasi bulk actions
-   [x] Membuat `MataKuliahResource`
    -   [x] Implementasi form untuk create/edit
    -   [x] Implementasi relasi dependent dropdown (Program Studi → Kurikulum)
    -   [x] Implementasi tabel untuk list
    -   [x] Implementasi filter dan search
    -   [x] Implementasi bulk actions

## Pages

-   [x] Membuat pages untuk `KurikulumResource`:
    -   [x] `ListKurikulums`
    -   [x] `CreateKurikulum`
    -   [x] `EditKurikulum`
-   [x] Membuat pages untuk `MataKuliahResource`:
    -   [x] `ListMataKuliahs`
    -   [x] `CreateMataKuliah`
    -   [x] `EditMataKuliah`

## Testing

-   [x] Membuat test untuk `Kurikulum`:
    -   [x] Test untuk halaman list
    -   [x] Test untuk fungsi create
    -   [x] Test untuk fungsi edit
    -   [x] Test untuk fungsi delete
    -   [x] Test untuk validasi
-   [x] Membuat test untuk `MataKuliah`:
    -   [x] Test untuk halaman list
    -   [x] Test untuk fungsi create
    -   [x] Test untuk fungsi edit
    -   [x] Test untuk fungsi delete
    -   [x] Test untuk validasi

## Dokumentasi

-   [x] Membuat task list (file ini)
-   [ ] Menambahkan dokumentasi ke README.md
-   [ ] Menambahkan dokumentasi kode

## Pengembangan Lanjutan

-   [ ] Implementasi fitur eksport data kurikulum dan mata kuliah (PDF/Excel)
-   [ ] Implementasi import data dari Excel
-   [ ] Implementasi fitur drag-and-drop untuk menyusun mata kuliah di kurikulum
-   [ ] Implementasi visualisasi struktur kurikulum (bagan/grafik)
-   [ ] Implementasi fitur persetujuan kurikulum (approval workflow)
