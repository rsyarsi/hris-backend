<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ethnic extends Model
{
    use HasFactory;

    protected $table = 'methnics';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'active'];
}
