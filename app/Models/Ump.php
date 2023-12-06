<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ump extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'umps';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = ['year', 'nominal'];
}
