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
        'user_id',
        'name',
        'short_description',
        'note',
        'file_url',
        'file_path',
        'file_disk',
        'image_url',
        'image_path',
        'image_disk',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
