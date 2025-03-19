<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketImage extends Model
{
    protected $fillable = ['ticket_id', 'user_id', 'img_path'];

    public function ticket()
    {
        return $this->belongsTo(TicketHeader::class, 'ticket_id', 'id');
    }

}
