<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillType extends Model
{
    use HasFactory;

    protected $table = 'mskilltypes';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'active'];
}
