<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalityType extends Model
{
    use HasFactory;

    protected $table = 'mlegalitytypes';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'active', 'extended'];
}
