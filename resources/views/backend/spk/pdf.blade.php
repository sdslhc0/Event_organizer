<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Pemilihan Paket Jasa</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            color: #000;
            font-size: 12px;
            margin: 20px;
        }
        .kop-surat {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: table;
            width: 100%;
        }
        .logo {
            display: table-cell;
            width: 20%;
            vertical-align: middle;
        }
        .logo-text {
            font-family: Arial, sans-serif;
            font-weight: bold;
            font-size: 28px;
            color: #90A4AE; /* Warna kebiruan/abu sesuai logo BE PRO */
            letter-spacing: 2px;
            line-height: 0.9;
        }
        .logo-text span {
            color: #E57373; /* Warna merah/orange sesuai logo BE PRO */
            display: block;
        }
        .kop-text {
            display: table-cell;
            width: 80%;
            text-align: right;
            vertical-align: middle;
        }
        .kop-text h2 {
            margin: 0 0 10px 0;
            font-size: 20px;
            font-weight: bold;
        }
        .kop-text p {
            margin: 0;
            font-size: 11px;
            color: #333;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            font-weight: bold;
            border-bottom: 2px solid #000;
            border-top: 2px solid #000;
        }
        /* Selang-seling warna baris seperti di gambar */
        tbody tr:nth-child(even) {
            background-color: #F5E8D3; /* Warna cream/coklat muda */
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="kop-surat">
        <div class="logo">
            <!-- Meniru logo referensi dengan teks -->
            <div class="logo-text">
                MELODI
            </div>
        </div>
        <div class="kop-text">
            <h2>Melodi Wedding,Sanggar & Salon</h2>
            <p>Jl. Taman Wisma Asri II No.12, RT.002/RW.025, Tlk. Pucung</p>
            <p>Kec. Bekasi Utara, Kota Bekasi, Jawa Barat 17121</p>
        </div>
    </div>

    <div class="title">
        Laporan Data Pemilihan Paket Bundling
        <div style="font-size: 11px; font-weight: normal; margin-top: 4px; color: #444;">
            Bobot Kriteria AHP ({{ $ahpWeights['jumlah_responden'] ?? 0 }} Responden): Harga ({{ number_format($ahpWeights['harga'] ?? 0, 4) }}) &middot; Kualitas ({{ number_format($ahpWeights['kualitas'] ?? 0, 4) }}) &middot; Fasilitas ({{ number_format($ahpWeights['fasilitas'] ?? 0, 4) }}) &middot; CR = {{ number_format($ahpWeights['cr'] ?? 0, 4) }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 22%;">Kode Paket Bundling</th>
                <th style="width: 48%;">Nama Paket Bundling</th>
                <th style="width: 30%; text-align: center;">Hasil Penilaian (Skor AHP)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pakets as $p)
                <tr>
                    <td>PB{{ str_pad($p->paket_id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $p->paketBundling->nama }}</td>
                    <td class="text-center">{{ number_format($p->skor_akhir, 4) }} ({{ number_format($p->skor_akhir * 100, 1) }}%)</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
