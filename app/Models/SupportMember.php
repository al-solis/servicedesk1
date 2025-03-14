<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportMember extends Model
{
    protected $table = "support_member";

    protected $fillable = [
        'team_id',
        'user_id',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function supportTeam()
    {
        return $this->belongsTo(SupportTeam::class, 'team_id', 'id');
    }

}
