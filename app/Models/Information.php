<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Information extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'informations';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'name',
        'note',
        'user_id',
        'file_url',
        'file_path',
        'file_disk',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
