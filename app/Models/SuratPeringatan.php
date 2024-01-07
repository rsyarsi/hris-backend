<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuratPeringatan extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'surat_peringatan';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'user_created_id',
        'employee_id',
        'date',
        'no_surat',
        'type',
        'jenis_pelanggaran',
        'keterangan',
        'batal',
        'file_url',
        'file_path',
        'file_disk',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function userCreated()
    {
        return $this->belongsTo(User::class, 'user_created_id', 'id');
    }
}
