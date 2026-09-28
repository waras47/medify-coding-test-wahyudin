<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        table.items th, table.items td { border: 1px solid #333; padding: 4px 6px; }
        .meta td { padding: 2px 6px; }
        footer { position: fixed; bottom: -20px; left: 0; right: 0; font-size: 10px; text-align: right; }
    </style>
</head>
<body>
    <h3>Detail Kategori</h3>
    <table class="meta">
        <tr><th align="left">Kode</th><td>:</td><td>{{ $data->kode }}</td></tr>
        <tr><th align="left">Nama</th><td>:</td><td>{{ $data->nama }}</td></tr>
    </table>

    <h4>Master Item dengan Kategori Ini</h4>
    <table class="items">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Harga Beli</th>
                <th>Supplier</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $mi)
            <tr>
                <td>{{ $mi->kode }}</td>
                <td>{{ $mi->nama }}</td>
                <td>{{ $mi->jenis }}</td>
                <td>{{ $mi->harga_beli }}</td>
                <td>{{ $mi->supplier }}</td>
            </tr>
            @empty
            <tr><td colspan="5">Belum ada item.</td></tr>
            @endforelse
        </tbody>
    </table>

    <footer>Dicetak pada {{ now()->format('d-m-Y H:i:s') }}</footer>
</body>
</html>
