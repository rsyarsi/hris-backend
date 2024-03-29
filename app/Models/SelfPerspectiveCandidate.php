<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SelfPerspectiveCandidate extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'self_perspective_candidates';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'candidate_id',
        'self_perspective',
        'strengths',
        'weaknesses',
        'successes',
        'failures',
        'career_overview',
        'future_expectations',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id');
    }
}
