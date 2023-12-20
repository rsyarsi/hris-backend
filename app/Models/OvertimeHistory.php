<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OvertimeHistory extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'overtime_histories';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'overtime_id',
        'user_id',
        'description',
        'ip_address',
        'user_agent',
        'comment',
    ];

    public function overtime()
    {
        return $this->belongsTo(Overtime::class, 'overtime_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
