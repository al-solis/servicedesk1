<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketFile extends Model
{
    protected $table = 'ticket_files';
    protected $fillable = ['ticket_id', 'user_id', 'file_name', 'file_path', 'file_type'];

    public function ticket()
    {
        return $this->belongsTo(TicketHeader::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
