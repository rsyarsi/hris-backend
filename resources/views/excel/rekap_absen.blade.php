<!-- monitoring_absen_rekap.blade.php -->
<table>
    <thead>
        <tr>
            <th colspan="2" align="center">Rekap Absensi {{ $year }}</th>
            @foreach($months as $month)
            <th colspan="3" align="center">{{ $month }}</th>
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
                <td align="center">{{ $employees[$unit->id] > 0 ? number_format(($absences[$unit->id][$month] ?? 0) / $employees[$unit->id] * 100, 2) : 0 }}%</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
