<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $table = 'indonesia_cities';

    protected $primaryKey = 'id';

    protected $fillable = ['code', 'province_code', 'name', 'meta'];

    public function provinces()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    public function districs()
    {
        return $this->hasMany(District::class, 'city_code', 'code');
    }
}
