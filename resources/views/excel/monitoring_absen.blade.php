<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Kode Shift</th>
            <th>Shift</th>
            <th>Tanggal</th>
            <th>Hari</th>
            <th>Jadwal Masuk</th>
            <th>Jadwal Pulang</th>
            <th>Presensi Masuk</th>
            <th>Presensi Pulang</th>
            <th>Terlambat</th>
            <th>Pulang Awal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $item->employee->name ?? '' }}</td>
            <td>{{ $item->shift->code ?? '' }}</td>
            <td>{{ $item->shift->name ?? '' }}</td>
            <td>{{ date("d-m-Y", strtotime($item->date)) }}</td>
            <td>{{ $item->day }}</td>
            <td>{{ $item->schedule_date_in_at }}</td>
            <td>{{ $item->schedule_date_out_at }}</td>
            <td>{{ $item->time_in_at }}</td>
            <td>{{ $item->time_out_at }}</td>
            <td>{{ $item->telat }}</td>
            <td>{{ $item->pa }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
