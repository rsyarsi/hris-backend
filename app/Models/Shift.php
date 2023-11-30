<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shift extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'shifts';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'shift_group_id',
        'code',
        'name',
        'in_time',
        'out_time',
        'finger_in_less',
        'finger_in_more',
        'finger_out_less',
        'finger_out_more',
        'night_shift',
        'active',
        'user_created_id',
        'user_updated_id',
        'libur',
    ];

    public function shiftGroup()
    {
        return $this->belongsTo(ShiftGroup::class, 'shift_group_id', 'id');
    }

    public function userCreated()
    {
        return $this->belongsTo(User::class, 'user_created_id', 'id');
    }

    public function userUpdated()
    {
        return $this->belongsTo(User::class, 'user_updated_id', 'id');
    }
}
