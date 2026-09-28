<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kategori</th>
            <th>Nama Items</th>
            <th>Nama Supplier</th>
            <th>Harga</th>
            <th>Laba</th>
            <th>Harga Jual</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->kategoris->pluck('nama')->implode(', ') }}</td>
            <td>{{ $item->nama }}</td>
            <td>{{ $item->supplier }}</td>
            <td>{{ $item->harga_beli }}</td>
            <td>{{ $item->laba }}</td>
            <td>{{ $item->harga_beli + $item->harga_beli * $item->laba / 100 }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
