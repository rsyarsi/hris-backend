<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobVacancy extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'job_vacancies';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'user_created_id',
        'education_id',
        'title',
        'position',
        'description',
        'start_date',
        'end_date',
        'min_age',
        'max_age',
        'experience',
        'note',
        'status',
    ];

    public function education()
    {
        return $this->belongsTo(Education::class, 'education_id', 'id');
    }

    public function userCreated()
    {
        return $this->belongsTo(User::class, 'user_created_id', 'id');
    }
}
