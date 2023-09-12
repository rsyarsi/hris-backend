<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeCertificate extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'employee_certificates';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'employee_id',
        'name',
        'institution_name',
        'started_at',
        'ended_at',
        'file_url',
        'file_path',
        'file_disk',
        'verified_at',
        'verified_user_Id',
        'is_extended',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
