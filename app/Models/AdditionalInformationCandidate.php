<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdditionalInformationCandidate extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'additional_information_candidates';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'candidate_id',
        'physical_condition',
        'severe_diseases',
        'hospitalizations',
        'last_medical_checkup',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id');
    }
}
