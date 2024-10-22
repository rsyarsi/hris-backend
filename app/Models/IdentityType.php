<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentityType extends Model
{
    use HasFactory;
    
    protected $table = 'midentitytypes';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'active'];
}
