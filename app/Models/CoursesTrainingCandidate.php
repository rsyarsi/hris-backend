<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoursesTrainingCandidate extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'courses_training_candidates';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'candidate_id',
        'type_of_training',
        'level',
        'organized_by',
        'year',
        'description',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id');
    }
}
