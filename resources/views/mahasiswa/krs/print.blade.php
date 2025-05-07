<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Rencana Studi (KRS) - {{ $mahasiswa->nama }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .university-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .faculty-name {
            font-size: 16px;
            margin-bottom: 5px;
        }
        .logo {
            max-width: 80px;
            margin: 0 auto 10px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
            text-align: center;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px;
        }
        .info-table td:first-child {
            width: 150px;
            font-weight: bold;
        }
        .courses-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .courses-table th, .courses-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .courses-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .courses-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            width: 80%;
            margin: 50px auto 10px;
        }
        .status-approved {
            color: green;
            font-weight: bold;
        }
        .status-rejected {
            color: red;
            font-weight: bold;
        }
        .status-submitted {
            color: orange;
            font-weight: bold;
        }
        .status-draft {
            color: blue;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
        }
        .print-button {
            margin: 20px 0;
            text-align: center;
        }
        .print-button button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .print-button button:hover {
            background-color: #45a049;
        }
        @media print {
            .print-button {
                display: none;
            }
            body {
                margin: 0;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="print-button">
        <button onclick="window.print()">Cetak KRS</button>
    </div>
    
    <div class="header">
        <div class="university-name">UNIVERSITAS STEI</div>
        <div class="faculty-name">FAKULTAS TEKNOLOGI INFORMASI</div>
    </div>
    
    <div class="title">KARTU RENCANA STUDI (KRS)</div>
    
    <table class="info-table">
        <tr>
            <td>Nama</td>
            <td>: {{ $mahasiswa->nama }}</td>
            <td>Semester</td>
            <td>: {{ $krs->semester }}</td>
        </tr>
        <tr>
            <td>NIM</td>
            <td>: {{ $mahasiswa->nim }}</td>
            <td>Program Studi</td>
            <td>: {{ $mahasiswa->programStudi->nama }}</td>
        </tr>
        <tr>
            <td>Tahun Akademik</td>
            <td>: {{ $tahunAkademik->nama }}</td>
            <td>Status KRS</td>
            <td>: 
                <span class="status-{{ $krs->status }}">
                    {{ $krs::getStatusList()[$krs->status] }}
                </span>
            </td>
        </tr>
    </table>
    
    <table class="courses-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode MK</th>
                <th>Nama Mata Kuliah</th>
                <th>SKS</th>
                <th>Kelas</th>
                <th>Jadwal</th>
                <th>Ruangan</th>
                <th>Dosen</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; $totalSKS = 0; @endphp
            @forelse($krsDetails as $detail)
                @if($detail->status === 'aktif')
                    @php $totalSKS += $detail->sks; @endphp
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $detail->mataKuliah->kode }}</td>
                        <td>{{ $detail->mataKuliah->nama }}</td>
                        <td>{{ $detail->sks }}</td>
                        <td>{{ $detail->kelas }}</td>
                        <td>{{ $detail->jadwal->hari }}, {{ $detail->jadwal->jam_mulai->format('H:i') }} - {{ $detail->jadwal->jam_selesai->format('H:i') }}</td>
                        <td>{{ $detail->jadwal->ruangan->nama }}</td>
                        <td>{{ $detail->jadwal->dosen->nama }}</td>
                    </tr>
                @endif
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Belum ada mata kuliah yang dipilih</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Total SKS</td>
                <td>{{ $totalSKS }}</td>
                <td colspan="4"></td>
            </tr>
        </tbody>
    </table>
    
    <div class="signature">
        <div class="signature-box">
            <p>Mahasiswa</p>
            <div class="signature-line"></div>
            <p>{{ $mahasiswa->nama }}<br>{{ $mahasiswa->nim }}</p>
        </div>
        
        <div class="signature-box">
            <p>Dosen Wali / Pembimbing Akademik</p>
            <div class="signature-line"></div>
            @if($mahasiswa->pembimbing)
                <p>{{ $mahasiswa->pembimbing->nama }}<br>NIDN: {{ $mahasiswa->pembimbing->nidn }}</p>
            @else
                <p>-</p>
            @endif
        </div>
    </div>
</body>
</html>