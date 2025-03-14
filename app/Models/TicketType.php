<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    protected $table = 'support_type';
    protected $fillable = [
        'description',
        'default_group_id',
    ];

    public function ticket_header()
    {
        return $this->hasMany(TicketHeader::class, 'type', 'id');
    }

    public function default_group()
    {
        return $this->belongsTo(SupportTeam::class, 'default_group_id', 'id');
    }
}
