<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Religion extends Model
{
    use HasFactory;

    protected $table = 'mreligions';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'active'];
}
