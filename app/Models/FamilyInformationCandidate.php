<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FamilyInformationCandidate extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'family_information_candidates';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'candidate_id',
        'relationship_id',
        'name',
        'sex_id',
        'birth_place',
        'birth_date',
        'education_id',
        'job_id',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id');
    }

    public function relationship()
    {
        return $this->belongsTo(Relationship::class, 'relationship_id', 'id');
    }

    public function sex()
    {
        return $this->belongsTo(Sex::class, 'sex_id', 'id');
    }
    
    public function education()
    {
        return $this->belongsTo(Education::class, 'education_id', 'id');
    }
    
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id', 'id');
    }
}
