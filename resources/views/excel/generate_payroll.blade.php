<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Periode</th>
            <th>Nama</th>
            <th>NIP</th>
            <th>Unit</th>
            {{-- <th>Gaji</th>
            <th>Jumlah Perubahan</th>
            <th>Deskripsi</th> --}}
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $item->period_payroll }}</td>
            <td>{{ $item->employee_name }}</td>
            <td>{{ $item->employeement_id }}</td>
            <td>{{ $item->employee_unit_name }}</td>
            {{-- <td>{{ $item->quantity_akhir }}</td>
            <td>{{ $item->quantity_adjustment }}</td>
            <td>{{ $item->description }}</td> --}}
        </tr>
        @endforeach
    </tbody>
</table>
