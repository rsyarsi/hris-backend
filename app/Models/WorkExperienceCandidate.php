<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkExperienceCandidate extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'work_experience_candidates';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'candidate_id',
        'company',
        'position',
        'location',
        'from_date',
        'to_date',
        'job_description',
        'reason_for_resignation',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id');
    }
}
