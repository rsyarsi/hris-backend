<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>User Device ID</th>
            <th>Firbase ID</th>
            <th>Imei</th>
            <th>IP</th>
            <th>Username</th>
            <th>Administrator Access</th>
            <th>HRD Access</th>
            <th>Manager Access</th>
            <th>Supervisor Access</th>
            <th>Employee Access</th>
            <th>Kabag Access</th>
            <th>Staff Access</th>
            <th>Active</th>
            <th>Verified</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->email }}</td>
            <td>{{ $item->user_device_id }}</td>
            <td>{{ $item->firebase_id }}</td>
            <td>{{ $item->imei }}</td>
            <td>{{ $item->ip }}</td>
            <td>{{ $item->username }}</td>
            <td>{{ $item->administrator }}</td>
            <td>{{ $item->hrd }}</td>
            <td>{{ $item->manager }}</td>
            <td>{{ $item->supervisor }}</td>
            <td>{{ $item->pegawai }}</td>
            <td>{{ $item->kabag }}</td>
            <td>{{ $item->staff }}</td>
            <td>{{ $item->active }}</td>
            <td>{{ $item->verified }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
