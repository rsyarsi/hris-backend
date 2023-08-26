<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'mdepartments';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'active'];
}
