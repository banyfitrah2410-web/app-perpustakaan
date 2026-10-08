<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Peminjaman</title>
    <style>
        body { font-family: sans-serif; margin: 40px; max-width: 600px; }
        table { border-collapse: collapse; width: 100%; margin-top: 16px; }
        th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: left; }
        .info th { width: 160px; background: #f3f4f6; }
        .badge-success { background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; display: inline-block; }
        .badge-warning { background: #fef3c7; color: #92400e; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; display: inline-block; }
        .badge-danger { background: #fee2e2; color: #b91c1c; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; display: inline-block; }
    </style>
</head>
<body>
    <h1>Detail Peminjaman</h1>
    <p><a href="{{ route('loans.index') }}">&larr; Kembali ke daftar peminjaman</a></p>

    <table class="info">
        <tr>
            <th>Anggota</th>
            <td>{{ $loan['member']['nama'] }} ({{ $loan['member']['nim'] }})</td>
        </tr>
        <tr>
            <th>Petugas</th>
            <td>{{ $loan['user']['name'] }}</td>
        </tr>
        <tr>
            <th>Tanggal Pinjam</th>
            <td>{{ $loan['tanggal_pinjam'] }}</td>
        </tr>
        <tr>
            <th>Tanggal Kembali</th>
            <td>{{ $loan['tanggal_kembali'] }}</td>
        </tr>
        <tr>
            <th>Tanggal Dikembalikan</th>
            <td>{{ $loan['tanggal_dikembalikan'] ?? '-' }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                @php
                    $badgeClass = 'badge-warning';
                    if ($loan['status'] === 'dikembalikan') $badgeClass = 'badge-success';
                    if ($loan['status'] === 'terlambat') $badgeClass = 'badge-danger';
                @endphp
                <span class="{{ $badgeClass }}">{{ ucfirst($loan['status']) }}</span>
            </td>
        </tr>
    </table>

    <h2>Buku yang Dipinjam</h2>
    <table>
        <thead>
            <tr>
                <th>Judul</th>
                <th>Penulis</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($loan['loanItems'] as $item)
                <tr>
                    <td>{{ $item['book']['judul'] }}</td>
                    <td>{{ $item['book']['penulis'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
