<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIK</th>
            <th>Email</th>
            <th>Tipe Legalitas</th>
            <th>Nomor Legalitas</th>
            <th>Nomor Kartu Keluarga</th>
            <th>Jenis Kelamin</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>Status Kawin</th>
            <th>Agama</th>
            <th>Golongan Darah</th>
            <th>Nomor Telephone</th>
            <th>NPWP</th>
            <th>Alamat Legal</th>
            <th>Kode Pos Legal</th>
            <th>Provinsi Legal</th>
            <th>Kota Legal</th>
            <th>Kecamatan Legal</th>
            <th>Desa Legal</th>
            <th>Nomor Telephone Rumah Legal</th>
            <th>Alamat Domisili</th>
            <th>Kode Pos Domisili</th>
            <th>Provinsi Domisili</th>
            <th>Kota Domisili</th>
            <th>Kecamatan Domisili</th>
            <th>Desa Domisili</th>
            <th>Nomor Telephone Rumah Domisili</th>
            <th>Status</th>
            <th>Posisi</th>
            <th>Unit</th>
            <th>Departemen</th>
            <th>Mulai Kerja</th>
            <th>Resign</th>
            <th>User Account</th>
            <th>Shift Group</th>
            <th>Kabag</th>
            <th>Supervisor</th>
            <th>Manager</th>
            <th>PIN</th>
            <th>Rekening Number</th>
            <th>BPJS Number</th>
            <th>BPJS TK Number</th>
            <th>Status Karyawan</th>
            <th>Photo</th>
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->employment_number }}</td>
            <td>{{ $item->email }}</td>
            <td>{{ $item->identityType->name ?? '' }}</td>
            <td>{{ $item->legal_identity_number }}</td>
            <td>{{ $item->family_card_number }}</td>
            <td>{{ $item->sex->name ?? '' }}</td>
            <td>{{ $item->birth_place }}</td>
            <td>{{ $item->birth_date }}</td>
            <td>{{ $item->maritalStatus->name ?? '' }}</td>
            <td>{{ $item->religion->name ?? '' }}</td>
            <td>{{ $item->phone_number }}</td>
            <td>{{ $item->blood_type }}</td>
            <td>{{ $item->tax_identify_number }}</td>
            <td>{{ $item->legal_address }}</td>
            <td>{{ $item->legal_postal_code }}</td>
            <td>{{ $item->province->name ?? '' }}</td>
            <td>{{ $item->city->name ?? '' }}</td>
            <td>{{ $item->district->name ?? '' }}</td>
            <td>{{ $item->village->name ?? '' }}</td>
            <td>{{ $item->legal_home_phone_number }}</td>
            <td>{{ $item->current_address }}</td>
            <td>{{ $item->current_postal_code }}</td>
            <td>{{ $item->currentProvince->name ?? '' }}</td>
            <td>{{ $item->currentCity->name ?? '' }}</td>
            <td>{{ $item->currentDistrict->name ?? '' }}</td>
            <td>{{ $item->currentVillage->name ?? '' }}</td>
            <td>{{ $item->current_home_phone_number }}</td>
            <td>{{ $item->statusEmployment->name ?? '' }}</td>
            <td>{{ $item->position->name ?? '' }}</td>
            <td>{{ $item->unit->name ?? '' }}</td>
            <td>{{ $item->department->name ?? '' }}</td>
            <td>{{ $item->started_at }}</td>
            <td>{{ $item->resigned_at }}</td>
            <td>{{ $item->position->name ?? '' }}</td>
            <td>{{ $item->user->name ?? '' }}</td>
            <td>{{ $item->shiftGroup->name ?? '' }}</td>
            <td>{{ $item->supervisor->name ?? '' }}</td>
            <td>{{ $item->manager->name ?? '' }}</td>
            <td>{{ $item->pin }}</td>
            <td>{{ $item->rekening_number }}</td>
            <td>{{ $item->bpjs_number }}</td>
            <td>{{ $item->bpjstk_number }}</td>
            <td>{{ $item->status_employee }}</td>
            <td>{{ $item->file_url }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
