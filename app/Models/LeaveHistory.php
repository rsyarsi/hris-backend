<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveHistory extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'leave_histories';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'leave_id',
        'user_id',
        'description',
        'ip_address',
        'user_agent',
        'comment',
    ];

    public function leave()
    {
        return $this->belongsTo(Leave::class, 'leave_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
