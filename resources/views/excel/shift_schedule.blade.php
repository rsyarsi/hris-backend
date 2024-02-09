<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIP</th>
            <th>Kode Shift</th>
            <th>Shift</th>
            <th>Tanggal</th>
            <th>Hari</th>
            <th>Jadwal Masuk</th>
            <th>Jadwal Pulang</th>
            <th>Libur</th>
            <th>Libur Nasional</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $item->employee->name ?? '' }}</td>
            <td>{{ $item->employee->employment_number ?? '' }}</td>
            <td>{{ $item->shift->code ?? '' }}</td>
            <td>{{ $item->shift->name ?? '' }}</td>
            <td>{{ date("d-m-Y", strtotime($item->date)) }}</td>
            <td>{{ date("l", strtotime($item->date)) }}</td>
            <td>{{ $item->time_in }}</td>
            <td>{{ $item->time_out }}</td>
            <td>{{ $item->holiday }}</td>
            <td>{{ $item->national_holiday }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
