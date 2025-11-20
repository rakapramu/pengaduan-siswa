<!DOCTYPE html>
<html>

<head>
    <title>Laporan Konseling</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 20px;
        }

        .header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
            margin-bottom: 15px;
        }

        .header img {
            float: left;
            width: 70px;
        }

        .header h2 {
            margin: 0;
            font-size: 22px;
            font-weight: bold;
        }

        .header p {
            margin-top: 3px;
            font-size: 14px;
        }

        .title {
            text-align: center;
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 12px;
        }

        th {
            background: #435ebe;
            color: #fff;
            padding: 8px;
            text-transform: uppercase;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #cfcfcf;
        }

        tr:nth-child(even) {
            background: #f6f6f6;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 20px;
            width: 100%;
            font-size: 12px;
            text-align: right;
            color: #555;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="{{ public_path('dashboard/img/logo.jpeg') }}" alt="Logo">
        <h2>Sistem Pengaduan & Konseling BK</h2>
        <p>{{ env('APP_NAME') }} • Tahun {{ date('Y') }}</p>
    </div>

    <div class="title">Laporan Sesi Konseling</div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Siswa</th>
                <th>Guru BK</th>
                <th>Topik</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $k)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $k->siswa->nama ?? '' }}</td>
                    <td>{{ $k->guru->nama ?? '' }}</td>
                    <td>{{ $k->topik }}</td>
                    <td>{{ \Carbon\Carbon::parse($k->tanggal)->format('d-m-Y') }}</td>
                    <td>{{ ucfirst($k->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ date('d-m-Y H:i') }}
    </div>

</body>

</html>
