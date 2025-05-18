<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak KHS - {{ $mahasiswa->nama }} - Semester {{ $semester }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            font-size: 12pt;
            color: #000;
            background: #fff;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 10px 0;
        }

        .header p {
            margin: 5px 0;
        }

        .info {
            margin-bottom: 30px;
        }

        .info table {
            width: 100%;
            border-collapse: collapse;
        }

        .info table td {
            padding: 5px 0;
        }

        .info table td:first-child {
            width: 150px;
        }

        .info table td:nth-child(2) {
            width: 15px;
            text-align: center;
        }

        .nilai {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .nilai th,
        .nilai td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .nilai th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .nilai tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .hasil {
            margin-top: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }

        .hasil table {
            width: 50%;
            border-collapse: collapse;
        }

        .hasil table td {
            padding: 5px;
        }

        .hasil table td:first-child {
            width: 65%;
        }

        .tanda-tangan {
            margin-top: 50px;
            text-align: right;
        }

        .tanda-tangan .tempat-tanggal {
            margin-bottom: 8px;
        }

        .tanda-tangan .jabatan {
            margin-bottom: 80px;
        }

        .tanda-tangan .nama {
            font-weight: bold;
            text-decoration: underline;
        }

        .print-only {
            display: block;
        }

        .no-print {
            display: none;
        }

        @media screen {
            .container {
                max-width: 1000px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                margin: 20px auto;
                padding: 40px;
            }

            .no-print {
                display: block;
                text-align: center;
                margin: 20px 0;
            }

            .print-btn {
                background-color: #4CAF50;
                color: white;
                border: none;
                padding: 10px 20px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 14px;
                cursor: pointer;
                border-radius: 4px;
            }

            .back-btn {
                background-color: #555;
                color: white;
                border: none;
                padding: 10px 20px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 14px;
                cursor: pointer;
                border-radius: 4px;
                margin-left: 10px;
            }
        }

        @media print {
            @page {
                size: A4;
                margin: 1.5cm;
            }

            .container {
                box-shadow: none;
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="no-print">
            <button class="print-btn" onclick="window.print()">Cetak KHS</button>
            <a href="{{ route('filament.mahasiswa.pages.kartu-hasil-studi') }}" class="back-btn">Kembali</a>
        </div>

        <div class="print-only">
            <div class="header">
                <h1>KARTU HASIL STUDI (KHS)</h1>
                <p>Semester {{ $semester }}</p>
                <p>{{ $tahunAkademik->nama }}</p>
                <p>{{ $mahasiswa->programStudi->nama }}</p>
                <p>Sekolah Tinggi Ekonomi Indonesia</p>
            </div>

            <div class="info">
                <table>
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{ $mahasiswa->nama }}</td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td>{{ $mahasiswa->nim }}</td>
                    </tr>
                    <tr>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>{{ $mahasiswa->programStudi->nama }}</td>
                    </tr>
                </table>
            </div>

            <table class="nilai">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode MK</th>
                        <th>Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Nilai Akhir</th>
                        <th>Grade</th>
                        <th>Bobot</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($krsDetails) > 0)
                    @foreach($krsDetails as $index => $detail)
                    @php
                    $grade = $detail->nilai->grade ?? '-';
                    $bobot = 0;

                    switch ($grade) {
                    case 'A': $bobot = 4.0; break;
                    case 'A-': $bobot = 3.7; break;
                    case 'B+': $bobot = 3.3; break;
                    case 'B': $bobot = 3.0; break;
                    case 'B-': $bobot = 2.7; break;
                    case 'C+': $bobot = 2.3; break;
                    case 'C': $bobot = 2.0; break;
                    case 'D': $bobot = 1.0; break;
                    case 'E': $bobot = 0; break;
                    default: $bobot = 0;
                    }
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detail->mataKuliah->kode }}</td>
                        <td>{{ $detail->mataKuliah->nama }}</td>
                        <td>{{ $detail->sks }}</td>
                        <td>{{ $detail->nilai ? number_format($detail->nilai->nilai_akhir, 2) : '-' }}</td>
                        <td>{{ $grade }}</td>
                        <td>{{ $bobot }}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="7" style="text-align: center;">Belum ada data nilai</td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <div class="hasil">
                <table>
                    <tr>
                        <td>Jumlah SKS</td>
                        <td>: {{ $totalSKS }}</td>
                    </tr>
                    <tr>
                        <td>Total Nilai (Bobot x SKS)</td>
                        <td>: {{ number_format($totalBobot, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Indeks Prestasi Semester</td>
                        <td>: <strong>{{ number_format($ipSemester, 2) }}</strong></td>
                    </tr>
                </table>
            </div>

            <div class="tanda-tangan">
                <div class="tempat-tanggal">Jakarta, {{ now()->format('d F Y') }}</div>
                <div class="jabatan">Ketua Program Studi</div>
                <div class="nama">.................................</div>
                <div>NIP. .........................</div>
            </div>
        </div>
    </div>

    <script>
        // Otomatis cetak saat halaman dimuat
        window.onload = function() {
            // Tampilkan halaman selama 500ms sebelum menampilkan dialog cetak
            // setTimeout(function() {
            //     window.print();
            // }, 500);
        };
    </script>
</body>

</html>