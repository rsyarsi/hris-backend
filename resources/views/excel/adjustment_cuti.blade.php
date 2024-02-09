<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIP</th>
            <th>Tahun</th>
            <th>Jumlah Awal</th>
            <th>Jumlah Akhir</th>
            <th>Jumlah Perubahan</th>
            <th>Deskripsi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $item->employee->name ?? '' }}</td>
            <td>{{ $item->employee->employment_number ?? '' }}</td>
            <td>{{ $item->year }}</td>
            <td>{{ $item->quantity_awal }}</td>
            <td>{{ $item->quantity_akhir }}</td>
            <td>{{ $item->quantity_adjustment }}</td>
            <td>{{ $item->description }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
