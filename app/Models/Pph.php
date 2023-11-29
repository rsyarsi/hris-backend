<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pph extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'pph';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'employee_id',
        'nilai',
        'period',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
