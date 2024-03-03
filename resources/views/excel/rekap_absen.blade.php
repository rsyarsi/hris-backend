<!-- monitoring_absen_rekap.blade.php -->
<table>
    <thead>
        <tr>
            <th colspan="2" align="center">Rekap Absensi {{ $year }}</th>
            @foreach($monthNames as $item)
            <th colspan="3" align="center">{{ $item }}</th>
            @endforeach
        </tr>
        <tr>
            <th align="center">#</th>
            <th align="center">Unit Name</th>
            @for ($i = 0; $i < count($months); $i++)
                <th align="center">Tdk Telat</th>
                <th align="center">Jml Kary</th>
                <th align="center">% Unit</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach($units as $key => $unit)
        <tr>
            <td align="center">{{ $key + 1 }}</td>
            <td>{{ $unit->name }}</td>
            @foreach($months as $month)
                <td align="center">{{ $absences[$unit->id][$month] ?? 0 }}</td>
                <td align="center">{{ $employees[$unit->id] ?? 0 }}</td>
                @php
                    $absentCount = $absences[$unit->id][$month] ?? 0;
                    $employeeCount = $employees[$unit->id] ?? 0;
                    $percentage = $employeeCount > 0 ? number_format(($absentCount / $employeeCount), 2) : 0;
                @endphp
                <td align="center">{{ $percentage }}%</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2" align="center">Total Pencapaian</td>
            @foreach($months as $month)
            <td>{{ array_sum(array_column($absences, null)) ?? 0 }}</td>
            <td>{{ array_sum(array_column($employees, null)) ?? 0 }}</td>
            <td></td>
            @endforeach
        </tr>
    </tfoot>
</table>
