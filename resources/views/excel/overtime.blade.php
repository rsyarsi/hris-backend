<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Jenis</th>
            <th>Pekerjaan</th>
            <th>Mulai</th>
            <th>Sampai</th>
            <th>Ganti Libur</th>
            <th>Durasi</th>
            <th>Status</th>
            <th>Note</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $item->employee->name ?? '' }}</td>
            <td>{{ $item->type }}</td>
            <td>{{ $item->task }}</td>
            <td>{{ date("d-m-Y H:i:s", strtotime($item->from_date)) }}</td>
            <td>{{ date("d-m-Y H:i:s", strtotime($item->to_date)) }}</td>
            <td>{{ $item->libur == '1' ? 'YA' : 'TIDAK' }}</td>
            <td>{{ $item->duration }}</td>
            <td>{{ $item->overtimeStatus->name ?? '' }}</td>
            <td>{{ $item->note }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
