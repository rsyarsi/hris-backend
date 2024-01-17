<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PromotionDemotion extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'promotion_demotions';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'user_created_id',
        'employee_id',
        'type',
        'unit_id',
        'before_position_id',
        'after_position_id',
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

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function positionBefore()
    {
        return $this->belongsTo(Position::class, 'before_position_id', 'id');
    }

    public function positionAfter()
    {
        return $this->belongsTo(Position::class, 'after_position_id', 'id');
    }
}