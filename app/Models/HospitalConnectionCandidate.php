<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HospitalConnectionCandidate extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'hospital_connection_candidates';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'candidate_id',
        'relationship_id',
        'name',
        'department_id',
        'position_id',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id');
    }

    public function relationship()
    {
        return $this->belongsTo(Relationship::class, 'relationship_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }
}
