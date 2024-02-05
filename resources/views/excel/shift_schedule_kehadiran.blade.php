<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Unit</th>
            <th>Kode Shift</th>
            <th>Shift</th>
            <th>Tanggal</th>
            <th>Hari</th>
            <th>Jenis</th>
            <th>Jadwal Masuk</th>
            <th>Jadwal Pulang</th>
            <th>Presensi Masuk</th>
            <th>Presensi Pulang</th>
            <th>Terlambat</th>
            <th>Pulang Awal</th>
            <th>Catatan Cuti</th>
            <th>Catatan Absen</th>
            <th>Catatan Lembur</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $item->employee_name }}</td>
            <td>{{ $item->unit_name }}</td>
            <td>{{ $item->shift_code }}</td>
            <td>{{ $item->shift_name }}</td>
            <td>{{ date("d-m-Y", strtotime($item->shift_schedule_date)) }}</td>
            <td>{{ date("l", strtotime($item->shift_schedule_date)) }}</td>
            <td>{{ $item->generate_absen_type }}</td>
            <td>{{ $item->in_time }}</td>
            <td>{{ $item->out_time }}</td>
            <td>{{ $item->generate_absen_time_in_at }}</td>
            <td>{{ $item->generate_absen_time_out_at }}</td>
            <td>{{ $item->generate_absen_telat }}</td>
            <td>{{ $item->generate_absen_pa }}</td>
            <td>{{ $item->leave_note }}</td>
            <td>{{ $item->generate_absen_note }}</td>
            <td>{{ $item->overtime_note }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
