<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Anggota</title>
    <style>
        @page { margin: 0px; }
        body { margin: 0px; font-family: 'Helvetica', sans-serif; }

        .card-page {
            width: 85.6mm;
            height: 53.98mm;
            position: relative;
            overflow: hidden;
            page-break-after: always;
        }

        .background {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
        }

        .foto-profil {
            position: absolute;
            top: 17.5mm;
            left: 58mm;
            width: 20mm;
            height: 27mm;
            object-fit: cover;
            border: 0.5mm solid #D4B26A;
        }

        .nama {
            position: absolute;
            top: 20mm;
            left: 7.5mm;
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            color: #0B2347;
        }

        .nia {
            position: absolute;
            top: 26mm;
            left: 7.5mm;
            font-size: 7pt;
            color: #333;
        }

        .rayon {
            position: absolute;
            top: 32mm;
            left: 7.5mm;
            font-size: 7pt;
            color: #333;
        }

        .kategori {
            position: absolute;
            top: 38mm;
            left: 7.5mm;
            font-size: 7pt;
            font-weight: bold;
            color: #0B2347;
        }
    </style>
</head>
<body>
    <div class="card-page">
        <img src="{{ public_path('images/kta/kartu-depan.jpg') }}" class="background">

        @if($anggota->foto)
            <img src="{{ public_path('storage/' . $anggota->foto) }}" class="foto-profil">
        @endif

        <div class="nama">{{ $anggota->nama }}</div>
        <div class="nia">NIA. {{ $anggota->nia }}</div>
        <div class="rayon">RAYON {{ strtoupper($anggota->rayon->name ?? '-') }}</div>
        <div class="kategori">{{ strtoupper($anggota->kategoriAnggota->nama_kategori ?? '-') }}</div>
    </div>

    <div class="card-page">
        <img src="{{ public_path('images/kta/kartu-belakang.jpg') }}" class="background">
    </div>
</body>
</html>
