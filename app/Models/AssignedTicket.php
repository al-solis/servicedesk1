<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignedTicket extends Model
{
    protected $table = 'assigned_ticket';
    protected $fillable = [
        'ticket_id',
        'team_id',
        'user_id',
    ];

    public function ticket()
    {
        return $this->belongsTo(TicketHeader::class, 'ticket_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function team()
    {
        return $this->belongsTo(SupportTeam::class, 'team_id', 'id');
    }
}
