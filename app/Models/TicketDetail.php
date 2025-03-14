<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketDetail extends Model
{
    protected $table = 'ticket_detail';
    protected $fillable = [
        'ticket_id',
        'message',
        'date_created',
        'user_id'
    ];

    public function ticket()
    {
        return $this->belongsTo(TicketHeader::class, 'ticket_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
