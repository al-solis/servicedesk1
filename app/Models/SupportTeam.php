<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTeam extends Model
{
    protected $table = "support_team";

    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
    ];

    public function supportMembers()
    {
        return $this->hasMany(SupportMember::class, 'team_id', 'id');
    }
}
