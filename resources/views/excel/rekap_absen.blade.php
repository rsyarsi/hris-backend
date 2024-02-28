<!-- monitoring_absen_rekap.blade.php -->
<table>
    <thead>
        <tr>
            <th>Employee ID</th>
            <th>Name</th>
            <th>January</th>
            <th>February</th>
            <!-- Add more month columns as needed -->
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
        <tr>
            <td>{{ $row['employee_id'] }}</td>
            <td>{{ $row['name'] }}</td>
            <td>{{ $row['january'] }}</td>
            <td>{{ $row['february'] }}</td>
            <!-- Add more month data columns as needed -->
        </tr>
        @endforeach
    </tbody>
</table>
