<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CandidateRequirement extends Model
{
    use HasFactory;

    protected $fillable = ['election_id','name','description','type','required','allowed_extensions','max_file_size','sort_order','active'];
    protected function casts(): array { return ['required'=>'boolean','active'=>'boolean','allowed_extensions'=>'array']; }
    public function election() { return $this->belongsTo(Election::class); }
}

