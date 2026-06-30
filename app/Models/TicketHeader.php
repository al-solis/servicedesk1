<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketHeader extends Model
{
    public $timestamps = false;
    protected $table = 'ticket_header';

    protected $fillable = [
        'ticket_number',
        'description',
        'user_id',
        'priority',
        'type',
        'status',
        'date_created',
        'date_closed',
    ];

    public function details()
    {
        return $this->hasMany(TicketDetail::class, 'ticket_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function ticket_type()
    {
        return $this->belongsTo(TicketType::class, 'type', 'id');
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'assigned_ticket', 'ticket_id', 'user_id');
    }

    public function images()
    {
        return $this->hasMany(TicketImage::class);
    }
}
