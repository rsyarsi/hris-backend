<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpertiseCertificationCandidate extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'expertise_certification_candidates';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'candidate_id',
        'type_of_expertise',
        'qualification_type',
        'given_by',
        'year',
        'description',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id');
    }
}
