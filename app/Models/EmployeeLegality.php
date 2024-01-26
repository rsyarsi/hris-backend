<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeLegality extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'employee_legalities';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'employee_id',
        'legality_type_id',
        'started_at',
        'ended_at',
        'file_url',
        'file_path',
        'file_disk',
        'no_str'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function legalityType()
    {
        return $this->belongsTo(LegalityType::class, 'legality_type_id', 'id');
    }
}
