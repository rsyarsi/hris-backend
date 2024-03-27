<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmergencyContactCandidate extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'emergency_contact_candidates';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'candidate_id',
        'relationship_id',
        'name',
        'sex_id',
        'address',
        'phone_number',
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
}
