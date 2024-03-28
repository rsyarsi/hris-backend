<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EducationBackgroundCandidate extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'education_background_candidates';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'candidate_id',
        'education_id',
        'institution_name',
        'major',
        'started_year',
        'ended_year',
        'final_score',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id');
    }

    public function education()
    {
        return $this->belongsTo(Education::class, 'education_id', 'id');
    }
}
