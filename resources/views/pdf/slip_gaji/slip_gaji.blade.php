<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
        <p>PERIODE : {{ date("M Y", strtotime($item->period_payroll)) }}</p>
        <p>NIK : {{ $item->employeement_id }}</p>
        <p>NAMA KARYAWAN : {{ $item->employee_name }}</p>
        <p>DEPARTMEN : {{ $item->employee_department_name }}</p>
        <p>JABATAN : {{ $item->employee_position_name }}</p>
        <p>MARITAL STATUS : nama field apa?</p>

        <p></p>
        <p>-----Pendapatan Tetap-----</p>
        <p>GAJI POKOK : {{ number_format($item->employee_fix_gapok,0,",",".") }}</p>
        <p>T. TRANSPORTASI : {{ number_format($item->employee_fix_transport,0,",",".") }}</p>
        <p>T. UANG MAKAN : {{ number_format($item->employee_fix_uangmakan,0,",",".") }}</p>
        <p>T. KEMAHALAN : {{ number_format($item->employee_fix_tunjangankemahalan,0,",",".") }}</p>
        <p>T. JABATAN : {{ number_format($item->employee_tunjangan_jabatan,0,",",".") }}</p>
        <p>T. DINAS MALAM : {{ number_format($item->employee_tunjangan_dinasmalam,0,",",".") }}</p>
        <p>T. PPR : {{ number_format($item->employee_tunjangan_tunjanganppr,0,",",".") }}</p>
        <p>T. Hospital On Duty : nama field apa?</p>
        <p>T. INSENTIF KHUSUS : {{ number_format($item->employee_tunjangan_intensifkhusus,0,",",".") }}</p>
        <p>T. EXTRA FOODING : {{ number_format($item->employee_tunjangan_extrafooding,0,",",".") }}</p>
        <p>Uang Lembur : nama field apa?</p>
        <p>Pengembalian Potongan / Rapel : nama field apa?</p>
        <p>PPH 21 : {{ number_format($item->liability_employee_pph21,0,",",".") }}</p>

        <p></p>
        <p>-----Potongan - Potongan Karyawan-----</p>
        <p>BPJS Ketenagakerjaan (Karyawan) JHT (2%) : {{ number_format($item->liability_employee_jht,0,",",".") }}</p>
        <p>BPJS Jaminan Pensiun (Karyawan)(1%) : {{ number_format($item->liability_employee_jp,0,",",".") }}</p>
        <p>BPJS Kesehatan (Karyawan) (1%) : {{ number_format($item->liability_employee_bpjskesehatan,0,",",".") }}</p>
        <p>Voucher Makan : nama field apa?</p>
        <p>Potongan Kehadiran : nama field apa?</p>
        <p>Potongan Lain-lain : nama field apa?</p>
        <p>Sub Total : nama field apa?</p>
        <p>THR : nama field apa?</p>

        <p></p>
        <p>-----Zakat-----</p>
        <p>THP SEBELUM ZAKAT : {{ number_format($item->salary_total_before_zakat,0,",",".") }}</p>
        <p>ZAKAT : {{ number_format($item->zakat,0,",",".") }}</p>
        <p>THP SESUDAH ZAKAT : {{ number_format($item->salary_after_zakat,0,",",".") }}</p>

        <p>-----Tunjangan Tidak Langsung-----</p>
        <p>BPJS Tenaga Kerja (Perusahaan) : {{ number_format($item->liability_companies_bpjskesehatan,0,",",".") }}</p>
        <p>JHT (3,7%) : {{ number_format($item->liability_companies_jht,0,",",".") }}</p>
        <p>JKK (0,24%) : {{ number_format($item->liability_companies_jkk,0,",",".") }}</p>
        <p>JKM (0,30%) : {{ number_format($item->liability_companies_jkm,0,",",".") }}</p>
        <p>BPJS Jaminan Pensiun (Perusahaan) (2%) : {{ number_format($item->liability_companies_jp,0,",",".") }}</p>
        <p>BPJS Kesehatan (Perusahaan) (4%) : {{ number_format($item->liability_companies_bpjskesehatan,0,",",".") }}</p>

        <p></p>
        <p>-----Keterangan-----</p>
        <p>No. BPJS Tenaga Kerja : nama field apa?</p>
        <p>No. BPJS Kesehatan : nama field apa?</p>
    </body>
</html>
