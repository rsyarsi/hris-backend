<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Jenis</th>
            <th>Dari</th>
            <th>Sampai</th>
            <th>Durasi(Menit)</th>
            <th>Cuti Awal</th>
            <th>Sisa Cuti</th>
            <th>Status</th>
            <th>File Url</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $item->employee->name ?? '' }}</td>
            <td>{{ $item->leaveType->name ?? '' }}</td>
            <td>{{ date("d-m-Y H:i:s", strtotime($item->from_date)) }}</td>
            <td>{{ date("d-m-Y H:i:s", strtotime($item->to_date)) }}</td>
            <td>{{ $item->duration }}</td>
            <td>{{ $item->quantity_cuti_awal }}</td>
            <td>{{ $item->sisa_cuti }}</td>
            <td>{{ $item->leaveStatus->name ?? '' }}</td>
            <td>{{ $item->file_url }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
