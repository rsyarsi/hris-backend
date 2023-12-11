<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CatatanCuti extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'catatan_cuti';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'employee_id',
        'quantity_awal',
        'quantity_akhir',
        'quantity_in',
        'quantity_out',
        'type',
        'description',
        'batal',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
