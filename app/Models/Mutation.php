<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mutation extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'mutations';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'user_created_id',
        'employee_id',
        'before_unit_id',
        'after_unit_id',
        'date',
        'note',
        'no_sk',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function userCreated()
    {
        return $this->belongsTo(User::class, 'user_created_id', 'id');
    }

    public function unitBefore()
    {
        return $this->belongsTo(Unit::class, 'before_unit_id', 'id');
    }

    public function unitAfter()
    {
        return $this->belongsTo(Unit::class, 'after_unit_id', 'id');
    }
}
