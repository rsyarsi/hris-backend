<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    // Define table name (if it's not inferred from the model's plural form)
    protected $table = 'mdepartments';

    // Define fillable fields
    protected $fillable = ['name', 'active'];
}
