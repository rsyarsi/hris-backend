<!-- monitoring_absen_rekap.blade.php -->
<table>
    <thead>
        <tr>
            <th colspan="2">Rekap Absensi {{ $year }}</th>
            @foreach($months as $month)
            <th colspan="3">{{ $month }}</th>
            @endforeach
        </tr>
        <tr>
            <th>#</th>
            <th>Unit Name</th>
            @for ($i = 0; $i < count($months); $i++)
                <th>Tdk Telat</th>
                <th>Jml Kary</th>
                <th>% Unit</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach($units as $key => $unit)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $unit->name }}</td>
            @for ($i = 0; $i < count($months); $i++)
                <td></td> <!-- Display generate_absen data count where employee_id and telat bigger 60 -->
                <td>{{ $employees[$unit->id] ?? 0 }}</td> <!-- Display employee count for this unit -->
                <td></td>
            @endfor
        </tr>
        @endforeach
    </tbody>
</table>
