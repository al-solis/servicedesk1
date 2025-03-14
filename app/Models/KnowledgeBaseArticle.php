<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Prompts\Table;

class KnowledgeBaseArticle extends Model
{
    protected $table = 'knowledge_base_articles';

    protected $fillable = [
        'title',
        'content',
        'category_id',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];

    public function category()
    {
        return $this->belongsTo(TicketType::class, 'category_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
