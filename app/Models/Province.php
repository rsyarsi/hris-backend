<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = 'indonesia_provinces';

    protected $primaryKey = 'id';

    protected $fillable = ['code', 'name', 'meta'];

    public function cities()
    {
        return $this->hasMany(City::class, 'province_code', 'code');
    }
}
