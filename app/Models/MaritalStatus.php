<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaritalStatus extends Model
{
    use HasFactory;

    protected $table = 'mmaritalstatuses';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'active'];
}
